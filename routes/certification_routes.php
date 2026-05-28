<?php

use App\Http\Controllers\CertificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Certification Routes
|--------------------------------------------------------------------------
*/

// Halaman daftar semua sertifikasi
Route::get('/certification', [CertificationController::class, 'index'])
    ->name('certification');

// Halaman detail sertifikasi berdasarkan kategori dan slug
// Contoh: /certification/technology/tableau-certified-data-analyst
Route::get('/certification/technology/{slug}', [CertificationController::class, 'show'])
    ->name('certification.show');

Route::get('/certification/career/{slug}', [CertificationController::class, 'show'])
    ->name('certification.career.show');