<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog', [ToolController::class, 'index'])->name('katalog');
Route::get('/katalog/{slug}', [ToolController::class, 'show'])->name('tool.detail');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';