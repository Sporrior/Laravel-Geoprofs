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
            if ($user->failed_login_attempts >= 2) {
                if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
                    $remainingSeconds = now()->diffInSeconds($user->blocked_until);
                    return back()->withErrors([
                        'email' => json_encode([
                            'message' => 'Your account is locked.',
                            'remaining_seconds' => $remainingSeconds,
                        ]),
                    ]);
                }else {
                    $remainingSeconds = 0;
                }
    
                // Blokkeer account voor 5 minuten
                $user->update([
                    'blocked_until' => now()->addMinutes(5),
                    'failed_login_attempts' => $user->failed_login_attempts + 1,
                ]);
    
                return back()->withErrors([
                    'email' => 'Too many failed login attempts. Your account is now locked for 5 minutes.',
                ]);
            }
    
            if (!Auth::attempt($credentials) && $user->blocked_until === null) {
                $user->increment('failed_login_attempts');
                return back()->withErrors(['password' => 'The provided password is incorrect.']);
            }
    
            // Login succesvol: reset login attempts
            $user->update([
                'failed_login_attempts' => 0,
                'blocked_until' => null,
            ]);
    
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
    
        // Als de gebruiker niet bestaat
        return back()->withErrors(['email' => 'No account found with this email address.']);
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
            Cache::forget('2fa_code');
            return redirect()->route('dashboard'); 
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
