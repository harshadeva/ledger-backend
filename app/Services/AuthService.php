<?php

namespace App\Services;

use App\Classes\ApiCatchErrors;
use App\Enums\HttpStatus;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function getAuthUser(): ?Authenticatable
    {
        return Auth::user();
    }

    public function login(array $data): array
    {
        try {
            $user = User::where('email', $data['email'])->first();
            if ($user == null) {
                throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Username or password invalid');
            }

            // Match user password and login
            if (! Hash::check($data['password'], $user->password)) {
                throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Username or password invalid');
            }

            $token = $user->createToken('payment-ledger')->accessToken;
            return ['token' => $token, 'user' => $user, 'message' => 'User logged successfully'];
        } catch (Exception $e) {
            ApiCatchErrors::rollback($e);
        }
    }
}
