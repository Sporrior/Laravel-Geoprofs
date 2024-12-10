<?php

namespace App\Http\Controllers;

use App\Models\Logboek;
use App\Models\Role;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfielController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user_info = UserInfo::with(['role', 'team'])->findOrFail($user->id);

        // Fetch users in the same team as the logged-in user with the role 'werknemer'
        $users = UserInfo::with('role')
            ->where('team_id', $user_info->team_id)
            ->whereHas('role', function ($query) {
                $query->where('role_name', 'werknemer');
            })
            ->get();

        Log::info('Profiel page viewed by user ID: ' . $user->id);

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
                Storage::delete('public/' . $user_info->profielFoto);
            }

            $file = $request->file('profielFoto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile_pictures', $filename, 'public');
            $user_info->profielFoto = 'profile_pictures/' . $filename;
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
            'actie' => 'Profile updated by user: ' . $user_info->getFullNameAttribute(),
            'actie_beschrijving' => 'Profile updated successfully.',
            'actie_datum' => now(),
        ]);

        return redirect()->back()->with('success', 'Profiel succesvol bijgewerkt');
    }
}