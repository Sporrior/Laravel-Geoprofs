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

        $userId = $request->input('user_id') ?? 'guest';
        $cacheKeyCode = '2fa_code_' . $userId;
        $cacheKeyAttempts = '2fa_attempts_' . $userId;
        $cacheKeyTimeout = '2fa_timeout_' . $userId;

        if (Cache::has($cacheKeyTimeout)) {
            $remainingTime = Cache::get($cacheKeyTimeout) - time();
            return response()->json([
                'status' => 'error',
                'message' => "You are timed out. Please wait {$remainingTime} seconds before trying again.",
            ]);
        }

        $storedCode = Cache::get($cacheKeyCode);

        if ($storedCode && $request->input('2fa_code') == $storedCode) {
            Cache::forget($cacheKeyAttempts);
            Cache::forget($cacheKeyTimeout);
            Cache::forget($cacheKeyCode);

            return response()->json([
                'status' => 'success',
                'message' => '2FA verification successful!',
            ]);
        }

        $attempts = Cache::increment($cacheKeyAttempts, 1);

        if ($attempts === 1) {
            Cache::put($cacheKeyAttempts, 1, now()->addMinutes(10));
        }

        $remainingAttempts = 3 - $attempts;

        if ($remainingAttempts <= 0) {
            Cache::put($cacheKeyTimeout, time() + 10, now()->addSeconds(10));
            Cache::forget($cacheKeyAttempts);

            return response()->json([
                'status' => 'error',
                'message' => 'Too many failed attempts. Please wait 10 seconds before trying again.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => "The code you entered is incorrect. You have {$remainingAttempts} more attempt(s) before being timed out.",
        ]);
    }
}