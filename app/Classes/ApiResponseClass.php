<?php

namespace App\Classes;

use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    public static function success($data = null, string $message = '', int $code = 200)
    {
        $response = [
            'ok' => true,
            'data' => $data,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    public static function error(string $message = 'Ocorreu um erro inesperado.', int $code = 500, $data = null)
    {
        Log::error("API error: $message");

        $response = [
            'ok' => false,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
