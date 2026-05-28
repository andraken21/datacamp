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
    Route::get('/real-world-projects', function () { return view('real-world-projects'); })->middleware('auth')->name('real-world-projects');

Route::get('/real-world-projects/{slug}', function ($slug) {
    $projects = [
        'cleaning-data-generative-ai' => [
            'title' => 'Cleaning Data with Generative AI',
            'level' => 'Basic', 'level_color' => '#03EF62',
            'updated' => 'December 2025',
            'duration' => '1 hr', 'exercises' => 5, 'participants' => '12,227', 'xp' => 250,
            'desc' => 'In this prompt engineering project, you will harness the power of generative AI to tackle one of the most critical tasks in data analytics: data cleaning! Through this hands-on project, you\'ll design AI-driven prompts to identify and address common data issues like duplicates, null values, missing values, inconsistent formatting, and more. Build intelligent workflows to streamline messy datasets and ensure accuracy, consistency, and usability.',
            'chapters' => [
                ['title' => 'Cleaning Data', 'locked' => true, 'exercises' => [
                    'Identifying data cleaning steps', 'Removing duplicate values',
                    'Filtering out null values', 'Standardizing data formats', 'Splitting columns'
                ], 'xp_each' => 50],
            ],
            'instructors' => [
                ['name' => 'Jess Ahmet', 'role' => 'Senior Data Analyst', 'bio' => 'Jess is an ex-Curriculum Manager at DataCamp, who has extensive experience in data analytics and education.'],
                ['name' => 'Alex Kuntz', 'role' => 'Head of Cloud Curriculum', 'bio' => 'Alex is the Head of Cloud Curriculum at DataCamp with expertise in cloud technologies.'],
            ],
            'prerequisites' => [],
            'url' => 'https://app.datacamp.com/learn/projects/2416',
        ],
        'data-storytelling-college-majors' => [
            'title' => 'Data Storytelling Case Study: College Majors',
            'level' => 'Basic', 'level_color' => '#03EF62',
            'updated' => 'November 2025',
            'duration' => '2 hr', 'exercises' => 8, 'participants' => '9,481', 'xp' => 400,
            'desc' => 'Data storytelling is a high-demand skill that combines technical analysis with compelling narrative. In this project, you\'ll explore a dataset of college majors and their outcomes, then craft a data story that communicates insights clearly and persuasively to different audiences.',
            'chapters' => [
                ['title' => 'Exploring the Data', 'locked' => true, 'exercises' => [
                    'Loading and inspecting the dataset', 'Identifying key variables', 'Summary statistics'
                ], 'xp_each' => 50],
                ['title' => 'Building the Narrative', 'locked' => true, 'exercises' => [
                    'Choosing the right visualizations', 'Crafting insights', 'Telling the story'
                ], 'xp_each' => 50],
            ],
            'instructors' => [
                ['name' => 'Sara Billen', 'role' => 'Data Storytelling Expert', 'bio' => 'Sara specializes in transforming complex data into compelling visual narratives for business audiences.'],
            ],
            'prerequisites' => ['Basic Python or R knowledge'],
            'url' => 'https://app.datacamp.com/learn/projects/college-majors',
        ],
        'data-storytelling-green-businesses' => [
            'title' => 'Data Storytelling Case Study: Green Businesses',
            'level' => 'Basic', 'level_color' => '#03EF62',
            'updated' => 'October 2025',
            'duration' => '2 hr', 'exercises' => 7, 'participants' => '7,320', 'xp' => 350,
            'desc' => 'Practice data storytelling using real-world scenarios about sustainable businesses. You\'ll analyze environmental and business metrics to uncover how green practices impact profitability, then present your findings as a compelling data story.',
            'chapters' => [
                ['title' => 'Sustainability Metrics', 'locked' => true, 'exercises' => [
                    'Understanding ESG data', 'Cleaning sustainability datasets', 'Computing key metrics'
                ], 'xp_each' => 50],
                ['title' => 'Visualizing Impact', 'locked' => true, 'exercises' => [
                    'Trend analysis', 'Comparative visualization', 'Final story presentation'
                ], 'xp_each' => 50],
            ],
            'instructors' => [
                ['name' => 'Maria Lopez', 'role' => 'Sustainability Data Analyst', 'bio' => 'Maria brings 8 years of experience in environmental data analysis and sustainable business consulting.'],
            ],
            'prerequisites' => [],
            'url' => 'https://app.datacamp.com/learn/projects/green-businesses',
        ],
        'analyzing-students-mental-health' => [
            'title' => 'Analyzing Students Mental Health',
            'level' => 'Basic', 'level_color' => '#f59e0b',
            'updated' => 'September 2025',
            'duration' => '1 hr', 'exercises' => 5, 'participants' => '18,540', 'xp' => 250,
            'desc' => 'Explore and analyze student mental health data to uncover trends and patterns. Using SQL, you\'ll investigate a dataset from a Japanese university to understand how social connectedness, acculturative stress, and length of stay affect mental health scores of international students.',
            'chapters' => [
                ['title' => 'Mental Health Analysis', 'locked' => true, 'exercises' => [
                    'Exploring the dataset', 'Filtering student groups', 'Calculating average scores',
                    'Identifying correlations', 'Drawing conclusions'
                ], 'xp_each' => 50],
            ],
            'instructors' => [
                ['name' => 'Diandra Gordan', 'role' => 'Data Scientist', 'bio' => 'Diandra is a data scientist with a focus on healthcare analytics and mental health research methodologies.'],
            ],
            'prerequisites' => ['Introduction to SQL'],
            'url' => 'https://app.datacamp.com/learn/projects/1569',
        ],
        'predicting-credit-card-approvals' => [
            'title' => 'Predicting Credit Card Approvals',
            'level' => 'Intermediate', 'level_color' => '#3b82f6',
            'updated' => 'August 2025',
            'duration' => '2 hr', 'exercises' => 8, 'participants' => '24,105', 'xp' => 400,
            'desc' => 'Build a machine learning model to predict whether a credit card application will be approved. Commercial banks receive a lot of applications for credit cards. Many of them get rejected for many reasons, like high loan balances, low income levels, or too many inquiries on an individual\'s credit report. In this project, you will build an automatic credit card approval predictor using machine learning techniques.',
            'chapters' => [
                ['title' => 'Data Preparation', 'locked' => true, 'exercises' => [
                    'Loading the dataset', 'Inspecting the data', 'Handling missing values', 'Preprocessing features'
                ], 'xp_each' => 50],
                ['title' => 'Model Building', 'locked' => true, 'exercises' => [
                    'Splitting the data', 'Training logistic regression', 'Evaluating the model', 'Hyperparameter tuning'
                ], 'xp_each' => 50],
            ],
            'instructors' => [
                ['name' => 'Carolina Bento', 'role' => 'Machine Learning Engineer', 'bio' => 'Carolina is an ML engineer who specializes in building production-grade machine learning systems for financial applications.'],
            ],
            'prerequisites' => ['Supervised Learning with scikit-learn'],
            'url' => 'https://app.datacamp.com/learn/projects/558',
        ],
        'hypothesis-testing-healthcare' => [
            'title' => 'Hypothesis Testing in Healthcare',
            'level' => 'Intermediate', 'level_color' => '#3b82f6',
            'updated' => 'July 2025',
            'duration' => '2 hr', 'exercises' => 8, 'participants' => '11,980', 'xp' => 400,
            'desc' => 'Apply hypothesis testing techniques to real-world healthcare data. In this project, you\'ll investigate whether a pharmaceutical company\'s drug leads to adverse reactions, performing statistical tests to determine significance and drawing conclusions that could impact patient safety and business decisions.',
            'chapters' => [
                ['title' => 'Setting Up Hypotheses', 'locked' => true, 'exercises' => [
                    'Understanding the dataset', 'Defining null and alternative hypotheses', 'Choosing the right test'
                ], 'xp_each' => 50],
                ['title' => 'Running the Tests', 'locked' => true, 'exercises' => [
                    'Performing z-test', 'Chi-square test', 'Interpreting p-values', 'Drawing conclusions'
                ], 'xp_each' => 50],
            ],
            'instructors' => [
                ['name' => 'James Chapman', 'role' => 'Biostatistician', 'bio' => 'James has 10+ years of experience applying statistical methods in pharmaceutical and clinical research.'],
            ],
            'prerequisites' => ['Introduction to Statistics', 'Introduction to Python'],
            'url' => 'https://app.datacamp.com/learn/projects/1584',
        ],
    ];

    if (!isset($projects[$slug])) abort(404);
    $project = $projects[$slug];
    $project['slug'] = $slug;
    return view('real-world-project-detail', compact('project'));
})->middleware('auth')->name('real-world-project.show');

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