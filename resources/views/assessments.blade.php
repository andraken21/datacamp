<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessments - DataCamp</title>
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
                    <h1 class="text-2xl font-bold text-white">DataCamp Signal™ Assessments</h1>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background:#3b82f6;color:white">📡 Know where you stand</span>
                </div>
                <p class="text-sm text-gray-300 max-w-lg">How do your skills stack up? Discover your skill level in just 10 minutes to get personalized learning recommendations.</p>
            </div>
        </div>

        <div class="p-6">

            @php
                // Ambil filter aktif
                $activeFilter = request('topic', 'all');

                // Ambil semua topik yang punya assessment (join topik)
                $topikList = DB::table('assessment')
                    ->join('topik', 'assessment.topik_id', '=', 'topik.topik_id')
                    ->select('topik.nama_topik')
                    ->distinct()
                    ->pluck('nama_topik');

                // Query assessment dari DB dengan filter topik
                $query = DB::table('assessment')
                    ->leftJoin('topik', 'assessment.topik_id', '=', 'topik.topik_id')
                    ->select(
                        'assessment.assessment_id',
                        'assessment.nama_assessment',
                        'assessment.url',
                        'assessment.slug',
                        'assessment.jumlah_pertanyaan',
                        'assessment.sisa_percobaan',
                        'topik.nama_topik'
                    );

                if ($activeFilter !== 'all') {
                    $query->where('topik.nama_topik', 'like', '%' . $activeFilter . '%');
                }

                $assessments = $query->get();

                // Warna per topik
                $topicColors = [
                    'Python' => '#3b82f6',
                    'SQL'    => '#10b981',
                    'R'      => '#8b5cf6',
                    'Theory' => '#6366f1',
                    'Power BI' => '#f59e0b',
                ];
            @endphp

            {{-- Filter Pills --}}
            <div class="flex flex-wrap gap-2 mb-5">
                <a href="{{ route('assessments') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
                @foreach($topikList as $topik)
                <a href="?topic={{ strtolower($topik) }}"
                   class="filter-btn {{ $activeFilter == strtolower($topik) ? 'active' : '' }}">
                    {{ $topik }}
                </a>
                @endforeach
            </div>

            <p class="text-sm text-gray-500 mb-5">
                <span class="font-semibold text-gray-900">{{ $assessments->count() }}</span> Assessments
            </p>

            {{-- Assessment Cards --}}
            @if($assessments->count() > 0)
            <div class="grid grid-cols-3 gap-4">
                @foreach($assessments as $assessment)
                @php
                    $color = $topicColors[$assessment->nama_topik ?? ''] ?? '#6366f1';
                @endphp
                <div class="card p-5 hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">ASSESSMENT</p>
                    <h3 class="text-base font-bold text-gray-900 mb-1">{{ $assessment->nama_assessment }}</h3>
                    @if($assessment->jumlah_pertanyaan)
                    <p class="text-xs text-gray-400 mb-4">{{ $assessment->jumlah_pertanyaan }} pertanyaan
                        @if($assessment->sisa_percobaan)
                        · {{ $assessment->sisa_percobaan }} percobaan tersisa
                        @endif
                    </p>
                    @else
                    <div class="mb-4"></div>
                    @endif
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:{{ $color }}20">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-500">{{ $assessment->nama_topik ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($assessment->url)
                            <a href="{{ $assessment->url }}" target="_blank"
                               class="px-4 py-1.5 rounded-lg text-sm font-semibold text-white"
                               style="background:#05192D">
                                Start
                            </a>
                            @else
                            <span class="px-4 py-1.5 rounded-lg text-sm font-semibold border border-gray-200 text-gray-400 cursor-not-allowed">
                                Start
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16 text-gray-400">
                <svg class="mx-auto mb-4 w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm">Belum ada assessment untuk topik ini.</p>
            </div>
            @endif

        </div>
    </main>
</div>
</body>
</html>