<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MasterData\Jabatan\JabatanController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return redirect()->route('admin.dashboard');
//});

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // your actual routes
    });
}


