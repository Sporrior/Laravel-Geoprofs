<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class LoginController extends Controller
//{
//    public function showLoginForm(Request $request)
//    {
//        $errorMessage = session('error_message', null);
//        return view('login', ['error_message' => $errorMessage]);
//    }
//
//    public function login(Request $request)
//    {
//        $credentials = $request->validate([
//            'email' => 'required|string|email',
//            'password' => 'required|string',
//        ]);
//
//        $remember = $request->has('remember');
//
//        if (Auth::attempt($credentials, $remember)) {
//            $request->session()->regenerate();
//
//            $twoFACode = mt_rand(100000, 999999);
//            $request->session()->put('2fa_code', $twoFACode);
//            $request->session()->put('2fa_attempts', 0);
//
//            return redirect()->route('2fa.show');
//        }
//
//        return back()->withErrors([
//            'email' => 'The provided credentials do not match our records.',
//        ])->onlyInput('email');
//    }
//
//    public function show2faForm(Request $request)
//    {
//        if (!$request->session()->has('2fa_code')) {
//            return redirect()->route('login');
//        }
//
//        return view('2fa', [
//            'twoFACode' => $request->session()->get('2fa_code'),
//        ]);
//    }
//
//    public function verify2fa(Request $request)
//    {
//        $request->validate([
//            '2fa_code' => 'required|numeric',
//        ]);
//
//        $storedCode = $request->session()->get('2fa_code');
//        $attempts = $request->session()->get('2fa_attempts', 0);
//
//        if ($request->input('2fa_code') == $storedCode) {
//            $request->session()->forget(['2fa_code', '2fa_attempts']);
//            return redirect()->route('dashboard2');
//        }
//
//        $attempts++;
//        $request->session()->put('2fa_attempts', $attempts);
//
//        if ($attempts >= 3) {
//            Auth::logout();
//            $request->session()->invalidate();
//            $request->session()->regenerateToken();
//
//            return redirect('/login')->with('error_message', '3 failed attempts, logging you out.');
//        }
//
//        $newTwoFACode = mt_rand(100000, 999999);
//        $request->session()->put('2fa_code', $newTwoFACode);
//
//        return back()->withErrors([
//            '2fa_code' => 'The provided 2FA code is incorrect. A new code has been generated.',
//        ]);
//    }
//
//    public function regenerate2fa(Request $request)
//    {
//        $newTwoFACode = mt_rand(100000, 999999);
//        $request->session()->put('2fa_code', $newTwoFACode);
//
//        return response()->json([
//            'new_code' => $newTwoFACode,
//        ]);
//    }
//
//    public function logout(Request $request)
//    {
//        Auth::logout();
//
//        $request->session()->invalidate();
//
//        $request->session()->regenerateToken();
//
//        return redirect('/login');
//    }
//}


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

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

        // Retrieve the user by email
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check if the account is permanently locked
            if ($user->account_locked) {
                return back()->withErrors([
                    'email' => 'Uw account is permanent geblokkeerd na meerdere mislukte inlogpogingen.',
                ]);
            }

            // Check if the user is temporarily blocked
            if ($user->blocked_until && now()->lessThan($user->blocked_until)) {
                $blockedMinutes = now()->diffInMinutes($user->blocked_until);
                return back()->withErrors([
                    'email' => "U bent geblokkeerd. Probeer het opnieuw over {$blockedMinutes} minuut/minuten.",
                ]);
            }

            $remember = $request->has('remember');

            if (Auth::attempt($credentials, $remember)) {
                // Successful login
                $user->update([
                    'failed_login_attempts' => 0,
                    'blocked_until' => null,
                ]);

                $request->session()->regenerate();

                // Generate 5-digit 2FA code and store it in the session
                $twoFACode = mt_rand(10000, 99999);
                $request->session()->put('2fa_code', $twoFACode);
                $request->session()->put('2fa_attempts', 0);

                return redirect()->route('2fa.show');
            } else {
                // Handle failed login attempt logic
                // ...
            }
        } else {
            return back()->withErrors([
                'email' => 'De opgegeven gegevens komen niet overeen met onze gegevens.',
            ]);
        }
    }

    public function show2faForm(Request $request)
    {
        if (!$request->session()->has('2fa_code')) {
            return redirect()->route('login');
        }

        return view('2fa');
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric|digits:5',
        ]);

        $storedCode = $request->session()->get('2fa_code');
        $attempts = $request->session()->get('2fa_attempts', 0);

        if ($request->input('2fa_code') == $storedCode) {
            $request->session()->forget(['2fa_code', '2fa_attempts']);
            return response()->json(['success' => true]);
        }

        $attempts++;
        $request->session()->put('2fa_attempts', $attempts);

        if ($attempts >= 3) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['success' => false, 'message' => 'Too many failed attempts. You have been logged out.']);
        }

        $newTwoFACode = mt_rand(10000, 99999);
        $request->session()->put('2fa_code', $newTwoFACode);

        return response()->json(['success' => false, 'message' => 'Incorrect code. A new code has been generated.']);
    }

    public function regenerate2fa(Request $request)
    {
        $newTwoFACode = mt_rand(10000, 99999);
        $request->session()->put('2fa_code', $newTwoFACode);

        return response()->json([
            'new_code' => $newTwoFACode,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}


