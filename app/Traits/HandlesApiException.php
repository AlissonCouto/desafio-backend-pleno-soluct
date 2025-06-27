<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesApiException
{
    public function handleException(Throwable $e, string $context = '')
    {
        Log::error("Erro em $context", [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'ok' => false,
            'message' => 'Ocorreu um erro inesperado. Tente novamente mais tarde.',
        ], 500);
    }
}
