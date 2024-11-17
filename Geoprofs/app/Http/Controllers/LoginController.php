<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if ($user->account_locked) {
                return back()->withErrors([
                    'email' => 'Your account is permanently locked due to multiple failed login attempts.',
                ]);
            }

            if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
                $blockedMinutes = now()->diffInMinutes($user->blocked_until);
                return back()->withErrors([
                    'email' => "You are blocked. Try again in {$blockedMinutes} minute(s).",
                ]);
            }

            $remember = $request->has('remember');

            if (Auth::attempt($credentials, $remember)) {
                $user->update(['failed_login_attempts' => 0, 'blocked_until' => null]);

                $request->session()->regenerate();

                return redirect()->route('2fa.show');
            }
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function show2faForm(Request $request)
    {
        return view('2fa');
    }

    public function store2faCodeFromApp(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return response()->json(['status' => 'error', 'message' => 'Code is missing'], 400);
        }

        Cache::put('2fa_code', $code, now()->addMinutes(10));

        return response()->json(['status' => 'success', 'message' => 'Code stored successfully']);
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric|digits:6',
        ]);

        $storedCode = Cache::get('2fa_code');

        if ($request->input('2fa_code') == $storedCode) {
            Cache::forget('2fa_code'); // Clear the stored code after successful verification
            return redirect()->route('dashboard'); // Change this route to your desired redirect
        }

        return back()->withErrors(['2fa_code' => 'The 2FA code is incorrect.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
