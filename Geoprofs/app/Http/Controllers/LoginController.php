<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
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
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find the user in the user_info table by email
        $user_info = UserInfo::where('email', $credentials['email'])->first();

        if (!$user_info) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        // Check if the account is locked
        if ($user_info->account_locked) {
            return back()->withErrors([
                'email' => 'Your account is permanently locked due to multiple failed login attempts.',
            ]);
        }

        // Check if the account is temporarily blocked
        if ($user_info->blocked_until && now()->lessThan($user_info->blocked_until)) {
            $blockedMinutes = now()->diffInMinutes($user_info->blocked_until);
            return back()->withErrors([
                'email' => "You are blocked. Try again in {$blockedMinutes} minute(s).",
            ]);
        }

        // Retrieve the password from the users table
        $user_password = User::where('id', $user_info->id)->value('password');

        // Verify the password
        if (!Hash::check($credentials['password'], $user_password)) {
            // Increment failed login attempts
            $user_info->increment('failed_login_attempts');

            // Block the user if too many failed attempts
            if ($user_info->failed_login_attempts >= 5) {
                $user_info->update(['blocked_until' => now()->addMinutes(15)]);
                return back()->withErrors(['email' => 'Too many failed login attempts. Try again in 15 minutes.']);
            }

            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        // Reset failed login attempts and blocked status
        $user_info->update(['failed_login_attempts' => 0, 'blocked_until' => null]);

        // Manually log in the user
        Auth::loginUsingId($user_info->id, $request->has('remember'));

        // Regenerate the session to prevent session fixation
        $request->session()->regenerate();

        return redirect()->route('2fa.show');
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