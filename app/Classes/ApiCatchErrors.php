<?php

namespace App\Classes;

use App\Enums\HttpStatus;
use App\ErrorMessageTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiCatchErrors
{
    use ErrorMessageTrait;
    public static function rollback(Throwable $error, string $message = null): void
    {
        DB::rollBack();
        self::throwException($error, $message);
    }

    /**
     * Throw an appropriate exception based on the error type.
     */
    public static function throwException(Throwable $error, string $message = null, int $status = HttpStatus::INTERNAL_SERVER_ERROR): void
    {
        Log::error($error);
        $errorDetails = self::getErrorStatusAndMessage($error, $message, $status);
        self::throw([config('common.generic_error_key') => $errorDetails['message']], $errorDetails['status']);
    }

    /**
     * Handle validation errors and throw an exception.
     */
    public static function validationError(Validator $validator): void
    {
        $errors = collect($validator->errors()->messages())
            ->map(fn ($messages) => $messages[0])
            ->toArray();

        self::throw($errors, HttpStatus::UNPROCESSABLE_CONTENT);
    }

    /**
     * Throw an HTTP response exception with the given errors and status.
     */
    private static function throw(array|string $errors = ['Something went wrong! Process not completed'], int $status = HttpStatus::INTERNAL_SERVER_ERROR): void
    {
        throw new HttpResponseException(response()->json(['error' => $errors], $status));
    }
}
