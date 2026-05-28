<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tool;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Current course enrollment
        $enrollment = $user->enrollments()->with('course')->latest()->first();

        // Enrolled track
        $enrolledTrack = $user->enrollments()
            ->with('course.track')
            ->latest()
            ->first()
            ?->course
            ?->track ?? null;

        // ✅ Pakai Stored Procedure: GetLeaderboard(5)
        $topUsers = DB::select('CALL GetLeaderboard(5)');

        // ✅ Pakai Stored Procedure: GetUserXPSummary(user_id)
        $xpSummary = DB::select('CALL GetUserXPSummary(?)', [$user->user_id]);
        $xpSummary = $xpSummary[0] ?? null;

        $xpPercent = $xpSummary?->xp_percent ?? 0;

        // Sandbox tools
        $sandboxTools = Tool::orderBy('nama_sandbox')->take(6)->get();

        return view('home-logged', compact(
            'enrollment',
            'enrolledTrack',
            'topUsers',
            'xpPercent',
            'sandboxTools',
        ));
    }
}