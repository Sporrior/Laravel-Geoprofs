<?php
namespace App\Http\Controllers;

use App\Models\logboek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ProfielController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $users = User::whereHas('role', function ($query) {
            $query->where('roleName', 'werknemer');
        })->get();

        Log::info('Profiel page viewed by user ID: ' . $user->id);

        return view('profiel', compact('user', 'users'));
    }

    public function edit()
    {
        $user = Auth::user();
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
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        Log::info('Updating profile for user ID: ' . $user->id);

        if ($request->hasFile('profielFoto')) {
            if ($user->profielFoto) {
                Log::info('Deleting old profile photo for user ID: ' . $user->id);
                Storage::delete('public/' . $user->profielFoto);
            }

            $file = $request->file('profielFoto');
            $filename = time() . '_' . $file->getClientOriginalName();
            Log::info('New profile photo uploaded for user ID: ' . $user->id . ' - Filename: ' . $filename);

            $file->storeAs('profile_pictures', $filename, 'public');

            $user->profielFoto = 'profile_pictures/' . $filename;
        }

        $user->voornaam = $request->voornaam;
        $user->tussennaam = $request->tussennaam;
        $user->achternaam = $request->achternaam;
        $user->telefoon = $request->telefoon;
        $user->email = $request->email;

        $user->save();
        Log::info('Profile updated for user ID: ' . $user->id);

        logboek::class::create([
            'user_id' => auth()->user()->id,
            'actie' => 'Profile updated door gebruiker: ' . $user->voornaam . ' ' . $user->achternaam . ' met een rol van ' . $user->role->roleName,
            'actie_beschrijving' => 'de volgende gegevens zijn bijgewerkt: ' . $request->voornaam . ' ' . $request->achternaam . ' ' . $request->email . ' ' . $request->telefoon,
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
        logboek::class::create([
            'user_id' => auth()->user()->id,
            'actie' => 'Password changed door gebruiker: ' . $user->voornaam . ' ' . $user->achternaam . ' met een rol van ' . $user->role->roleName,
            'actie_beschrijving' => 'Wachtwoord is gewijzigd',
            'actie_datum' => now(),
        ]);
        return redirect()->back()->with('success', 'Wachtwoord succesvol gewijzigd');
    }
}
