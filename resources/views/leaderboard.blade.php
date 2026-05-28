<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
        .sidebar-link svg { width:18px; height:18px; opacity:0.6; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <x-sidebar />

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-8">

        @php
            $currentUser = Auth::user();
            $allUsers = \App\Models\User::orderByDesc('xp')->get();
            $currentUserRank = $allUsers->search(fn($u) => $u->id === $currentUser->id) + 1;
            $currentXp = $currentUser->xp ?? 0;
            $targetXp = 250;
            $progress = min(100, round(($currentXp / $targetXp) * 100));
        @endphp

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Leaderboard</h1>
            <p class="text-sm text-gray-500">Track your learning progress and compete with fellow learners</p>
        </div>

        {{-- XP Progress Card --}}
        <div class="card p-6 mb-8 flex flex-col items-center text-center">
            <div class="w-24 h-24 mb-4 flex items-center justify-center">
                <svg viewBox="0 0 100 100" width="96" height="96" fill="none">
                    <polygon points="50,5 61,35 95,35 68,57 79,91 50,70 21,91 32,57 5,35 39,35" fill="#e8d5a3" stroke="#c9a96e" stroke-width="2"/>
                    <text x="50" y="58" text-anchor="middle" font-size="18" font-weight="bold" fill="#8B6914">DC</text>
                </svg>
            </div>
            <p class="text-xs font-semibold text-green-600 tracking-widest mb-2">9 HOURS LEFT TO JOIN</p>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Gain 250XP to join this week's Bit League</h2>
            <p class="text-sm text-gray-500 mb-5 max-w-md">Track your learning progress and see how you stack up against fellow data enthusiasts. New leaderboards open on a weekly basis.</p>
            <div class="w-full max-w-md flex items-center gap-3">
                <div class="flex-1 bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all" style="width:{{ $progress }}%"></div>
                </div>
                <span class="text-sm text-gray-600 shrink-0">{{ $currentXp }} / {{ $targetXp }} XP</span>
            </div>
        </div>

        {{-- Leaderboard Table --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">All Learners</h3>
                <span class="text-xs text-gray-400">Ranked by XP</span>
            </div>

            @foreach($allUsers as $i => $user)
            <div class="flex items-center gap-4 px-6 py-3 {{ $user->id === $currentUser->id ? 'bg-green-50' : '' }} {{ !$loop->last ? 'border-b border-gray-50' : '' }} hover:bg-gray-50 transition-colors">

                {{-- Rank --}}
                <div class="w-8 text-center shrink-0">
                    @if($i === 0)
                        <span class="text-lg">🥇</span>
                    @elseif($i === 1)
                        <span class="text-lg">🥈</span>
                    @elseif($i === 2)
                        <span class="text-lg">🥉</span>
                    @else
                        <span class="text-sm font-semibold text-gray-400">{{ $i + 1 }}</span>
                    @endif
                </div>

                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                {{-- Name --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 {{ $user->id === $currentUser->id ? 'font-semibold' : '' }}">
                        {{ $user->name }}
                        @if($user->id === $currentUser->id)
                            <span class="ml-1 text-xs text-green-600"></span>
                        @endif
                    </p>
                </div>

                {{-- XP Bar --}}
                <div class="flex items-center gap-3 w-48 shrink-0">
                    <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width:{{ $allUsers->first()->xp > 0 ? min(100, round((($user->xp ?? 0) / $allUsers->first()->xp) * 100)) : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 w-16 text-right shrink-0">{{ $user->xp ?? 0 }} XP</span>
                </div>
            </div>
            @endforeach
        </div>

    </main>
</div>

</body>
</html>

