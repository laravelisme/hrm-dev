<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MasterData\Jabatan\JabatanController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('/')->group(function () {
    // Auth
    Route::get('login', [AuthController::class, 'viewLogin'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware([AuthMiddleware::class])->group(function () {
        Route::get('dashboard', function () {
            return view('pages.dashboard.index');
        })->name('admin.dashboard');

        Route::prefix('master-data')->group(function () {
            Route::prefix('jabatan')->group(function () {
                Route::get('/', [JabatanController::class, 'index'])->name('admin.master-data.jabatan.index');
                Route::get('/create', [JabatanController::class, 'create'])->name('admin.master-data.jabatan.create');
                Route::post('/store', [JabatanController::class, 'store'])->name('admin.master-data.jabatan.store');
                Route::get('/{id}/edit', [JabatanController::class, 'edit'])->name('admin.master-data.jabatan.edit');
                Route::put('/{id}/update', [JabatanController::class, 'update'])->name('admin.master-data.jabatan.update');
                Route::delete('/{id}/delete', [JabatanController::class, 'destroy'])->name('admin.master-data.jabatan.destroy');
            });
        });
    });

});
