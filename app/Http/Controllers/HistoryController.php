<?php

namespace App\Http\Controllers;

use App\Models\UserSession;
use App\Models\UserAnswer;
use App\Models\PracticeQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Ambil semua riwayat sesi quiz milik user
     * GET /api/history
     */
    public function index()
    {
        $userId = Auth::id();

        $history = UserSession::where('user_id', $userId)
            ->join('practice_session', 'user_session.session_id', '=', 'practice_session.session_id')
            ->join('topik', 'practice_session.topik_id', '=', 'topik.topik_id')
            ->select(
                'user_session.user_session_id',
                'user_session.session_id',
                'user_session.skor',
                'user_session.attempt',
                'user_session.status',
                'user_session.waktu_mulai',
                'user_session.waktu_selesai',
                'practice_session.nama_session',
                'topik.nama_topik',
                DB::raw("TIMESTAMPDIFF(MINUTE, user_session.waktu_mulai, user_session.waktu_selesai) as durasi_menit")
            )
            ->orderByDesc('user_session.waktu_mulai')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $history
        ]);
    }

    /**
     * Detail riwayat 1 sesi — tampilkan semua jawaban user
     * GET /api/history/{userSessionId}
     */
    public function show($userSessionId)
    {
        $userId = Auth::id();

        // Pastikan sesi ini milik user yang login
        $userSession = UserSession::where('user_session_id', $userSessionId)
            ->where('user_id', $userId)
            ->join('practice_session', 'user_session.session_id', '=', 'practice_session.session_id')
            ->join('topik', 'practice_session.topik_id', '=', 'topik.topik_id')
            ->select(
                'user_session.*',
                'practice_session.nama_session',
                'topik.nama_topik',
                DB::raw("TIMESTAMPDIFF(MINUTE, user_session.waktu_mulai, user_session.waktu_selesai) as durasi_menit")
            )
            ->firstOrFail();

        // Ambil semua jawaban beserta soalnya
        $answers = UserAnswer::where('user_session_id', $userSessionId)
            ->join('practice_question', 'user_answer.question_id', '=', 'practice_question.question_id')
            ->select(
                'user_answer.answer_id',
                'user_answer.jawaban_dipilih',
                'user_answer.is_correct',
                'user_answer.answered_at',
                'practice_question.pertanyaan',
                'practice_question.opsi_1',
                'practice_question.opsi_2',
                'practice_question.opsi_3',
                'practice_question.jawaban_benar',
            )
            ->get();

        $totalSoal  = $answers->count();
        $totalBenar = $answers->where('is_correct', true)->count();
        $totalSalah = $totalSoal - $totalBenar;

        return response()->json([
            'success'     => true,
            'sesi'        => $userSession,
            'ringkasan'   => [
                'total_soal'  => $totalSoal,
                'total_benar' => $totalBenar,
                'total_salah' => $totalSalah,
                'skor'        => $userSession->skor,
            ],
            'jawaban' => $answers,
        ]);
    }

    /**
     * Statistik user — total sesi, rata-rata skor, XP, streak
     * GET /api/history/stats
     */
    public function stats()
    {
        $userId = Auth::id();
        $user   = Auth::user();

        $stats = UserSession::where('user_id', $userId)
            ->where('status', 'Finish')
            ->selectRaw('
                COUNT(*) as total_sesi,
                ROUND(AVG(skor), 1) as rata_rata_skor,
                MAX(skor) as skor_tertinggi,
                SUM(CASE WHEN skor = 100 THEN 1 ELSE 0 END) as total_perfect
            ')
            ->first();

        // Topik yang paling sering dimainkan
        $favoritTopik = UserSession::where('user_session.user_id', $userId)
            ->where('user_session.status', 'Finish')
            ->join('practice_session', 'user_session.session_id', '=', 'practice_session.session_id')
            ->join('topik', 'practice_session.topik_id', '=', 'topik.topik_id')
            ->select('topik.nama_topik', DB::raw('COUNT(*) as total_main'))
            ->groupBy('topik.topik_id', 'topik.nama_topik')
            ->orderByDesc('total_main')
            ->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'xp'            => $user->xp,
                'streak'        => $user->streak,
                'last_activity' => $user->last_activity,
                'total_sesi'    => $stats->total_sesi ?? 0,
                'rata_rata_skor'=> $stats->rata_rata_skor ?? 0,
                'skor_tertinggi'=> $stats->skor_tertinggi ?? 0,
                'total_perfect' => $stats->total_perfect ?? 0,
                'favorit_topik' => $favoritTopik->nama_topik ?? '-',
            ]
        ]);
    }
}
