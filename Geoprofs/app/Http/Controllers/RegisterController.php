<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user_info',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $nameParts = explode(' ', $validatedData['name']);
        $voornaam = $nameParts[0];
        $tussennaam = count($nameParts) === 3 ? $nameParts[1] : null;
        $achternaam = count($nameParts) > 1 ? end($nameParts) : '';

        $user = User::create([
            'password' => Hash::make($validatedData['password']),
        ]);

        // user_info en geen user tabel...
        $userInfo = $user->userInfo()->create([
            'voornaam' => $voornaam,
            'tussennaam' => $tussennaam,
            'achternaam' => $achternaam,
            'email' => $validatedData['email'],
            'telefoon' => $request->telefoon ?? '', 
            'role_id' => 1, 
            'team_id' => 1,
        ]);

        auth()->login($user);

        return redirect('/dashboard');
    }
}