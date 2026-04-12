<?php

use App\Http\Controllers\Admin\CutiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Redirect halaman depan ke login jika belum masuk, atau ke dashboard jika sudah
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});


Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('pages.admin.dashboard');
        })->name('dashboard');

        Route::prefix('/pegawais')->name('pegawais.')->group(function () {
            Route::get('', function () {
                return view('pages.admin.pegawais.index');
            });

            Route::get('/{id}/details', function ($id) {
                return view('pages.admin.pegawais.details', compact('id'));
            });
            Route::get('/{id}/absensis', function ($id) {
                return view('pages.admin.pegawais.absensis', compact('id'));
            });
        });




        Route::get('/pimpinan', function () {
            return view('pages.admin.pegawais.index');
        })->name('pegawai');
    });



require __DIR__ . '/settings.php';
