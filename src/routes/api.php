<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/test/set-test', [\App\Http\Controllers\CalonKaryawan\TestTulis\TestTulisController::class, 'setTest'])->middleware([\App\Http\Middleware\AllowIp::class]);

Route::get('/host-check', fn () => request()->getHost());

Route::prefix('v2')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
        Route::post('/refresh-token', [\App\Http\Controllers\Api\Auth\AuthController::class, 'refreshToken']);
        Route::get('/me', [\App\Http\Controllers\Api\Auth\AuthController::class, 'me'])->middleware('auth:api');
        Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->middleware('auth:api');
    });

});

