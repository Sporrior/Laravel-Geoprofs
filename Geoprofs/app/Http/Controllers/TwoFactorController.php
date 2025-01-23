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
        $attemptKey = '2fa_attempts';
        $cooldownKey = '2fa_cooldown';

        if (Cache::has($cooldownKey)) {
            $remainingTime = Cache::get($cooldownKey) - time();
            return response()->json([
                'status' => 'error',
                'message' => "Je hebt een cooldown. Wacht alsjeblieft {$remainingTime} seconds voor je het weer kunt proberen",
            ], 429); 
        }

        if ($storedCode && $request->input('2fa_code') == $storedCode) {
            Cache::forget('2fa_code');
            Cache::forget($attemptKey);
            return response()->json([
                'status' => 'success',
                'message' => '2FA verification successful.',
            ], 200);
        }

        $attempts = Cache::get($attemptKey, 0) + 1;
        Cache::put($attemptKey, $attempts, now()->addMinutes(10));

        if ($attempts >= 3) {
            Cache::put($cooldownKey, time() + 300, now()->addMinutes(5));
            Cache::forget($attemptKey);
            return response()->json([
                'status' => 'error',
                'message' => 'Te veel pogingen. Wacht alsjeblieft 5 minuten voor je het weer probeert',
            ], 429);
        }

        return response()->json([
            'status' => 'error',
            'message' => "De 2FA code is fout. Je hebt nog " . (3 - $attempts) . " pogingen over.",
        ], 400);
    }
}