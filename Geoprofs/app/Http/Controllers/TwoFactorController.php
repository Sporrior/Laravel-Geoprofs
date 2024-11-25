<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TwoFactorController extends Controller
{
    /**
     *
     */
    public function storeCode(Request $request)
    {
        // Generate a random 6-digit code
        $code = random_int(100000, 999999);

        Cache::put('2fa_code_' . auth()->id(), $code, now()->addMinutes(10));

        return response()->json([
            'status' => 'success',
            'message' => '2FA code generated successfully',
            'code' => $code 
        ], 200);
    }

    /**
     *
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric|digits:6',
        ]);

        $inputCode = $request->input('2fa_code');
        $storedCode = Cache::get('2fa_code_' . auth()->id());

        if ($storedCode && $inputCode == $storedCode) {
            Cache::forget('2fa_code_' . auth()->id());

            return redirect()->route('dashboard')->with('success', '2FA verification successful!');
        }

        return back()->withErrors(['2fa_code' => 'The code you entered is incorrect. Please try again.']);
    }
}
