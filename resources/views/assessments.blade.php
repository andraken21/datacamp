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
        .sidebar-link svg { width:18px; height:18px; opacity:0.6; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
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
            @php $activeFilter = request('topic', 'all'); @endphp
            <div class="flex flex-wrap gap-2 mb-5">
                <a href="{{ route('assessments') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
                @foreach(['Python','SQL','R','Theory'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
            </div>
            <p class="text-sm text-gray-500 mb-5"><span class="font-semibold text-gray-900">30</span> Assessments</p>
            @php
                $assessments = [
                    ['title' => 'Excel Fundamentals', 'topic' => 'Python', 'color' => '#3b82f6'],
                    ['title' => 'Machine Learning Engineer', 'topic' => 'Python', 'color' => '#3b82f6'],
                    ['title' => 'Addressing Import and Parsing Warnings in R', 'topic' => 'R', 'color' => '#8b5cf6'],
                    ['title' => 'Data Analysis in SQL', 'topic' => 'SQL', 'color' => '#10b981'],
                    ['title' => 'Python Programming', 'topic' => 'Python', 'color' => '#3b82f6'],
                    ['title' => 'Data Literacy Fundamentals', 'topic' => 'Theory', 'color' => '#6366f1'],
                ];
            @endphp
            <div class="grid grid-cols-3 gap-4">
                @foreach($assessments as $assessment)
                <div class="card p-5 hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">ASSESSMENT</p>
                    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $assessment['title'] }}</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:{{ $assessment['color'] }}20">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $assessment['color'] }}" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            </div>
                            <span class="text-sm text-gray-500">{{ $assessment['topic'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="#" class="text-sm text-green-600 hover:underline">Topics</a>
                            <a href="#" class="px-4 py-1.5 rounded-lg text-sm font-semibold border border-gray-200 hover:bg-gray-50 text-gray-700">Start</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>
</body>
</html>