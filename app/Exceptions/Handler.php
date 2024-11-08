<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Exception: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function handleApiException($request, Throwable $exception)
    {
        $statusCode = method_exists($exception, 'getStatusCode') ? 
            $exception->getStatusCode() : 500;

        return response()->json([
            'status' => 'error',
            'message' => $exception->getMessage(),
            'error' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        ], $statusCode);
    }
} 