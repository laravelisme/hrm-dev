<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/test/set-test', [\App\Http\Controllers\CalonKaryawan\TestTulis\TestTulisController::class, 'setTest'])->middleware([\App\Http\Middleware\AllowIp::class]);

Route::get('/host-check', fn () => request()->getHost());

