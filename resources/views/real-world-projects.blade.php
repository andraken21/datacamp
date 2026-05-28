<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real World Projects - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; text-decoration:none; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
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
                <h1 class="text-2xl font-bold text-white mb-2">Real World Projects</h1>
                <p class="text-sm text-gray-300 max-w-lg">Engage with real-world challenges using data notebooks and BI applications. Apply your knowledge to real scenarios in a practical environment.</p>
            </div>
            <div class="hidden lg:block">
                <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
                    <circle cx="40" cy="70" r="16" fill="#ec4899" opacity="0.9"/>
                    <circle cx="90" cy="30" r="10" fill="none" stroke="#ec4899" stroke-width="1.5" opacity="0.5"/>
                    <circle cx="90" cy="70" r="10" fill="none" stroke="#ec4899" stroke-width="1.5" opacity="0.5"/>
                    <path d="M40 70 Q60 40 90 30" stroke="#ec4899" stroke-width="1.5" fill="none" stroke-dasharray="4 2"/>
                    <path d="M90 30 Q100 50 90 70" stroke="#ec4899" stroke-width="1.5" fill="none" stroke-dasharray="4 2"/>
                    <text x="25" y="74" font-size="7" fill="white" font-weight="bold">APPLY</text>
                </svg>
            </div>
        </div>

        <div class="p-6">
            @php $activeFilter = request('topic', 'all'); @endphp
            <div class="flex flex-wrap gap-2 mb-2">
                <a href="{{ route('real-world-projects') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
                @foreach(['Python','SQL','R','Power BI','Tableau','Alteryx','Excel','Google Sheets','ChatGPT','Azure','Databricks','dbt','Theory','KNIME'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach(['PyTorch','OpenAI','Snowflake','Spark','BigQuery','Redshift'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
            </div>

            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500"><span class="font-semibold text-gray-900">166</span> Projects</p>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Search projects..." class="border border-gray-200 bg-white text-sm pl-9 pr-4 py-2 rounded-lg w-48 focus:outline-none focus:border-green-400">
                    </div>
                    <select class="border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg focus:outline-none">
                        <option>Topic</option>
                    </select>
                    <button class="flex items-center gap-2 border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg hover:bg-gray-50">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                        More filters
                    </button>
                </div>
            </div>

            @php
                $projects = [
                    ['slug' => 'cleaning-data-generative-ai',        'title' => 'Cleaning Data with Generative AI',               'level' => 'Basic',        'color' => '#03EF62', 'desc' => 'Use generative AI to tackle data cleaning, fixing duplicates, nulls, and formatting for consistent, high-quality datasets.'],
                    ['slug' => 'data-storytelling-college-majors',   'title' => 'Data Storytelling Case Study: College Majors',   'level' => 'Basic',        'color' => '#03EF62', 'desc' => 'Data storytelling is a high-demand skill that combines technical analysis with compelling narrative.'],
                    ['slug' => 'data-storytelling-green-businesses', 'title' => 'Data Storytelling Case Study: Green Businesses', 'level' => 'Basic',        'color' => '#03EF62', 'desc' => 'Practice data storytelling using real-world scenarios about sustainable businesses.'],
                    ['slug' => 'analyzing-students-mental-health',   'title' => 'Analyzing Students Mental Health',              'level' => 'Basic',        'color' => '#f59e0b', 'desc' => 'Explore and analyze student mental health data to uncover trends and patterns.'],
                    ['slug' => 'predicting-credit-card-approvals',   'title' => 'Predicting Credit Card Approvals',              'level' => 'Intermediate', 'color' => '#3b82f6', 'desc' => 'Build a machine learning model to predict whether a credit card application will be approved.'],
                    ['slug' => 'hypothesis-testing-healthcare',      'title' => 'Hypothesis Testing in Healthcare',              'level' => 'Intermediate', 'color' => '#3b82f6', 'desc' => 'Apply hypothesis testing techniques to real-world healthcare data.'],
                ];
            @endphp

            <div class="grid grid-cols-3 gap-4">
                @foreach($projects as $project)
                <div class="card p-5 hover:shadow-md transition-shadow cursor-pointer"
                     onclick="window.location='{{ route('real-world-project.show', $project['slug']) }}'">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">PROJECT</p>
                    <h3 class="text-base font-bold text-gray-900 mb-3">{{ $project['title'] }}</h3>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $project['desc'] }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:{{ $project['color'] }}"></div>
                            <span class="text-sm text-gray-500">{{ $project['level'] }}</span>
                        </div>
                        <a href="{{ route('real-world-project.show', $project['slug']) }}"
                           class="px-4 py-1.5 rounded-lg text-sm font-semibold border border-gray-200 hover:bg-gray-50 text-gray-700"
                           onclick="event.stopPropagation()">
                            Start
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>
</body>
</html>