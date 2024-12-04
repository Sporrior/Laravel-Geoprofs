<?php

namespace App\Http\Controllers;

use App\Models\logboek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserCreateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('account-toevoegen');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voornaam' => 'required|string|max:255',
            'tussennaam' => 'nullable|string|max:255',
            'achternaam' => 'required|string|max:255',
            'telefoon' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'voornaam' => $request->voornaam,
            'tussennaam' => $request->tussennaam,
            'achternaam' => $request->achternaam,
            'telefoon' => $request->telefoon,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verlof_dagen' => 25,
        ]);

        $loggedInUser = auth()->user();
        $voornaam = $loggedInUser->voornaam;
        $achternaam = $loggedInUser->achternaam;
        $role = $loggedInUser->role->roleName;

        logboek::class::create([
            'user_id' => $loggedInUser->id,
            'actie' => 'Profile updated door gebruiker: ' . $voornaam . ' ' . $achternaam . 'met een rol van ' . $role,
            'actie_beschrijving' => $voornaam . ' Heeft een account aangemaakt ' . $request->voornaam . ' ' . $request->achternaam . " " . $request->email . " " . $request->telefoon,
            'actie_datum' => now(),
        ]);
        return redirect()->route('account-toevoegen.index')->with('success', 'User successfully created.');
    }
}
