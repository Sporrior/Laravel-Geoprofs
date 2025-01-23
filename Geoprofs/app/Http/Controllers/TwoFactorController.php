<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA form.
     */
    public function show2faForm()
    {
        return view('2fa');
    }

    /**
     * Generate and store a new 2FA code.
     */
    public function storeCode()
    {
        $code = random_int(100000, 999999);
        Cache::put('2fa_code', $code, now()->addMinutes(10));

        return response()->json([
            'status' => 'success',
            'message' => '2FA code generated successfully',
            'code' => $code,
        ], 200);
    }

    /**
     * Retrieve the latest 2FA code (for Discord bot).
     */
    public function getCode()
    {
        $code = Cache::get('2fa_code');

        if (!$code) {
            // Automatically generate a new code if none exists
            $code = random_int(100000, 999999);
            Cache::put('2fa_code', $code, now()->addMinutes(10));
        }

        return response()->json([
            'status' => 'success',
            'code' => $code,
        ], 200);
    }

    /**
     * Verify the 2FA code.
     */
    public function verify2fa(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric|digits:6',
        ]);

        $storedCode = Cache::get('2fa_code');

        if ($storedCode && $request->input('2fa_code') == $storedCode) {
            Cache::forget('2fa_code');
            return response()->json([
                'status' => 'success',
                'message' => '2FA verification successful.',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'The 2FA code is incorrect or has expired.',
        ], 400);
    }
}