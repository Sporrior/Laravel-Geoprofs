<?php

namespace App\Http\Controllers;

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
        // Show the user registration form
        return view('addusers');
    }

    public function store(Request $request)
    {
        // Validate the user input
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

        // Redirect back with success message
        return redirect()->route('addusers.index')->with('success', 'User successfully created.');
    }
}
