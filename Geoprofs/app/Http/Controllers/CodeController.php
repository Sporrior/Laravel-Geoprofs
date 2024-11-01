<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CodeController extends Controller
{
    // Endpoint to store the generated code sent from Flutter
    public function storeCode(Request $request): JsonResponse
    {
        $code = $request->query('code'); // Get code from Flutter as a query parameter

        // Store the code in cache with a 10-second expiration time
        Cache::put('generated_code', $code, 10); // 10 seconds

        return response()->json(['status' => 'success', 'message' => 'Code stored successfully']);
    }

    // Endpoint to check the code entered on the website
    public function checkCode(Request $request): JsonResponse
    {
        $inputCode = $request->query('code'); // Code entered on the website

        // Retrieve the code from cache
        $storedCode = Cache::get('generated_code');

        if ($inputCode === $storedCode) {
            return response()->json(['status' => 'success', 'message' => 'Code is valid']);
        }

        return response()->json(['status' => 'error', 'message' => 'Code is invalid']);
    }
}
