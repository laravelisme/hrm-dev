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

    Route::prefix('recruitment')->name('recruitment.')->group(function () {
        Route::get('/register', [\App\Http\Controllers\Recruitment\RegisterController::class, 'create'])->name('register');
        Route::post('/register', [\App\Http\Controllers\Recruitment\RegisterController::class, 'store'])->name('register.store');
        Route::get('/select2/companies', [\App\Http\Controllers\Recruitment\RegisterController::class, 'select2Companies'])->name('select2.companies');
        Route::get('/select2/departments', [\App\Http\Controllers\Recruitment\RegisterController::class, 'select2Departments'])->name('select2.departments');
        Route::get('/register/success', [\App\Http\Controllers\Recruitment\RegisterController::class, 'success'])->name('register.success');
    });

    Route::middleware([AuthMiddleware::class])->group(function () {
        Route::get('dashboard', function () {
            return view('pages.dashboard.index');
        })->name('admin.dashboard');

        Route::prefix('/karyawan')->middleware(['role:hr|admin'])->group(function () {
            Route::get('/', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'index'])->name('admin.karyawan.index');
            Route::get('/options/jabatan', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'jabatanOptions'])->name('admin.karyawan.options.jabatan');
            Route::get('/options/department', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'departmentOptions'])->name('admin.karyawan.options.department');
            Route::get('/options/company', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'companyOptions'])->name('admin.karyawan.options.company');
            Route::get('/create', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'create'])->name('admin.karyawan.create');
            Route::get('/{id}/show', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'show'])->name('admin.karyawan.show');
            Route::delete('/{id}/delete', [\App\Http\Controllers\Karyawan\KaryawanController::class, 'destroy'])->name('admin.karyawan.destroy');
        });

        Route::prefix('calon-karyawan')->middleware(['role:hr'])->group(function () {

            Route::post('/{id}/update-status-recruitment', [\App\Http\Controllers\CalonKaryawan\ShortlistAdmin\ShortListAdminController::class, 'updateStatus'])->name('admin.calon-karyawan.update-status-recruitment');

            Route::prefix('generate-link')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\GenerateLink\GenerateLinkController::class, 'index'])->name('admin.calon-karyawan.generate-link.index');
                Route::post('/store', [\App\Http\Controllers\CalonKaryawan\GenerateLink\GenerateLinkController::class, 'store'])->name('admin.calon-karyawan.generate-link.store');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\GenerateLink\GenerateLinkController::class, 'destroy'])->name('admin.calon-karyawan.generate-link.destroy');
            });

            Route::prefix('/shortlist-admin')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\ShortlistAdmin\ShortListAdminController::class, 'index'])->name('admin.calon-karyawan.shortlist-admin.index');
                Route::get('/{id}/show', [\App\Http\Controllers\CalonKaryawan\ShortlistAdmin\ShortListAdminController::class, 'show'])->name('admin.calon-karyawan.shortlist-admin.show');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\ShortlistAdmin\ShortListAdminController::class, 'destroy'])->name('admin.calon-karyawan.shortlist-admin.destroy');
            });

            Route::prefix('/test-tulis')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\TestTulis\TestTulisController::class, 'index'])->name('admin.calon-karyawan.test-tulis.index');
                Route::get('/{id}/show', [\App\Http\Controllers\CalonKaryawan\TestTulis\TestTulisController::class, 'show'])->name('admin.calon-karyawan.test-tulis.show');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\TestTulis\TestTulisController::class, 'destroy'])->name('admin.calon-karyawan.test-tulis.destroy');
            });

            Route::prefix('/interview')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\Interview\InterviewController::class, 'index'])->name('admin.calon-karyawan.interview.index');
                Route::get('/{id}/show', [\App\Http\Controllers\CalonKaryawan\Interview\InterviewController::class, 'show'])->name('admin.calon-karyawan.interview.show');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\Interview\InterviewController::class, 'destroy'])->name('admin.calon-karyawan.interview.destroy');
            });

            Route::prefix('/talent-pool')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\TalentPool\TalentPoolController::class, 'index'])->name('admin.calon-karyawan.talent-pool.index');
                Route::get('/{id}/show', [\App\Http\Controllers\CalonKaryawan\TalentPool\TalentPoolController::class, 'show'])->name('admin.calon-karyawan.talent-pool.show');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\TalentPool\TalentPoolController::class, 'destroy'])->name('admin.calon-karyawan.talent-pool.destroy');
            });

            Route::prefix('/offering')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\Offering\OfferingController::class, 'index'])->name('admin.calon-karyawan.offering.index');
                Route::get('/{id}/show', [\App\Http\Controllers\CalonKaryawan\Offering\OfferingController::class, 'show'])->name('admin.calon-karyawan.offering.show');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\Offering\OfferingController::class, 'destroy'])->name('admin.calon-karyawan.offering.destroy');
            });

            Route::prefix('/rejected')->group(function () {
                Route::get('/', [\App\Http\Controllers\CalonKaryawan\Rejected\RejectedController::class, 'index'])->name('admin.calon-karyawan.rejected.index');
                Route::get('/{id}/show', [\App\Http\Controllers\CalonKaryawan\Rejected\RejectedController::class, 'show'])->name('admin.calon-karyawan.rejected.show');
                Route::delete('/{id}/delete', [\App\Http\Controllers\CalonKaryawan\Rejected\RejectedController::class, 'destroy'])->name('admin.calon-karyawan.rejected.destroy');
            });

        });

        Route::prefix('master-data')->middleware(['role:hr'])->group(function () {
            Route::prefix('jabatan')->group(function () {
                Route::get('/', [JabatanController::class, 'index'])->name('admin.master-data.jabatan.index');
                Route::get('/create', [JabatanController::class, 'create'])->name('admin.master-data.jabatan.create');
                Route::post('/store', [JabatanController::class, 'store'])->name('admin.master-data.jabatan.store');
                Route::get('/{id}/edit', [JabatanController::class, 'edit'])->name('admin.master-data.jabatan.edit');
                Route::put('/{id}/update', [JabatanController::class, 'update'])->name('admin.master-data.jabatan.update');
                Route::delete('/{id}/delete', [JabatanController::class, 'destroy'])->name('admin.master-data.jabatan.destroy');
            });

            Route::prefix('saldo-cuti')->group(function () {
                Route::get('/', [\App\Http\Controllers\MasterData\SaldoCuti\SaldoCutiController::class, 'index'])->name('admin.master-data.saldo-cuti.index');
                Route::get('/create', [\App\Http\Controllers\MasterData\SaldoCuti\SaldoCutiController::class, 'create'])->name('admin.master-data.saldo-cuti.create');
                Route::post('/store', [\App\Http\Controllers\MasterData\SaldoCuti\SaldoCutiController::class, 'store'])->name('admin.master-data.saldo-cuti.store');
                Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\SaldoCuti\SaldoCutiController::class, 'edit'])->name('admin.master-data.saldo-cuti.edit');
                Route::put('/{id}/update', [\App\Http\Controllers\MasterData\SaldoCuti\SaldoCutiController::class, 'update'])->name('admin.master-data.saldo-cuti.update');
                Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\SaldoCuti\SaldoCutiController::class, 'destroy'])->name('admin.master-data.saldo-cuti.destroy');
            });

            Route::prefix('lokasi-kerja')->group(function () {
                Route::get('/', [\App\Http\Controllers\MasterData\LokasiKerja\LokasiKerjaController::class, 'index'])->name('admin.master-data.lokasi-kerja.index');
                Route::get('/create', [\App\Http\Controllers\MasterData\LokasiKerja\LokasiKerjaController::class, 'create'])->name('admin.master-data.lokasi-kerja.create');
                Route::post('/store', [\App\Http\Controllers\MasterData\LokasiKerja\LokasiKerjaController::class, 'store'])->name('admin.master-data.lokasi-kerja.store');
                Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\LokasiKerja\LokasiKerjaController::class, 'edit'])->name('admin.master-data.lokasi-kerja.edit');
                Route::put('/{id}/update', [\App\Http\Controllers\MasterData\LokasiKerja\LokasiKerjaController::class, 'update'])->name('admin.master-data.lokasi-kerja.update');
                Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\LokasiKerja\LokasiKerjaController::class, 'destroy'])->name('admin.master-data.lokasi-kerja.destroy');
            });

            Route::prefix('/company')->group(function () {
                Route::get('/', [\App\Http\Controllers\MasterData\Company\CompanyController::class, 'index'])->name('admin.master-data.company.index');
                Route::get('/create', [\App\Http\Controllers\MasterData\Company\CompanyController::class, 'create'])->name('admin.master-data.company.create');
                Route::post('/store', [\App\Http\Controllers\MasterData\Company\CompanyController::class, 'store'])->name('admin.master-data.company.store');
                Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\Company\CompanyController::class, 'edit'])->name('admin.master-data.company.edit');
                Route::put('/{id}/update', [\App\Http\Controllers\MasterData\Company\CompanyController::class, 'update'])->name('admin.master-data.company.update');
                Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\Company\CompanyController::class, 'destroy'])->name('admin.master-data.company.destroy');
            });

            Route::prefix('/department')->group(function () {
                Route::get('/', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'index'])->name('admin.master-data.department.index');
                Route::get('/company-options', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'companyOptions'])->name('admin.master-data.department.company-options');
                Route::get('/create', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'create'])->name('admin.master-data.department.create');
                Route::post('/store', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'store'])->name('admin.master-data.department.store');
                Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'edit'])->name('admin.master-data.department.edit');
                Route::put('/{id}/update', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'update'])->name('admin.master-data.department.update');
                Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\Department\DepartmentController::class, 'destroy'])->name('admin.master-data.department.destroy');
            });

            Route::prefix('/hari-libur')->group(function () {
                Route::get('/', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'index'])->name('admin.master-data.hari-libur.index');
                Route::get('/company-options', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'companyOptions'])->name('admin.master-data.hari-libur.company-options');
                Route::get('/create', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'create'])->name('admin.master-data.hari-libur.create');
                Route::post('/store', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'store'])->name('admin.master-data.hari-libur.store');
                Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'edit'])->name('admin.master-data.hari-libur.edit');
                Route::put('/{id}/update', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'update'])->name('admin.master-data.hari-libur.update');
                Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\HariLibur\HariLiburController::class, 'destroy'])->name('admin.master-data.hari-libur.destroy');
            });

            Route::prefix('/grup-jam-kerja')->group(function () {
                Route::get('/', [\App\Http\Controllers\MasterData\GrupJamKerja\GrupJamKerjaController::class, 'index'])->name('admin.master-data.grup-jam-kerja.index');
                Route::get('/create', [\App\Http\Controllers\MasterData\GrupJamKerja\GrupJamKerjaController::class, 'create'])->name('admin.master-data.grup-jam-kerja.create');
                Route::post('/store', [\App\Http\Controllers\MasterData\GrupJamKerja\GrupJamKerjaController::class, 'store'])->name('admin.master-data.grup-jam-kerja.store');
                Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\GrupJamKerja\GrupJamKerjaController::class, 'edit'])->name('admin.master-data.grup-jam-kerja.edit');
                Route::put('/{id}/update', [\App\Http\Controllers\MasterData\GrupJamKerja\GrupJamKerjaController::class, 'update'])->name('admin.master-data.grup-jam-kerja.update');
                Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\GrupJamKerja\GrupJamKerjaController::class, 'destroy'])->name('admin.master-data.grup-jam-kerja.destroy');
                Route::prefix('/setting')->group(function () {
                    Route::get('/', [\App\Http\Controllers\MasterData\Setting\SettingController::class, 'index'])->name('admin.master-data.setting.index');
                    Route::get('/create', [\App\Http\Controllers\MasterData\Setting\SettingController::class, 'create'])->name('admin.master-data.setting.create');
                    Route::post('/create', [\App\Http\Controllers\MasterData\Setting\SettingController::class, 'store'])->name('admin.master-data.setting.store');
                    Route::get('/{id}/edit', [\App\Http\Controllers\MasterData\Setting\SettingController::class, 'edit'])->name('admin.master-data.setting.edit');
                    Route::put('/{id}/update', [\App\Http\Controllers\MasterData\Setting\SettingController::class, 'update'])->name('admin.master-data.setting.update');
                    Route::delete('/{id}/delete', [\App\Http\Controllers\MasterData\Setting\SettingController::class, 'destroy'])->name('admin.master-data.setting.destroy');
                });
            });
        });
    });
});
