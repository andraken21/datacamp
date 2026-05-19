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

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->topic && $request->topic !== 'all') {
            $query->where(function($q) use ($request) {
                $q->where('category', 'like', '%'.$request->topic.'%')
                  ->orWhere('title', 'like', '%'.$request->topic.'%');
            });
        }

        if ($request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        $sort = $request->sort ?? 'popular';
        if ($sort === 'popular') $query->orderByDesc('students_count');
        elseif ($sort === 'rating') $query->orderByDesc('rating');
        elseif ($sort === 'newest') $query->orderByDesc('created_at');

        $courses = $query->paginate(12);
        $categories = Course::distinct()->pluck('category');

        return view('courses', compact('courses', 'categories'));
    }

    public function show($slug) {
        $course = Course::with('lessons')->where('slug', $slug)->firstOrFail();

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