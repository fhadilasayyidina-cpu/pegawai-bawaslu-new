<?php

use App\Http\Controllers\Admin\CutiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/admin/cutis/{id}/pdf', [CutiController::class, 'generatePdf'])
    ->name('cuti.pdf')
    ->middleware(['auth', 'verified']);

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
