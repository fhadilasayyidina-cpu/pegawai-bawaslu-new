<?php

use App\Http\Controllers\Admin\CutiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/admin/cutis/{id}/pdf', [CutiController::class, 'generatePdf'])
    ->name('cuti.pdf')
    ->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
