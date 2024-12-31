<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'test'], function () {
    Route::get('/', [TestController::class, 'index']);
    Route::get('/exception', [TestController::class, 'exception']);
    Route::post('/validation', [TestController::class, 'validation']);
    Route::get('/manual-unprocessable', [TestController::class, 'manualUnprocessable']);
    Route::get('/manual-server-error', [TestController::class, 'manualServer']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('test/auth', [TestController::class, 'authUser']);

    Route::apiResource('/categories', CategoryController::class)->names('categories');
    Route::apiResource('/people', PeopleController::class)->names('people');
    Route::apiResource('/projects', ProjectController::class)->names('projects');
    Route::apiResource('/accounts', AccountsController::class)->names('accounts');
    Route::apiResource('/transactions', TransactionController::class)->names('transactions');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
