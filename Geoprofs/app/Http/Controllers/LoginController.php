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
        $errorMessage = session('error_message', null);
        return view('login', ['error_message' => $errorMessage]);
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
                    'email' => 'Uw account is permanent geblokkeerd na meerdere mislukte inlogpogingen.',
                ]);
            }

            if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
                $blockedMinutes = now()->diffInMinutes($user->blocked_until);
                return back()->withErrors([
                    'email' => "U bent geblokkeerd. Probeer het opnieuw over {$blockedMinutes} minuut/minuten.",
                ]);
            }

            $remember = $request->has('remember');

            if (Auth::attempt($credentials, $remember)) {
                $user->update(['failed_login_attempts' => 0, 'blocked_until' => null]);

                $request->session()->regenerate();

                return redirect()->route('2fa.show');
            }
        }

        return back()->withErrors(['email' => 'De opgegeven gegevens komen niet overeen met onze gegevens.']);
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

        Cache::put('2fa_code', $code, now()->addSeconds(10));

        return response()->json(['status' => 'success', 'message' => 'Code stored successfully']);
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric|digits:5',
        ]);

        $storedCode = Cache::get('2fa_code');

        if ($request->input('2fa_code') == $storedCode) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Incorrect code.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
