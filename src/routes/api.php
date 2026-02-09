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
        Route::post('/change-password', [\App\Http\Controllers\Api\Auth\AuthController::class, 'changePassword'])->middleware('auth:api');
        Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->middleware('auth:api');
    });

    Route::prefix('master')->middleware('auth:api')->group(function () {
       Route::get('/izin-type', [\App\Http\Controllers\Api\Master\MasterController::class, 'getJenisIzin']);
       Route::get('/cuti-type', [\App\Http\Controllers\Api\Master\MasterController::class, 'getJenisCuti']);
       Route::get('/sp-type', [\App\Http\Controllers\Api\Master\MasterController::class, 'getJenisSP']);
       Route::get('/list-bawahan', [\App\Http\Controllers\Api\Master\MasterController::class, 'getListBawahan']);
       Route::get('/jabatan-type', [\App\Http\Controllers\Api\Master\MasterController::class, 'getJenisJabatan']);
       Route::get('list-city', [\App\Http\Controllers\Api\Master\MasterController::class, 'getWorkLocation']);
    });

    Route::prefix('izin')->middleware('auth:api')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Izin\IzinController::class, 'index']);
        Route::post('/submit', [\App\Http\Controllers\Api\Izin\IzinController::class, 'store']);
        Route::get('/list-approval', [\App\Http\Controllers\Api\Izin\IzinController::class, 'getListApproval']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Izin\IzinController::class, 'showIzin']);
        Route::post('/approval/{id}', [\App\Http\Controllers\Api\Izin\IzinController::class, 'approvalIzin']);
    });

});

