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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $nameParts = explode(' ', $validatedData['name']);
        $voorNaam = $nameParts[0];
        $tussenNaam = count($nameParts) === 3 ? $nameParts[1] : null;
        $achterNaam = count($nameParts) > 1 ? end($nameParts) : '';

        $user = User::create([
            'voorNaam' => $voorNaam,
            'tussenNaam' => $tussenNaam,
            'achterNaam' => $achterNaam,
            'profielFoto' => null, 
            'telefoon' => '', 
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => null,
        ]);

        auth()->login($user);

        return redirect('/dashboard');
    }
}