<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PracticeSession;
use App\Models\Topik;
use App\Models\UserSession;

class PracticeController extends Controller
{
    public function intro($id)
    {
    $session = PracticeSession::with('topik')->findOrFail($id);
    
    $totalSoal = DB::table('practice_question')
        ->where('session_id', $id)
        ->count();

    // Ambil jumlah attempt user untuk session ini
    $totalAttempt = \App\Models\UserSession::where('user_id', Auth::id())
        ->where('session_id', $id)
        ->count();

    return view('practice.intro', compact('session', 'totalSoal', 'totalAttempt'));
    }

    public function start($id)
    {
        $userId = Auth::id();

    // Cek sesi aktif yang belum selesai
    $existing = \App\Models\UserSession::where('user_id', $userId)
        ->where('session_id', $id)
        ->whereIn('status', ['Start', 'Process'])
        ->first();

    if (!$existing) {
        // Hitung attempt — tapi hanya dari yang Finish
        $attempt = \App\Models\UserSession::where('user_id', $userId)
            ->where('session_id', $id)
            ->where('status', 'Finish')
            ->count() + 1;

        \App\Models\UserSession::create([
            'user_id'    => $userId,
            'session_id' => $id,
            'attempt'    => $attempt,
            'status'     => 'Start',
            'skor'       => 0,
        ]);
    }

    return redirect()->route('practice.play', $id);
    }

    public function play($id)
    {
    $session = PracticeSession::with('topik')->findOrFail($id);
    
    $soalList = DB::table('practice_question')
        ->where('session_id', $id)
        ->orderBy('nomor_pertanyaan')
        ->get();

    return view('practice.play', compact('session', 'soalList'));
    }
    
    public function index(Request $request)
    {
        $topikId = $request->topic;
        $search  = $request->search;
        $userId  = Auth::id();

        $topik = Topik::orderBy('nama_topik')->get();
        $query = PracticeSession::with('topik');

        if ($topikId) {
            $query->where('topik_id', $topikId);
        }
        if ($search) {
            $query->where('nama_session', 'like', '%' . $search . '%');
        }

        $doneSessionIds = \App\Models\UserSession::where('user_id', $userId)
        ->where('status', 'Finish')
        ->pluck('session_id')
        ->toArray();

    $sessions = $query->get()->map(function ($session) use ($doneSessionIds) {
        $session->is_completed = in_array($session->session_id, $doneSessionIds);
        return $session;
    });


        $sessions = $query->get()->map(function ($session) {
    $session->is_completed = false;
    $session->last_skor    = null;
    return $session;
});


        return view('practice.index', compact('sessions', 'topik', 'topikId', 'search'));
    }
}