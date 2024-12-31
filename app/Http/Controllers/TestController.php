<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatus;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\TestValidationRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestController extends Controller
{
    public function index()
    {
        return 'Ledger APIs working';
    }

    public function exception()
    {
        return new LoginRequest('dd');
    }

    public function validation(TestValidationRequest $request)
    {
        return 'test validation function not reach to this line';
    }

    public function manualUnprocessable()
    {
        throw new HttpException(HttpStatus::UNPROCESSABLE_CONTENT, 'Test unprocessable error reason');
    }

    public function manualServer()
    {
        throw new HttpException(HttpStatus::INTERNAL_SERVER_ERROR, 'Test internal server error reason');
    }

    public function authUser()
    {
        return Auth::user();
    }
}
