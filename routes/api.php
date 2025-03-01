<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StakeholderController;
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

    Route::get('/categories/get-all', [CategoryController::class,'getAll'])->name('categories.get-all');
    Route::apiResource('/categories', CategoryController::class)->names('categories');

    Route::get('/stakeholders/get-all', [StakeholderController::class,'getAll'])->name('stakeholders.get-all');
    Route::apiResource('/stakeholders', StakeholderController::class)->names('stakeholders');

    Route::get('/projects/get-all', [ProjectController::class,'getAll'])->name('projects.get-all');
    Route::apiResource('/projects', ProjectController::class)->names('projects');

    Route::get('/accounts/get-all', [AccountsController::class,'getAll'])->name('accounts.get-all');
    Route::apiResource('/accounts', AccountsController::class)->names('accounts');

    Route::apiResource('/transactions', TransactionController::class)->names('transactions');

    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
