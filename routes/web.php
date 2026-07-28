<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/cuti/{id}/pdf', [\App\Http\Controllers\Admin\CutiController::class, 'generatePdf'])
    ->middleware(['auth'])
    ->name('cuti.pdf');

// Admin routes
Route::middleware(['auth', 'check.role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.admin.dashboard');
        })->name('dashboard');

        // Absensis
        Route::prefix('/absensis')->name('absensis.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.absensis.index');
            })->name('index');
            Route::get('/create', function () {
                return view('pages.admin.absensis.create');
            })->name('create');
            Route::get('/import', function () {
                return view('pages.admin.absensis.import');
            })->name('import');
            Route::get('/{id}/details', function ($id) {
                return view('pages.admin.absensis.details', compact('id'));
            })->name('details');
            Route::get('/{id}/edit', function ($id) {
                return view('pages.admin.absensis.edit', compact('id'));
            })->name('edit');
        });

        // Cutis
        Route::prefix('/cutis')->name('cutis.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.cutis.index');
            })->name('index');
            Route::get('/create', function () {
                return view('pages.admin.cutis.create');
            })->name('create');
            Route::get('/{id}/details', function ($id) {
                return view('pages.admin.cutis.details', compact('id'));
            })->name('details');
            Route::get('/{id}/edit', function ($id) {
                return view('pages.admin.cutis.edit', compact('id'));
            })->name('edit');
        });

        // Hari Liburs
        Route::prefix('/hari-liburs')->name('hari-liburs.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.hari-liburs.index');
            })->name('index');
        });

        // KGBS
        Route::prefix('/kgbs')->name('kgbs.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.kgbs.index');
            })->name('index');
            Route::get('/import', function () {
                return view('pages.admin.kgbs.import');
            })->name('import');
            Route::get('/create-pns', function () {
                return view('pages.admin.kgbs.create-pns');
            })->name('create-pns');
            Route::get('/create-pppk', function () {
                return view('pages.admin.kgbs.create-pppk');
            })->name('create-pppk');
            Route::get('/pns-pdf', [\App\Http\Controllers\Admin\KgbController::class, 'generatePnsPdf'])->name('pns-pdf');
            Route::get('/pppk-pdf', [\App\Http\Controllers\Admin\KgbController::class, 'generatePppkPdf'])->name('pppk-pdf');
        });

        // Pegawais
        Route::prefix('/pegawais')->name('pegawais.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.pegawais.index');
            })->name('index');
            Route::get('/import', function () {
                return view('pages.admin.pegawais.import');
            })->name('import');
            Route::get('/import-id-absensi', function () {
                return view('pages.admin.pegawais.import-id-absensi');
            })->name('import-id-absensi');
            Route::get('/{id}/details', function ($id) {
                return view('pages.admin.pegawais.details', compact('id'));
            })->name('details');
            Route::get('/{id}/absensis', function ($id) {
                return view('pages.admin.pegawais.absensis', compact('id'));
            })->name('absensis');
        });

        // Pimpinans
        Route::prefix('/pimpinans')->name('pimpinans.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.pimpinans.index');
            })->name('index');
            Route::get('/create', function () {
                return view('pages.admin.pimpinans.create');
            })->name('create');
            Route::get('/{id}/details', function ($id) {
                return view('pages.admin.pimpinans.details', compact('id'));
            })->name('details');
            Route::get('/{id}/edit', function ($id) {
                return view('pages.admin.pimpinans.edit', compact('id'));
            })->name('edit');
        });

        // Users
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', function () {
                return view('pages.admin.users.index');
            })->name('index');
            Route::get('/create', function () {
                return view('pages.admin.users.create');
            })->name('create');
            Route::get('/{user}/edit', function ($user) {
                return view('pages.admin.users.edit', compact('user'));
            })->name('edit');
        });
    });

// Operator routes
Route::middleware(['auth', 'check.role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.operator.dashboard');
        })->name('dashboard');

        // Pegawais
        Route::prefix('/pegawais')->name('pegawais.')->group(function () {
            Route::get('/', function () {
                return view('pages.operator.pegawais.index');
            })->name('index');
            Route::get('/{id}/details', function ($id) {
                return view('pages.operator.pegawais.details', compact('id'));
            })->name('details');
        });

        // Pimpinans
        Route::prefix('/pimpinans')->name('pimpinans.')->group(function () {
            Route::get('/', function () {
                return view('pages.operator.pimpinans.index');
            })->name('index');
            Route::get('/{id}/details', function ($id) {
                return view('pages.operator.pimpinans.details', compact('id'));
            })->name('details');
            Route::get('/{id}/edit', function ($id) {
                return view('pages.operator.pimpinans.edit', compact('id'));
            })->name('edit');
        });
    });

// Pegawai routes
Route::middleware(['auth'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.pegawai.dashboard');
        })->name('dashboard');
    });

require __DIR__ . '/settings.php';


if (app()->environment('local')) {
    Route::get('/tes-k6-langsung', function () {
        // Tulis log langsung di sini untuk pembuktian
        Log::debug("Rute khusus k6 berhasil dipanggil!");

        // Paksa login user ID 4
        Auth::loginUsingId(4);

        // Panggil controller dashboard Anda secara manual
        // Ganti 'DashboardController' sesuai nama asli controller Anda
        return;
    });
}
