<?php

namespace App\Http\Controllers;

use App\Models\Logboek;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccounttoevoegenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::all();
        return view('account-toevoegen', compact('roles',"user_info"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voornaam' => 'required|string|max:255',
            'tussennaam' => 'nullable|string|max:255',
            'achternaam' => 'required|string|max:255',
            'telefoon' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:user_info,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'password' => Hash::make($request->password),
        ]);

        UserInfo::create([
            'id' => $user->id,
            'voornaam' => $request->voornaam,
            'tussennaam' => $request->tussennaam,
            'achternaam' => $request->achternaam,
            'telefoon' => $request->telefoon,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        $loggedInUser = auth()->user();
        Logboek::create([
            'user_id' => $loggedInUser->id,
            'actie' => 'User account created',
            'actie_beschrijving' => "{$loggedInUser->voornaam} created a new account for {$request->voornaam} {$request->achternaam}",
            'actie_datum' => now(),
        ]);

        return redirect()->route('account-toevoegen.index')->with('success', 'Gebruiker succesvol aangemaakt.');
    }
}