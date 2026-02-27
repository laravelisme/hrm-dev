<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MasterData\Jabatan\JabatanController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;


foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)
        ->middleware(['web'])
        ->group(function () {

            require base_path('routes/shared_admin.php');

            Route::prefix('/tenancy')->group(function () {
                Route::prefix('/domain')->group(function () {
                    Route::get('/', [\App\Http\Controllers\Tenancy\TenancyController::class, 'index'])->name('tenancy.domain.index');
                    Route::get('/create', [\App\Http\Controllers\Tenancy\TenancyController::class, 'create'])->name('tenancy.domain.create');
                    Route::post('/store', [\App\Http\Controllers\Tenancy\TenancyController::class, 'store'])->name('tenancy.domain.store');
                    Route::delete('/destroy/{id}', [\App\Http\Controllers\Tenancy\TenancyController::class, 'destroy'])->name('tenancy.domain.destroy');
                });
            });

        });
}


