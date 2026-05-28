<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; text-decoration:none; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .filter-btn { padding:6px 14px; border-radius:999px; font-size:13px; border:1px solid #e0e0e0; background:white; cursor:pointer; color:#444; text-decoration:none; display:inline-block; }
        .filter-btn.active { background:#05192D; color:white; border-color:#05192D; }
        .filter-btn:hover:not(.active) { background:#f0f0f0; }
    </style>
</head>
<body>
<x-navbar />
<div class="flex min-h-screen">
    <x-sidebar />
    <main class="flex-1">

        {{-- Hero --}}
        <div class="p-8 flex items-center justify-between" style="background:#05192D">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-2xl font-bold text-white">Practice</h1>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1" style="background:#f97316;color:white">
                        ↔ Reinforce what you're learning
                    </span>
                </div>
                <p class="text-sm text-gray-300 max-w-lg">Keep your skills sharp with quick daily challenges. You earn XP for every practice round.</p>
            </div>
        </div>

        <div class="p-6">

            @php
                $topicColors = [
                    'Python'       => '#3b82f6',
                    'SQL'          => '#10b981',
                    'R'            => '#8b5cf6',
                    'Power BI'     => '#f59e0b',
                    'Tableau'      => '#06b6d4',
                    'Excel'        => '#22c55e',
                    'Theory'       => '#6366f1',
                    'ChatGPT'      => '#10b981',
                    'Alteryx'      => '#f97316',
                    'Google Sheets'=> '#34d399',
                ];
            @endphp

            {{-- Filter Pills --}}
            <div class="flex flex-wrap gap-2 mb-5">
                <a href="{{ route('practice.index') }}"
                   class="filter-btn {{ !$topikId ? 'active' : '' }}">
                    All
                </a>
                @foreach($topik as $t)
                <a href="{{ route('practice.index', ['topic' => $t->topik_id]) }}"
                   class="filter-btn {{ $topikId == $t->topik_id ? 'active' : '' }}">
                    {{ $t->nama_topik }}
                </a>
                @endforeach
            </div>

            {{-- Count + Search --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-900">{{ $sessions->count() }}</span> Practice sessions
                </p>
                <form method="GET" action="{{ route('practice.index') }}" class="flex items-center gap-3">
                    @if($topikId)
                        <input type="hidden" name="topic" value="{{ $topikId }}">
                    @endif
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search sessions..."
                               class="border border-gray-200 bg-white text-sm pl-9 pr-4 py-2 rounded-lg w-56 focus:outline-none focus:border-blue-400">
                    </div>
                </form>
            </div>

            {{-- Practice Cards --}}
            @if($sessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($sessions as $session)
                @php
                    $color      = $topicColors[$session->topik->nama_topik ?? ''] ?? '#6366f1';
                    $completed  = $session->is_completed;
                    $lastSkor   = $session->last_skor;
                @endphp
                <div class="card p-5 hover:shadow-md transition-shadow flex flex-col justify-between gap-4">

                    {{-- Top --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">PRACTICE</p>
                            @if($completed)
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                      style="background:#f0fdf4; color:#15803d;">
                                    ✓ Selesai
                                </span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-gray-900 leading-snug">
                            {{ $session->nama_session }}
                        </h3>
                    </div>

                    {{-- Bottom --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center"
                                 style="background:{{ $color }}20">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                     stroke="{{ $color }}" stroke-width="2">
                                    <polyline points="16 18 22 12 16 6"/>
                                    <polyline points="8 6 2 12 8 18"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">{{ $session->topik->nama_topik ?? '-' }}</span>
                                @if($lastSkor !== null)
                                    <span class="text-xs text-gray-400 ml-1">· Skor: {{ $lastSkor }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Start / Ulangi --}}
                        <a href="{{ route('practice.intro', $session->session_id) }}"
                           class="px-4 py-1.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-80"
                           style="background: {{ $completed ? '#f97316' : '#05192D' }}">
                            {{ $completed ? 'Ulangi' : 'Start' }}
                        </a>
                    </div>

                </div>
                @endforeach
            </div>

            @else
            <div class="text-center py-16 text-gray-400">
                <svg class="mx-auto mb-4 w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                <p class="text-sm">Belum ada practice session yang ditemukan.</p>
            </div>
            @endif

        </div>
    </main>
</div>
</body>
</html>
