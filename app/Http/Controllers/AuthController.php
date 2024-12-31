<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }
    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->validated());
    }
}
