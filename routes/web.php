<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ActivityController;

// ============================================
// STUDY GUIDE DOWNLOAD (taruh paling atas!)
// ============================================
Route::get('/study-guides/{filename}', function ($filename) {
    if (!preg_match('/^[\w\+\-]+\.pdf$/i', $filename)) abort(404);
    
    $path = base_path('study-guides-pdf/' . $filename);
    
    if (!file_exists($path)) abort(404);
    
    return response()->download($path, $filename, [
        'Content-Type' => 'application/pdf',
    ]);
})->where('filename', '[^/]+');

// ============================================
// PUBLIC ROUTES
// ============================================

Route::get('/', function () {
    if (Auth::check()) {
        return app(HomeController::class)->index();
    }
    return view('welcome');
});

Route::get('/ai-native', function () {
    return view('ai-native');
})->name('ai-native');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/feedback', function () {
    return view('feedback');
})->name('feedback');

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
Route::get('/harga', function () { return view('harga'); })->name('harga');
Route::get('/resources', function () { return view('resources'); })->name('resources');

// ============================================
// CERTIFICATION ROUTES (public)
// ============================================
Route::prefix('certification')->name('certification.')->group(function () {
    Route::get('/',                              [CertificationController::class, 'index'])           ->name('index');

    // Career
    Route::get('/career/data-analyst',           [CertificationController::class, 'careerAnalyst'])   ->name('career.analyst');
    Route::get('/career/data-scientist',         [CertificationController::class, 'careerScientist']) ->name('career.scientist');
    Route::get('/career/data-engineer',          [CertificationController::class, 'careerEngineer'])  ->name('career.engineer');
    Route::get('/career/ai-engineer-developers', [CertificationController::class, 'careerAIEngineerDev']) ->name('career.ai-dev');
    Route::get('/career/ai-engineer-ds',         [CertificationController::class, 'careerAIEngineerDS'])  ->name('career.ai-ds');

    // Technology
    Route::get('/technology/power-bi',           [CertificationController::class, 'techPowerBI'])     ->name('tech.powerbi');
    Route::get('/technology/tableau',            [CertificationController::class, 'techTableau'])     ->name('tech.tableau');
    Route::get('/technology/sql',                [CertificationController::class, 'techSQL'])         ->name('tech.sql');
    Route::get('/technology/python',             [CertificationController::class, 'techPython'])      ->name('tech.python');
    Route::get('/technology/azure',              [CertificationController::class, 'techAzure'])       ->name('tech.azure');
    Route::get('/technology/azure-developer',    [CertificationController::class, 'techAzureDev'])    ->name('tech.azure-dev');
    Route::get('/technology/github',             [CertificationController::class, 'techGithub'])      ->name('tech.github');
    Route::get('/technology/aws',                [CertificationController::class, 'techAWS'])         ->name('tech.aws');
    Route::get('/technology/alteryx',            [CertificationController::class, 'techAlteryx'])     ->name('tech.alteryx');
    Route::get('/technology/knime',              [CertificationController::class, 'techKNIME'])       ->name('tech.knime');

    // Others
    Route::get('/cpe',                           [CertificationController::class, 'cpe'])             ->name('cpe');
    Route::get('/theory',                        [CertificationController::class, 'theory'])          ->name('theory');
    Route::get('/history',                       [CertificationController::class, 'history'])         ->name('history');
    Route::get('/faq',                           [CertificationController::class, 'faq'])             ->name('faq');
    Route::get('/feedback',                      [CertificationController::class, 'feedback'])        ->name('feedback');
});

// ============================================
// TUTORIAL ROUTES (public)
// ============================================
Route::prefix('tutorials')->name('tutorials.')->group(function () {
    Route::get('/',        [TutorialController::class, 'index'])->name('index');
    Route::get('/status',  [TutorialController::class, 'status'])->name('status');
    Route::post('/scrape', [TutorialController::class, 'scrape'])->name('scrape');
    Route::get('/{slug}',  [TutorialController::class, 'show'])->name('show');
});

Route::get('/learn', function () {
    if (Auth::check()) {
        return view('dashboard');
    }
    return redirect()->route('courses');
})->middleware('auth')->name('learn');

// ============================================
// AUTH ROUTES (requires login)
// ============================================
Route::middleware('auth')->group(function () {

    // Dashboard & navigasi utama
    Route::get('/dashboard',           function () { return view('dashboard'); })->name('dashboard');
    Route::get('/leaderboard',         function () { return view('leaderboard'); })->name('leaderboard');
    Route::get('/practice',            function () { return view('practice'); })->name('practice');
    Route::get('/sandbox',             function () { return view('sandbox'); })->name('sandbox');
    Route::get('/tracks',              function () { return view('tracks'); })->name('tracks');
    Route::get('/my-activity', [ActivityController::class, 'index'])->name('my-activity');
    Route::get('/assessments',         function () { return view('assessments'); })->name('assessments');
    Route::get('/real-world-projects', function () { return view('real-world-projects'); })->name('real-world-projects');
    Route::get('/code-alongs',         function () { return view('code-alongs'); })->name('code-alongs');

    //practice
    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::get('/practice/{id}/intro', [PracticeController::class, 'intro'])->name('practice.intro');
    Route::post('/practice/{id}/start', [PracticeController::class, 'start'])->name('practice.start');
    Route::get('/practice/{id}/play', [PracticeController::class, 'play'])->name('practice.play');

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tools
    Route::post('/katalog/{id}/save', [ToolController::class, 'save'])->name('tool.save');

    // Courses
    Route::post('/courses/{id}/enroll',   [CourseController::class, 'enroll'])->name('course.enroll');
    Route::post('/lessons/{id}/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');

    // Scraper
    Route::get('/scraper',      [ScraperController::class, 'index'])->name('scraper');
    Route::post('/scraper/run', [ScraperController::class, 'run'])->name('scraper.run');

    // Comments
    Route::post('/katalog/{slug}/comment', [CommentController::class, 'storeTool'])->name('comment.tool');
    Route::post('/courses/{slug}/comment', [CommentController::class, 'storeCourse'])->name('comment.course');
    Route::delete('/comments/{id}',        [CommentController::class, 'destroy'])->name('comment.destroy');

    // AI Native review
    Route::post('/ai-native/review', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'isi_review' => 'required|string|max:500',
            'rating'     => 'nullable|integer|min:1|max:5',
        ]);
        DB::table('user_reviews')->insert([
            'user_id'    => Auth::id(),
            'halaman'    => 'ai-native',
            'isi_review' => $request->isi_review,
            'rating'     => $request->rating,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('review_success', true);
    })->name('ai-native.review');

    // Give Feedback
    Route::post('/feedback', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'isi_feedback' => 'required|string|max:2000',
            'tipe'         => 'nullable|string|max:100',
            'halaman'      => 'nullable|string|max:200',
        ]);
        DB::table('feedbacks')->insert([
            'user_id'      => Auth::id(),
            'halaman'      => $request->halaman ?? 'certification',
            'isi_feedback' => ($request->tipe ? '[' . $request->tipe . '] ' : '') . $request->isi_feedback,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('feedback_success', true);
    })->name('feedback.submit');

});

require __DIR__.'/auth.php';