<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('login'); // refers to 'resources/views/login.blade.php'
    }

    // Handle login request
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|string|email', // Change 'username' to 'email'
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to dashboard or intended page
            return redirect()->intended('/dashboard');
        }

        // If authentication fails, redirect back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
