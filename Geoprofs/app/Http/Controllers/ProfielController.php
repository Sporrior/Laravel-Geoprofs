<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfielController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profiel', compact('user')); 
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
            'profielFoto' => 'nullable|url',
            'telefoon' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();

        $user->voornaam = $request->voornaam;
        $user->tussennaam = $request->tussennaam;
        $user->achternaam = $request->achternaam;
        $user->profielFoto = $request->profielFoto;
        $user->telefoon = $request->telefoon;
        $user->email = $request->email;

        $user->save();

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

        return redirect()->back()->with('success', 'Wachtwoord succesvol gewijzigd');
    }
}
