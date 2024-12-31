<?php

namespace App;

use App\Enums\HttpStatus;
use ErrorException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

trait ErrorMessageTrait
{
    public static function getErrorStatusAndMessage(Throwable $error, ?string $message, int $status): array
    {
        if (config('app.debug') && $message == null) {
            $message = $error->getMessage() . ' on line ' . $error->getLine() . ' in ' . $error->getFile();
        } else {
            $message = 'Something went wrong';
        }
        if ($error instanceof HttpException) {
            Log::error($error);
            //handled errors ( manually trowing )
            $status = $error->getStatusCode();
            $message = $error->getMessage();
        } elseif ($error instanceof ErrorException) {
            $status = HttpStatus::INTERNAL_SERVER_ERROR;
            // exceptions. Keep the default message for exception message, and also keep server error status
        }
        else{
            Log::error($error);
        }
        return ['message' => $message,'status' => $status];
    }
}
