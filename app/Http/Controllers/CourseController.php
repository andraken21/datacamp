<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller {

    public function index(Request $request) {
        $query = Course::query();

        // Map nama topik → topik_id
        $topicMap = [
            'python' => 33, 'sql' => 35, 'r' => 34, 'power bi' => 31,
            'tableau' => 41, 'alteryx' => 5, 'excel' => 15,
            'google sheets' => 21, 'chatgpt' => 9, 'gemini' => 17,
            'pytorch' => 32, 'openai' => 30, 'aws' => 3, 'azure' => 7,
            'snowflake' => 38, 'databricks' => 13, 'git' => 18,
            'docker' => 14, 'shell' => 36, 'kubernetes' => 26,
            'airflow' => 4, 'spark' => 39, 'dbt' => 43, 'bigquery' => 8,
            'redshift' => 40, 'scala' => 6, 'julia' => 23, 'mlflow' => 28,
            'theory' => 42, 'google cloud' => 20, 'claude' => 10,
            'n8n' => 44, 'sigma' => 37, 'microsoft copilot' => 29,
            'cursor' => 11, 'github' => 19, 'java' => 22,
            'fastapi' => 16, 'llama' => 27, 'knime' => 24, 'kafka' => 25,
            'dvc' => 12, 'ai & llm' => 1, 'ai copilot' => 2,
        ];

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('nama_course', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%')
                ->orWhere('deskripsi', 'like', '%'.$request->search.'%');
            });
        }

        // Filter by topic → topik_id
        if ($request->topic && $request->topic !== 'all') {
            $topicKey = strtolower($request->topic);
            if (isset($topicMap[$topicKey])) {
                $query->where('topik_id', $topicMap[$topicKey]);
            }
        }

        // Filter difficulty
        if ($request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        // Sort
        $sort = $request->sort ?? 'popular';
        if ($sort === 'popular') $query->orderByDesc('students_count');
        elseif ($sort === 'rating') $query->orderByDesc('rating');
        elseif ($sort === 'newest') $query->orderByDesc('created_at');

        $courses = $query->with('level')->paginate(12);        $categories = Course::distinct()->pluck('category');

        return view('courses', compact('courses', 'categories'));
    }

    public function show($slug) {
        $course = Course::with(['lessons', 'instruktur', 'level'])->where('slug', $slug)->firstOrFail();
        $enrollment = null;
        $completedLessons = collect();
        $isEnrolled = false;

        if (Auth::check()) {
            // FIX: pakai $course->id konsisten (jangan campuran course_id dan id)
            $enrollment = Enrollment::where('user_id', Auth::id())
                         ->where('course_id', $course->id)
                         ->first();

            $isEnrolled = $enrollment ? true : false;

            if ($enrollment) {
                $completedLessons = LessonProgress::where('user_id', Auth::id())
                                   ->whereIn('lesson_id', $course->lessons->pluck('id'))
                                   ->where('is_completed', true)
                                   ->pluck('lesson_id');
            }
        }

        return view('course-detail', compact('course', 'enrollment', 'completedLessons', 'isEnrolled'));
    }

    public function enroll($id) {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $course = Course::findOrFail($id);
    
    if (!$course) {
        return back()->with('error', 'Kursus tidak ditemukan.');
    }

    $existing = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)->first();

    if (!$existing) {
        Enrollment::create([
            'user_id'   => Auth::id(),
            'course_id' => $course->id,
            'progress'  => 0,
        ]);
        $course->increment('students_count');
    }

    return redirect()->route('course.learn', $course->slug);
}

    public function learn($slug) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // FIX: hapus duplikat query course, gabungkan dengan instruktur sekaligus
        $course = Course::with(['lessons', 'instruktur'])->where('slug', $slug)->firstOrFail();

        // FIX: pakai $course->id konsisten
        $enrollment = Enrollment::where('user_id', Auth::id())
                     ->where('course_id', $course->id)
                     ->firstOrFail();

        $completedLessons = LessonProgress::where('user_id', Auth::id())
                           ->whereIn('lesson_id', $course->lessons->pluck('id'))
                           ->where('is_completed', true)
                           ->pluck('lesson_id');

        $firstLesson = $course->lessons->first();

        return view('course-learn', compact('course', 'enrollment', 'completedLessons', 'firstLesson'));
    }

    public function completeLesson(Request $request, $lessonId) {
        $progress = LessonProgress::firstOrCreate(
            ['user_id' => Auth::id(), 'lesson_id' => $lessonId],
            ['is_completed' => false]
        );

        // Kalau sudah completed, jangan tambah XP lagi
        if ($progress->is_completed) {
            return back()->with('message', 'Pelajaran ini sudah selesai sebelumnya.');
        }

        $progress->update(['is_completed' => true, 'completed_at' => now()]);

        $lesson = \App\Models\Lesson::findOrFail($lessonId);
        $course = $lesson->course;
        $totalLessons = $course->lessons()->count(); // FIX: hitung langsung dari relasi

        $completedCount = LessonProgress::where('user_id', Auth::id())
                         ->whereIn('lesson_id', $course->lessons->pluck('id'))
                         ->where('is_completed', true)
                         ->count();

        $progressPercent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        // FIX: pakai $course->id konsisten
        Enrollment::where('user_id', Auth::id())
                  ->where('course_id', $course->id)
                  ->update(['progress' => $progressPercent]);

        // Tambah XP ke user
        Auth::user()->increment('xp', 10);

        // Update streak
        $user = Auth::user()->fresh(); // FIX: fresh() supaya data terbaru
        $today = now()->toDateString();

        if (!$user->last_activity || $user->last_activity->format('Y-m-d') != $today) {
            $yesterday = now()->subDay()->toDateString();
            if ($user->last_activity && $user->last_activity->format('Y-m-d') == $yesterday) {
                $user->increment('streak');
            } else {
                $user->streak = 1;
            }
            $user->last_activity = now();
            $user->save();
        }

        return back()->with('message', 'Pelajaran selesai! +10 XP');
    }
}