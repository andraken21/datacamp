<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

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

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('nama_course', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%')
                ->orWhere('deskripsi', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->topic && $request->topic !== 'all') {
            $topicKey = strtolower($request->topic);
            if (isset($topicMap[$topicKey])) {
                $query->where('topik_id', $topicMap[$topicKey]);
            }
        }

        if ($request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        $sort = $request->sort ?? 'popular';
        if ($sort === 'popular') $query->orderByDesc('students_count');
        elseif ($sort === 'rating') $query->orderByDesc('rating');
        elseif ($sort === 'newest') $query->orderByDesc('created_at');

        $courses = $query->with('level')->paginate(12);
        $categories = Course::distinct()->pluck('category');

        return view('courses', compact('courses', 'categories'));
    }

    public function show($slug)
    {
        $course = Course::with(['lessons', 'instruktur', 'level'])->where('slug', $slug)->firstOrFail();

        $totalLessons   = $course->lessons->count();
        $totalMinutes   = $course->lessons->sum('duration_minutes');
        $totalHours     = $totalMinutes > 0 ? round($totalMinutes / 60, 1) : 0;

        $course->total_lessons  = $totalLessons;
        $course->duration_hours = $totalHours;

        $enrollment       = null;
        $completedLessons = collect();
        $isEnrolled       = false;

        if (Auth::check()) {
            $userId = Auth::user()->user_id;

            $enrollment = Enrollment::where('user_id', $userId)
                         ->where('course_id', $course->course_id)
                         ->first();

            $isEnrolled = $enrollment ? true : false;

            if ($enrollment) {
                $completedLessons = LessonProgress::where('user_id', $userId)
                                   ->whereIn('lesson_id', $course->lessons->pluck('id'))
                                   ->where('is_completed', true)
                                   ->pluck('lesson_id');
            }
        }

        return view('course-detail', compact('course', 'enrollment', 'completedLessons', 'isEnrolled'));
    }

    public function enroll($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $course = Course::findOrFail($id);
        $user   = Auth::user();
        $userId = $user->user_id;

        $existing = Enrollment::where('user_id', $userId)
                    ->where('course_id', $course->course_id)->first();

        if (!$existing) {
            Enrollment::create([
                'user_id'   => $userId,
                'course_id' => $course->course_id,
                'progress'  => 0,
            ]);

            $course->increment('students_count');

            DB::table('users')
                ->where('user_id', $userId)
                ->increment('xp', 50);
        }

        return redirect()->route('course.learn', $course->slug)
               ->with('message', '🎉 Welcome! +50 XP earned!');
    }

    public function learn($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $course = Course::with(['lessons' => function($q) {
            $q->orderBy('order');
        }, 'instruktur'])->where('slug', $slug)->firstOrFail();

        $user   = Auth::user();
        $userId = $user->user_id;

        $enrollment = Enrollment::where('user_id', $userId)
                     ->where('course_id', $course->course_id)
                     ->first();

        if (!$enrollment) {
            $enrollment = Enrollment::create([
                'user_id'   => $userId,
                'course_id' => $course->course_id,
                'progress'  => 0,
            ]);
            $course->increment('students_count');

            DB::table('users')
                ->where('user_id', $userId)
                ->increment('xp', 50);
        }

        $completedLessons = LessonProgress::where('user_id', $userId)
                           ->whereIn('lesson_id', $course->lessons->pluck('id'))
                           ->where('is_completed', true)
                           ->pluck('lesson_id');

        $firstLesson  = $course->lessons->first();
        $totalLessons = $course->lessons->count();
        $totalMinutes = $course->lessons->sum('duration_minutes');
        $totalHours   = $totalMinutes > 0 ? round($totalMinutes / 60, 1) : 0;

        return view('course-learn', compact(
            'course', 'enrollment', 'completedLessons', 'firstLesson',
            'totalLessons', 'totalHours'
        ));
    }

    public function completeLesson(Request $request, $lessonId)
    {
        $userId = Auth::user()->user_id;

        // Cek apakah sudah completed
        $alreadyDone = LessonProgress::where('user_id', $userId)
                                     ->where('lesson_id', $lessonId)
                                     ->where('is_completed', true)
                                     ->exists();

        if ($alreadyDone) {
            return back()->with('message', 'Pelajaran ini sudah selesai sebelumnya.');
        }

        // Pakai updateOrCreate — tidak butuh save(), tidak akan error 'id'
        LessonProgress::updateOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['is_completed' => true, 'completed_at' => now()]
        );

        // Hitung progress course
        $lesson       = \App\Models\Lesson::findOrFail($lessonId);
        $course       = $lesson->course;
        $totalLessons = $course->lessons()->count();

        $completedCount = LessonProgress::where('user_id', $userId)
                         ->whereIn('lesson_id', $course->lessons->pluck('id'))
                         ->where('is_completed', true)
                         ->count();

        $progressPercent = $totalLessons > 0
            ? round(($completedCount / $totalLessons) * 100)
            : 0;

        Enrollment::where('user_id', $userId)
                  ->where('course_id', $course->course_id)
                  ->update(['progress' => $progressPercent]);

        // Tambah XP +10
        DB::table('users')
            ->where('user_id', $userId)
            ->increment('xp', 10);

        // Update streak
        $user      = Auth::user()->fresh();
        $today     = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        if (!$user->last_activity || $user->last_activity->toDateString() != $today) {
            if ($user->last_activity && $user->last_activity->toDateString() == $yesterday) {
                $user->increment('streak');
            } else {
                $user->streak = 1;
            }
            $user->last_activity = now();
            $user->save();
        }

        return back()->with('message', 'Pelajaran selesai! +10 XP 🎉');
    }
}