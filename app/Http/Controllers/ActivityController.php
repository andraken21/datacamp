<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $userId = $user->user_id;

        // ── Kursus ──────────────────────────────────────────────
        $enrollments = Enrollment::where('user_id', $userId)
            ->with('course')
            ->get();

        $coursesCompleted = $enrollments->where('progress', 100)->count();

        // ── Practice Sessions ────────────────────────────────────
        // Ambil satu sesi terbaru per session_id (tidak duplikat)
        $practiceSessions = \App\Models\UserSession::where('user_session.user_id', $userId)
            ->join('practice_session', 'user_session.session_id', '=', 'practice_session.session_id')
            ->join('topik', 'practice_session.topik_id', '=', 'topik.topik_id')
            ->select(
                'user_session.user_session_id',
                'user_session.session_id',
                'user_session.skor',
                'user_session.attempt',
                'user_session.status',
                'user_session.create_at',
                'practice_session.nama_session',
                'topik.nama_topik'
            )
        // Ambil hanya 1 record per session — yang paling baru
        ->whereIn('user_session.user_session_id', function($sub) use ($userId) {
            $sub->selectRaw('MAX(user_session_id)')
                ->from('user_session')
                ->where('user_id', $userId)
                ->groupBy('session_id');
            })
        ->orderByDesc('user_session.create_at')
        ->get();

        $practiceCount = $practiceSessions->count();

        return view('my-activity', compact(
            'enrollments',
            'coursesCompleted',
            'practiceSessions',
            'practiceCount'
        ));
    }
}