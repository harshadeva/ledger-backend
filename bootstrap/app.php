<?php

use App\Enums\HttpStatus;
use App\ErrorMessageTrait;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $e) {
            $errorDetails = ErrorMessageTrait::getErrorStatusAndMessage($e, null, $status =  $statusCode = $response->getStatusCode());
            $message = $errorDetails['message'];
            $status = $errorDetails['status'];
            return response()->json(['error' => [
                config('common.generic_error_key') => $message
            ]], $status);
        });
    })->create();
