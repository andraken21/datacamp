<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice - DataCamp</title>
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

    {{-- SIDEBAR --}}
    <x-sidebar />

    {{-- MAIN --}}
    <main class="flex-1">

        {{-- Hero Banner --}}
        <div class="p-8 flex items-center justify-between" style="background:#05192D">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-2xl font-bold text-white">Practice</h1>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1" style="background:#f97316;color:white">
                        ↔ Reinforce what you're learning
                    </span>
                </div>
                <p class="text-sm text-gray-300 max-w-lg">Keep your skills sharp with quick daily challenges on desktop or Mobile app. You earn XP for every practice round.</p>
            </div>
            <div class="hidden lg:block">
                <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
                    <circle cx="90" cy="30" r="16" fill="none" stroke="#f97316" stroke-width="2"/>
                    <circle cx="60" cy="60" r="10" fill="none" stroke="#f97316" stroke-width="1.5" opacity="0.5"/>
                    <circle cx="30" cy="70" r="10" fill="none" stroke="#f97316" stroke-width="1.5" opacity="0.5"/>
                    <circle cx="90" cy="70" r="16" fill="#f97316" opacity="0.9"/>
                    <path d="M30 70 Q45 40 60 60 Q75 80 90 30" stroke="#f97316" stroke-width="1.5" fill="none" stroke-dasharray="4 2"/>
                    <text x="78" y="75" font-size="8" fill="white" font-weight="bold">PRACTICE</text>
                </svg>
            </div>
        </div>

        <div class="p-6">
            {{-- Filter pills --}}
            @php $activeFilter = request('topic', 'all'); @endphp
            <div class="flex flex-wrap gap-2 mb-2">
                <a href="{{ route('practice') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
                @foreach(['Python','SQL','R','Power BI','Tableau','Alteryx','Excel','Google Sheets','ChatGPT','Gemini','PyTorch','OpenAI','AWS','Azure'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach(['Snowflake','Databricks','Git','Docker','Shell','Kubernetes','Airflow','Spark'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
                <span class="filter-btn">+21</span>
            </div>

            {{-- Count + search --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500"><span class="font-semibold text-gray-900">614</span> Practice sessions</p>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Search courses..." class="border border-gray-200 bg-white text-sm pl-9 pr-4 py-2 rounded-lg w-48 focus:outline-none focus:border-green-400">
                    </div>
                    <select class="border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg focus:outline-none">
                        <option>Topic</option>
                    </select>
                </div>
            </div>

            {{-- Practice Cards --}}
            @php
                $practices = [
                    ['title' => 'Introduction to AI for Work', 'topic' => 'Theory', 'color' => '#6366f1'],
                    ['title' => 'Introduction to Python', 'topic' => 'Python', 'color' => '#3b82f6'],
                    ['title' => 'Introduction to Power BI', 'topic' => 'Power BI', 'color' => '#f59e0b'],
                    ['title' => 'Introduction to SQL', 'topic' => 'SQL', 'color' => '#10b981'],
                    ['title' => 'Data Manipulation with pandas', 'topic' => 'Python', 'color' => '#3b82f6'],
                    ['title' => 'Introduction to R', 'topic' => 'R', 'color' => '#8b5cf6'],
                ];
            @endphp
            <div class="grid grid-cols-3 gap-4">
                @foreach($practices as $practice)
                <div class="card p-5 hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">PRACTICE</p>
                    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $practice['title'] }}</h3>
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background:{{ $practice['color'] }}20">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $practice['color'] }}" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            </div>
                            <span class="text-sm text-gray-500">{{ $practice['topic'] }}</span>
                        </div>
                        <a href="#" class="px-4 py-1.5 rounded-lg text-sm font-semibold border border-gray-200 hover:bg-gray-50 text-gray-700">Start</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

</body>
</html>

