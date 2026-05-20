<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\CourseController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CertificationController;

// Homepage
Route::get('/', function () {
    if (Auth::check()) {
        return app(HomeController::class)->index();
    }
    return view('welcome');
});

Route::get('/ai-native', function () {
    return view('ai-native');
})->name('ai-native');

// Katalog tools
Route::get('/katalog', [ToolController::class, 'index'])->name('katalog');
Route::get('/katalog/{slug}', [ToolController::class, 'show'])->name('tool.detail');

// Kursus (public)
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('course.detail');
Route::get('/courses/{slug}/learn', [CourseController::class, 'learn'])->middleware('auth')->name('course.learn');

// Tracks (public)
Route::get('/tracks/career', [TrackController::class, 'career'])->name('tracks.career');
Route::get('/tracks/skill', [TrackController::class, 'skill'])->name('tracks.skill');
Route::get('/tracks/{slug}', [TrackController::class, 'show'])->name('tracks.show');

// Halaman statis
Route::get('/harga', function() { return view('harga'); })->name('harga');
Route::get('/resources', function() { return view('resources'); })->name('resources');

// ============================================
// Certification Routes
// ============================================
Route::prefix('certification')->name('certification.')->group(function () {
    Route::get('/',                     [CertificationController::class, 'index'])          ->name('index');
    Route::get('/career/data-analyst',  [CertificationController::class, 'careerAnalyst'])  ->name('career.analyst');
    Route::get('/career/data-scientist',[CertificationController::class, 'careerScientist'])->name('career.scientist');
    Route::get('/career/data-engineer', [CertificationController::class, 'careerEngineer'])->name('career.engineer');
    Route::get('/tech/power-bi',        [CertificationController::class, 'techPowerBI'])    ->name('tech.powerbi');
    Route::get('/tech/tableau',         [CertificationController::class, 'techTableau'])    ->name('tech.tableau');
    Route::get('/tech/sql',             [CertificationController::class, 'techSQL'])        ->name('tech.sql');
    Route::get('/cpe',                  [CertificationController::class, 'cpe'])            ->name('cpe');
    Route::get('/theory',               [CertificationController::class, 'theory'])         ->name('theory');
    Route::get('/history',              [CertificationController::class, 'history'])        ->name('history');
});

Route::get('/learn', function () {
    if (Auth::check()) {
        return view('dashboard');
    }
    return redirect()->route('courses');
})->middleware('auth')->name('learn');

// Tutorial routes
Route::prefix('tutorials')->name('tutorials.')->group(function () {
    Route::get('/', [TutorialController::class, 'index'])->name('index');
    Route::get('/status', [TutorialController::class, 'status'])->name('status');
    Route::post('/scrape', [TutorialController::class, 'scrape'])->name('scrape');
    Route::get('/{slug}', [TutorialController::class, 'show'])->name('show');
});

// Auth routes (requires login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/leaderboard', function () { return view('leaderboard'); })->name('leaderboard');
    Route::get('/practice', function () { return view('practice'); })->name('practice');
    Route::get('/tracks', function () { return view('tracks'); })->name('tracks');
    Route::get('/my-activity', function () { return view('my-activity'); })->name('my-activity');
    Route::get('/assessments', function () { return view('assessments'); })->name('assessments');
    Route::get('/real-world-projects', function () { return view('real-world-projects'); })->name('real-world-projects');
    Route::get('/code-alongs', function () { return view('code-alongs'); })->name('code-alongs');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/katalog/{id}/save', [ToolController::class, 'save'])->name('tool.save');

    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('course.enroll');
    Route::post('/lessons/{id}/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');

    Route::get('/scraper', [ScraperController::class, 'index'])->name('scraper');
    Route::post('/scraper/run', [ScraperController::class, 'run'])->name('scraper.run');

    Route::post('/katalog/{slug}/comment', [CommentController::class, 'storeTool'])->name('comment.tool');
    Route::post('/courses/{slug}/comment', [CommentController::class, 'storeCourse'])->name('comment.course');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

Route::view('/faq', 'faq')->name('faq');
Route::view('/feedback', 'feedback')->name('feedback');

require __DIR__.'/auth.php';