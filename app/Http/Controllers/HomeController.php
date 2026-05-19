<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        // Leaderboard top 5
        $topUsers = User::orderByDesc('xp')->take(5)->get();

        // XP progress ke 250
        $xpPercent = min(100, round(($user->xp ?? 0) / 250 * 100));

        return view('home-logged', compact(
            'enrollment',
            'enrolledTrack', 
            'topUsers',
            'xpPercent'
        ));
    }
}