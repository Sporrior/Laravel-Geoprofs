<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\UserInfo;

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

        $userInfo = UserInfo::where('email', $credentials['email'])->first();

        if ($userInfo) {
            $user = $userInfo->user;

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

            // Attempt login
            if (Auth::attempt(['user_id' => $userInfo->id, 'password' => $credentials['password']], $remember)) {
                $user->update(['failed_login_attempts' => 0, 'blocked_until' => null]);

                $request->session()->regenerate();

                return redirect()->route('2fa.show');
            }
        }

        // If credentials do not match
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