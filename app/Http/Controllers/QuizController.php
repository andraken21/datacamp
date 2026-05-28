<?php

namespace App\Http\Controllers;

use App\Models\PracticeSession;
use App\Models\PracticeQuestion;
use App\Models\UserSession;
use App\Models\UserAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    // ============================================
    // Halaman utama Practice — daftar semua session
    // GET /practice
    // ============================================
    public function index(Request $request)
    {
        $topikId = $request->query('topic');

        $topik = DB::table('topik')->orderBy('nama_topik')->get();

        $sessionsQuery = PracticeSession::with('topik')
            ->withCount('questions')
            ->orderBy('nama_session');

        if ($topikId) {
            $sessionsQuery->where('topik_id', $topikId);
        }

        $search = $request->query('search', '');
        if ($search) {
            $sessionsQuery->where('nama_session', 'like', '%' . $search . '%');
        }

        // Ambil semua user_session milik user dalam 1 query (hindari N+1)
        $userSessions = UserSession::where('user_id', Auth::id())
            ->orderByDesc('user_session_id')
            ->get()
            ->groupBy('session_id')
            ->map(fn($group) => $group->first());

        $sessions = $sessionsQuery->get()->map(function ($session) use ($userSessions) {
            $lastUserSession = $userSessions->get($session->session_id);

            $session->last_user_session = $lastUserSession;
            $session->is_completed      = $lastUserSession && $lastUserSession->status === 'Finish';
            $session->last_skor         = $lastUserSession->skor ?? null;

            return $session;
        });

        return view('practice.index', compact('sessions', 'topik', 'topikId'));
    }

    // ============================================
    // Halaman intro sebelum mulai quiz
    // GET /practice/{sessionId}/intro
    // ============================================
    public function intro($sessionId)
    {
        $session   = PracticeSession::with('topik')->findOrFail($sessionId);
        $totalSoal = PracticeQuestion::where('session_id', $sessionId)
                        ->where('nomor_pertanyaan', 1)
                        ->count();

        $totalAttempt = UserSession::where('user_id', Auth::id())
                        ->where('session_id', $sessionId)
                        ->count();

        return view('practice.intro', compact('session', 'totalSoal', 'totalAttempt'));
    }

    // ============================================
    // Mulai quiz — buat user_session baru
    // POST /practice/{sessionId}/start
    // ============================================
    public function start(Request $request, $sessionId)
    {
        $session = PracticeSession::findOrFail($sessionId);
        $userId  = Auth::id();

        // Kalau ada sesi yang masih Process, lanjutkan
        $ongoingSession = UserSession::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->where('status', 'Process')
            ->first();

        if ($ongoingSession) {
            return redirect()->route('practice.quiz', $ongoingSession->user_session_id);
        }

        // Hitung attempt ke berapa
        $attempt = UserSession::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->count() + 1;

        // Buat user_session baru
        $userSession = UserSession::create([
            'user_id'     => $userId,
            'session_id'  => $sessionId,
            'skor'        => 0,
            'attempt'     => $attempt,
            'status'      => 'Process',
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('practice.quiz', $userSession->user_session_id);
    }

    // ============================================
    // Halaman quiz — tampilkan soal satu per satu
    // GET /practice/{userSessionId}/quiz
    // ============================================
    public function quiz(Request $request, $userSessionId)
    {
        $userId      = Auth::id();
        $userSession = UserSession::where('user_session_id', $userSessionId)
            ->where('user_id', $userId)
            ->whereIn('status', ['Process', 'Start'])
            ->firstOrFail();

        $practiceSession = PracticeSession::with('topik')->findOrFail($userSession->session_id);

        // Tentukan nomor_pertanyaan berdasarkan attempt (1 → 2 → 3 → berulang)
        $nomorPertanyaan = (($userSession->attempt - 1) % 3) + 1;

        // Ambil semua soal untuk attempt ini
        $allQuestions = PracticeQuestion::where('session_id', $userSession->session_id)
            ->where('nomor_pertanyaan', $nomorPertanyaan)
            ->orderBy('question_id')
            ->get();

        // Ambil soal yang sudah dijawab
        $answeredIds = UserAnswer::where('user_session_id', $userSessionId)
            ->pluck('question_id')
            ->toArray();

        // Ambil soal berikutnya yang belum dijawab
        $currentQuestion = $allQuestions->whereNotIn('question_id', $answeredIds)->first();

        // Kalau semua sudah dijawab, selesaikan otomatis
        if (!$currentQuestion) {
            return $this->finishAndRedirect($userSession);
        }

        // Acak urutan opsi
        $opsi = [
            $currentQuestion->opsi_1,
            $currentQuestion->opsi_2,
            $currentQuestion->opsi_3,
        ];
        shuffle($opsi);

        // Ambil feedback jawaban sebelumnya dari session flash
        $lastAnswer = session('last_answer');

        $totalSoal    = $allQuestions->count();
        $sudahDijawab = count($answeredIds);
        $nomorSoal    = $sudahDijawab + 1;

        return view('practice.quiz', compact(
            'userSession',
            'practiceSession',
            'currentQuestion',
            'opsi',
            'totalSoal',
            'nomorSoal',
            'sudahDijawab',
            'lastAnswer'
        ));
    }

    // ============================================
    // Submit jawaban satu soal
    // POST /practice/{userSessionId}/answer
    // ============================================
    public function submitAnswer(Request $request, $userSessionId)
    {
        $request->validate([
            'question_id'     => 'required|exists:practice_question,question_id',
            'jawaban_dipilih' => 'required|string',
        ]);

        $userId      = Auth::id();
        $userSession = UserSession::where('user_session_id', $userSessionId)
            ->where('user_id', $userId)
            ->where('status', 'Process')
            ->firstOrFail();

        // Cek sudah dijawab belum
        $alreadyAnswered = UserAnswer::where('user_session_id', $userSessionId)
            ->where('question_id', $request->question_id)
            ->exists();

        if ($alreadyAnswered) {
            return redirect()->route('practice.quiz', $userSessionId);
        }

        // Ambil jawaban benar dari DB
        $question  = PracticeQuestion::findOrFail($request->question_id);
        $isCorrect = trim($request->jawaban_dipilih) === trim($question->jawaban_benar);

        // Simpan jawaban
        UserAnswer::create([
            'user_session_id' => $userSessionId,
            'question_id'     => $request->question_id,
            'jawaban_dipilih' => $request->jawaban_dipilih,
            'is_correct'      => $isCorrect,
            'answered_at'     => now(),
        ]);

        // Flash feedback ke session
        session()->flash('last_answer', [
            'is_correct'      => $isCorrect,
            'jawaban_benar'   => $question->jawaban_benar,
            'jawaban_dipilih' => $request->jawaban_dipilih,
        ]);

        return redirect()->route('practice.quiz', $userSessionId);
    }

    // ============================================
    // Selesaikan quiz manual
    // POST /practice/{userSessionId}/finish
    // ============================================
    public function finish($userSessionId)
    {
        $userId      = Auth::id();
        $userSession = UserSession::where('user_session_id', $userSessionId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $this->finishAndRedirect($userSession);
    }

    // ============================================
    // Helper: hitung skor, tambah XP, redirect result
    // ============================================
    private function finishAndRedirect(UserSession $userSession)
    {
        $totalSoal  = UserAnswer::where('user_session_id', $userSession->user_session_id)->count();
        $totalBenar = UserAnswer::where('user_session_id', $userSession->user_session_id)
            ->where('is_correct', true)->count();

        $skor = $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100) : 0;

        $userSession->update([
            'skor'          => $skor,
            'status'        => 'Finish',
            'waktu_selesai' => now(),
        ]);

        // +50 XP setiap menyelesaikan 1 session
        $this->tambahXP(Auth::id(), 50);

        return redirect()->route('practice.result', $userSession->user_session_id);
    }

    // ============================================
    // Halaman hasil akhir quiz
    // GET /practice/{userSessionId}/result
    // ============================================
    public function result($userSessionId)
    {
        $userId      = Auth::id();
        $userSession = UserSession::where('user_session_id', $userSessionId)
            ->where('user_id', $userId)
            ->where('status', 'Finish')
            ->firstOrFail();

        $practiceSession = PracticeSession::with('topik')->findOrFail($userSession->session_id);

        $answers    = UserAnswer::where('user_session_id', $userSessionId)->with('question')->get();
        $totalSoal  = $answers->count();
        $totalBenar = $answers->where('is_correct', true)->count();
        $totalSalah = $totalSoal - $totalBenar;
        $skor       = $userSession->skor;

        $durasi = null;
        if ($userSession->waktu_mulai && $userSession->waktu_selesai) {
            $durasi = $userSession->waktu_mulai->diffInMinutes($userSession->waktu_selesai);
        }

        return view('practice.result', compact(
            'userSession',
            'practiceSession',
            'answers',
            'totalSoal',
            'totalBenar',
            'totalSalah',
            'skor',
            'durasi'
        ));
    }

    // ============================================
    // Helper: tambah XP & update streak user
    // ============================================
    private function tambahXP($userId, $xp)
    {
        $user  = User::findOrFail($userId);
        $today = now()->toDateString();

        $lastActivity = $user->last_activity;
        $isYesterday  = $lastActivity && \Carbon\Carbon::parse($lastActivity)->toDateString() === now()->subDay()->toDateString();
        $isToday      = $lastActivity && \Carbon\Carbon::parse($lastActivity)->toDateString() === $today;

        if ($isToday) {
            $newStreak = $user->streak;
        } elseif ($isYesterday) {
            $newStreak = $user->streak + 1;
        } else {
            $newStreak = 1;
        }

        DB::table('users')
        ->where('user_id', $user->user_id)
        ->increment('xp', 50);
        

        $user->update([
            'xp'            => $user->xp + $xp,
            'streak'        => $newStreak,
            'last_activity' => $today,
        ]);
    }
}
