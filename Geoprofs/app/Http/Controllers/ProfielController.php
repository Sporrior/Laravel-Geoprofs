<?php

namespace App\Http\Controllers;

use App\Models\logboek;
use App\Models\Role; // Ensure this model exists and is imported
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

        $users = UserInfo::with('role')->whereHas('role', function ($query) {
            $query->where('role_id', 'werknemer');
        })->get();

        Log::info('Profiel page viewed by user ID: ' . $user->id);

        return view('profiel', compact('user', 'users'));
    }

    public function edit()
    {
        $user = Auth::user()->load('info');
        return view('profiel.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'voornaam' => 'required|string|max:255',
            'tussennaam' => 'nullable|string|max:255',
            'achternaam' => 'required|string|max:255',
            'profielFoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'telefoon' => 'nullable|string|max:15',
            'verlof_dagen' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:255|unique:user_info,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $userInfo = $user->info;

        if ($request->hasFile('profielFoto')) {
            if ($userInfo->profielFoto) {
                Storage::delete('public/' . $userInfo->profielFoto);
            }

            $file = $request->file('profielFoto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile_pictures', $filename, 'public');
            $userInfo->profielFoto = 'profile_pictures/' . $filename;
        }

        $userInfo->voornaam = $request->voornaam;
        $userInfo->tussennaam = $request->tussennaam;
        $userInfo->achternaam = $request->achternaam;
        $userInfo->telefoon = $request->telefoon;
        $userInfo->email = $request->email;
        $userInfo->save();

        logboek::create([
            'user_id' => $user->id,
            'actie' => 'Profile updated door gebruiker: ' . $userInfo->voornaam . ' ' . $userInfo->achternaam . ' met een rol van ' . optional($userInfo->role)->role_name,
            'actie_beschrijving' => 'De volgende gegevens zijn bijgewerkt: ' . $request->voornaam . ' ' . $request->achternaam . ' ' . $request->email . ' ' . $request->telefoon,
            'actie_datum' => now(),
        ]);

        return redirect()->back()->with('success', 'Profiel succesvol bijgewerkt');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'huidigWachtwoord' => 'required',
            'nieuwWachtwoord' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->huidigWachtwoord, $user->password)) {
            return back()->withErrors(['huidigWachtwoord' => 'Huidig wachtwoord is incorrect.']);
        }

        $user->password = Hash::make($request->nieuwWachtwoord);
        $user->save();

        logboek::create([
            'user_id' => $user->id,
            'actie' => 'Password changed door gebruiker: ' . $user->info->voornaam . ' ' . $user->info->achternaam . ' met een rol van ' . optional($user->info->role)->role_name,
            'actie_beschrijving' => 'Wachtwoord is gewijzigd',
            'actie_datum' => now(),
        ]);

        return redirect()->back()->with('success', 'Wachtwoord succesvol gewijzigd');
    }
}