<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TwoFactorController extends Controller
{
    /**
     * Generate and store 2FA code.
     */
    public function storeCode(Request $request)
    {
        $code = random_int(100000, 999999);

        $cacheKey = '2fa_code_' . ($request->input('user_id') ?? 'guest');

        Cache::put($cacheKey, $code, now()->addMinutes(10));

        return response()->json([
            'status' => 'success',
            'message' => '2FA code generated successfully',
            'code' => $code
        ], 200);
    }

    /**
     * Verify 2FA code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric|digits:6',
        ]);

        $cacheKey = '2fa_code_' . ($request->input('user_id') ?? 'guest');
        $storedCode = Cache::get($cacheKey);

        if ($storedCode && $request->input('2fa_code') == $storedCode) {
            Cache::forget($cacheKey);

            return response()->json([
                'status' => 'success',
                'message' => '2FA verification successful!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'The code you entered is incorrect.',
        ]);
    }
}