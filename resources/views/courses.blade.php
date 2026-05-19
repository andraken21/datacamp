<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <h1 class="text-2xl font-bold text-white">Courses</h1>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1" style="background:#03EF62;color:#05192D">
                        📍 Hands-on learning
                    </span>
                </div>
                <p class="text-sm text-gray-300 max-w-lg">It's time to roll up your sleeves—we learn best by doing. All of our courses are interactive, combining short videos with hands-on exercises.</p>
            </div>
            <div class="hidden lg:block">
                <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
                    <circle cx="90" cy="30" r="16" fill="none" stroke="#03EF62" stroke-width="2"/>
                    <circle cx="60" cy="60" r="10" fill="none" stroke="#03EF62" stroke-width="1.5" opacity="0.5"/>
                    <circle cx="30" cy="70" r="10" fill="none" stroke="#03EF62" stroke-width="1.5" opacity="0.5"/>
                    <path d="M30 70 Q45 40 60 60 Q75 80 90 30" stroke="#03EF62" stroke-width="1.5" fill="none" stroke-dasharray="4 2"/>
                    <circle cx="90" cy="30" r="4" fill="#03EF62"/>
                    <text x="84" y="34" font-size="8" fill="#03EF62" font-weight="bold">LEARN</text>
                </svg>
            </div>
        </div>

        <div class="p-6">
            {{-- Filter pills row 1 --}}
@php
    $activeFilter = request('topic', 'all');
    $mainTopics = ['Python','SQL','R','Power BI','Tableau','Alteryx','Excel','Google Sheets','ChatGPT','Gemini','PyTorch','OpenAI','AWS','Azure'];
    $moreTopics = ['Snowflake','Databricks','Git','Docker','Shell','Kubernetes','Airflow','Spark','dbt','BigQuery','Redshift','Scala','Julia','MLflow','Theory','Google Cloud','Claude','n8n','Sigma','Microsoft Copilot','Cursor','GitHub','Java','DataLab','FastAPI','Llama','KNIME','Kafka','DVC'];
@endphp

<div class="flex flex-wrap gap-2 mb-2">
    <a href="{{ route('courses') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
    @foreach($mainTopics as $topic)
    <a href="{{ route('courses', array_merge(request()->except('page'), ['topic'=>strtolower($topic)])) }}"
       class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
    @endforeach
</div>
<div class="flex flex-wrap gap-2 mb-5 items-center">
    @foreach(['Snowflake','Databricks','Git','Docker','Shell','Kubernetes','Airflow','Spark'] as $topic)
    <a href="{{ route('courses', array_merge(request()->except('page'), ['topic'=>strtolower($topic)])) }}"
       class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
    @endforeach

    {{-- Dropdown +21 --}}
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="filter-btn flex items-center gap-1">
            +21
            <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><path d="M5 7L0 2h10z"/></svg>
        </button>
        <div x-show="open" @click.away="open = false"
             class="absolute top-full left-0 mt-1 z-50 bg-white border border-gray-200 rounded-xl shadow-lg p-3 w-64">
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2 px-1">TECHNOLOGY</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($moreTopics as $topic)
                <a href="{{ route('courses', array_merge(request()->except('page'), ['topic'=>strtolower($topic)])) }}"
                   class="filter-btn text-xs py-1 px-2.5 {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Search + count bar --}}
<form method="GET" action="{{ route('courses') }}" class="flex items-center gap-3 mb-6">
    <p class="text-sm text-gray-500 shrink-0"><span class="font-semibold text-gray-900">{{ $courses->total() }}</span> Courses</p>
    <div class="flex-1"></div>
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..."
            class="border border-gray-200 bg-white text-sm pl-9 pr-4 py-2 rounded-lg w-48 focus:outline-none focus:border-green-400">
    </div>
    <select name="sort" onchange="this.form.submit()" class="border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg focus:outline-none">
        <option value="popular">Topic</option>
        <option value="rating">Rating</option>
        <option value="newest">Newest</option>
    </select>
    <button type="button" class="flex items-center gap-2 border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg hover:bg-gray-50">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
        More filters
    </button>
</form>

{{-- Course Grid --}}
@if($courses->count())
<div class="grid grid-cols-3 gap-4 mb-6">
    @foreach($courses as $course)
<a href="{{ route('course.detail', $course->slug) }}" class="card overflow-hidden transition-shadow block hover:shadow-md">
    <div class="h-1" style="background:{{ $course->thumbnail_color }}"></div>
    <div class="p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">COURSE</p>
        <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2">{{ $course->title }}</h3>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-3 h-3 rounded-sm" style="background:{{ $course->thumbnail_color }}"></div>
            <span class="text-sm text-gray-500">{{ $course->level->nama_level ?? '-' }}</span>        
        </div>
        <p class="text-sm text-gray-500 line-clamp-2 mb-4 leading-relaxed">{{ $course->description }}</p>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $course->instructor ?? 'DataCamp' }}</span>
            <span class="text-xs text-gray-400">{{ $course->durasi }}</span>
        </div>
        @if(!$course->is_free)
        <span class="text-xs bg-yellow-50 text-yellow-600 border border-yellow-200 px-2 py-0.5 rounded font-medium mt-2 inline-block">PRO</span>
        @endif
    </div>
</a>
@endforeach
</div>
{{ $courses->withQueryString()->links() }}
@else
<div class="text-center py-20">
    <p class="text-gray-500 text-sm mb-2">Tidak ada kursus yang cocok.</p>
    <a href="{{ route('courses') }}" class="text-sm text-green-600 hover:text-green-500">Reset filter →</a>
</div>
@endif
        </div>
    </main>
</div>

</body>
</html>


