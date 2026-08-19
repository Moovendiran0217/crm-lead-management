<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FollowupController;
use App\Http\Controllers\Api\LeadController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [
    AuthController::class,
    'login',
]);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ]);

    Route::get('/me', [
        AuthController::class,
        'me',
    ]);

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ]);

    Route::apiResource('leads', LeadController::class);

    Route::post(
        '/leads/{lead}/followups',
        [FollowupController::class, 'store']
    );

    Route::get(
        '/leads/{lead}/followups',
        [FollowupController::class, 'index']
    );

    Route::put(
        '/followups/{followup}',
        [FollowupController::class, 'update']
    );
});
