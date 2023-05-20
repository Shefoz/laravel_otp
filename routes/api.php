<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/signup', [UserController::class, 'studentRegister']);
Route::post('/login', [UserController::class, 'userLogin']);
Route::post('/email/verify/{id}', [UserController::class, 'verifiedOtp']);
Route::post('/email/resend/{id}', [UserController::class, 'resendOtp']);

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/dashboard', [UserController::class, 'loadDashboard']);
});

