<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CodeController extends Controller
{
    public function storeCode(Request $request): JsonResponse
    {
        $code = $request->query('code');

        Cache::put('generated_code', $code, 10); 

        return response()->json(['status' => 'success', 'message' => 'Code stored successfully']);
    }

    public function checkCode(Request $request): JsonResponse
    {
        $inputCode = $request->query('code');

        $storedCode = Cache::get('generated_code');

        if ($inputCode === $storedCode) {
            return response()->json(['status' => 'success', 'message' => 'Code is valid']);
        }

        return response()->json(['status' => 'error', 'message' => 'Code is invalid']);
    }
}
