<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\CommentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog', [ToolController::class, 'index'])->name('katalog');
Route::get('/katalog/{slug}', [ToolController::class, 'show'])->name('tool.detail');
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('course.detail');
Route::get('/scraper', [ScraperController::class, 'index'])->name('scraper');
Route::post('/scraper/run', [ScraperController::class, 'run'])->name('scraper.run');
    
Route::get('/harga', function() { 
    return view('harga'); })->name('harga');
Route::get('/resources', function() { 
    return view('resources'); })->name('resources');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('course.enroll');
    Route::get('/courses/{slug}/learn', [CourseController::class, 'learn'])->name('course.learn');
    Route::post('/lessons/{id}/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/katalog/{id}/save', [ToolController::class, 'save'])->name('tool.save');

    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
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