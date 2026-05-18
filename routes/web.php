<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TutorialController;

// Homepage
Route::get('/', function () {
    if (Auth::check()) {
        return view('home-logged');
    }
    return view('welcome');
});

// Katalog tools
Route::get('/katalog', [ToolController::class, 'index'])->name('katalog');
Route::get('/katalog/{slug}', [ToolController::class, 'show'])->name('tool.detail');

// Kursus
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('course.detail');

Route::get('/learn', function () {
    if (Auth::check()) {
        return view('dashboard');
    }
    return redirect()->route('courses');
})->middleware('auth')->name('learn');

// Halaman statis
Route::get('/harga', function() { return view('harga'); })->name('harga');
Route::get('/resources', function() { return view('resources'); })->name('resources');

// Tutorial routes (punya kawanmu)
Route::prefix('tutorials')->name('tutorials.')->group(function () {
    Route::get('/', [TutorialController::class, 'index'])->name('index');
    Route::get('/status', [TutorialController::class, 'status'])->name('status');
    Route::post('/scrape', [TutorialController::class, 'scrape'])->name('scrape');
    Route::get('/{slug}', [TutorialController::class, 'show'])->name('show');
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/leaderboard', function () {
    return view('leaderboard');
})->name('leaderboard');

Route::get('/practice', function () {
    return view('practice');
})->name('practice');

Route::get('/tracks', function () {
    return view('tracks');
})->name('tracks');

Route::get('/tracks/career', function () {
    return view('tracks-career');
})->name('tracks.career');

Route::get('/tracks/skill', function () {
    return view('tracks-skill');
})->name('tracks.skill');

    Route::get('/my-activity', function () {
    return view('my-activity');
})->name('my-activity');

Route::get('/assessments', function () {
    return view('assessments');
})->name('assessments');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/katalog/{id}/save', [ToolController::class, 'save'])->name('tool.save');

    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('course.enroll');
    Route::get('/courses/{slug}/learn', [CourseController::class, 'learn'])->name('course.learn');
    Route::post('/lessons/{id}/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');

    Route::get('/scraper', [ScraperController::class, 'index'])->name('scraper');
    Route::post('/scraper/run', [ScraperController::class, 'run'])->name('scraper.run');

    Route::post('/katalog/{slug}/comment', [CommentController::class, 'storeTool'])->name('comment.tool');
    Route::post('/courses/{slug}/comment', [CommentController::class, 'storeCourse'])->name('comment.course');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

require __DIR__.'/auth.php';