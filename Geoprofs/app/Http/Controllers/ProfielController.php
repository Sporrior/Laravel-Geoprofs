<?php

namespace App\Http\Controllers;

use App\Models\Logboek;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfielController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user_info = UserInfo::with(['role:id,role_name', 'team:id,group_name'])
            ->findOrFail($user->id);

        // Fetch team members with the role 'werknemer'
        $users = UserInfo::with('role:id,role_name')
            ->select('id', 'voornaam', 'achternaam', 'email', 'telefoon', 'team_id', 'role_id')
            ->where('team_id', $user_info->team_id)
            ->whereHas('role', fn($query) => $query->where('role_name', 'werknemer'))
            ->get();

        Log::info("Profile page accessed by user ID: {$user->id}");

        return view('profiel', compact('user', 'user_info', 'users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'voornaam' => 'required|string|max:255',
            'tussennaam' => 'nullable|string|max:255',
            'achternaam' => 'required|string|max:255',
            'profielFoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'telefoon' => 'nullable|string|max:15',
            'verlof_dagen' => 'nullable|integer',
            'email' => 'required|string|email|max:255|unique:user_info,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user_info = UserInfo::findOrFail($user->id);

        if ($request->hasFile('profielFoto')) {
            if ($user_info->profielFoto) {
                Storage::disk('public')->delete($user_info->profielFoto);
            }

            $file = $request->file('profielFoto');
            $filePath = $file->store('profile_pictures', 'public');
            $user_info->profielFoto = $filePath;
        }

        $user_info->fill($request->only([
            'voornaam',
            'tussennaam',
            'achternaam',
            'telefoon',
            'email',
        ]));
        $user_info->save();

        Logboek::create([
            'user_id' => $user->id,
            'actie' => 'Profile updated',
            'actie_beschrijving' => "Updated profile details for {$user_info->voornaam} {$user_info->achternaam}",
            'actie_datum' => now(),
        ]);

        Log::info("Profile updated successfully for user ID: {$user->id}");

        return redirect()->back()->with('success', 'Profiel succesvol bijgewerkt');
    }
}