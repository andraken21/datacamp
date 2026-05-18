<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
        .sidebar-link svg { width:18px; height:18px; opacity:0.6; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .tab-active { border-bottom: 2px solid #05192D; font-weight:600; color:#05192D; }
        .tab-inactive { color:#666; border-bottom: 2px solid transparent; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <x-sidebar />

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-8 max-w-5xl">

        <h1 class="text-2xl font-bold text-gray-900 mb-1">My Activity</h1>
        <p class="text-sm text-gray-500 mb-6">Everything you're working on and have completed</p>

        {{-- Stats --}}
        @php
            $enrollments = Auth::user()->enrollments()->with('course')->get();
            $completed = $enrollments->where('completed', true)->count();
        @endphp
        <div class="grid grid-cols-4 gap-0 card mb-8 divide-x divide-gray-100">
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $completed }}</p>
                <p class="text-sm text-gray-500 mt-1">Courses completed</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-sm text-gray-500 mt-1">Tracks completed</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-sm text-gray-500 mt-1">DataLab projects</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-sm text-gray-500 mt-1">Certifications</p>
            </div>
        </div>

        {{-- Tabs --}}
        @php $tab = request('tab', 'inprogress'); @endphp
        <div class="flex items-center gap-6 border-b border-gray-200 mb-6">
            <a href="?tab=inprogress" class="pb-3 text-sm px-1 {{ $tab=='inprogress' ? 'tab-active' : 'tab-inactive' }}">
                In Progress <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">{{ $enrollments->where('completed', false)->count() }}</span>
            </a>
            <a href="?tab=completed" class="pb-3 text-sm px-1 {{ $tab=='completed' ? 'tab-active' : 'tab-inactive' }}">
                Completed <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">{{ $completed }}</span>
            </a>
            <a href="?tab=skipped" class="pb-3 text-sm px-1 {{ $tab=='skipped' ? 'tab-active' : 'tab-inactive' }}">
                Skipped <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">0</span>
            </a>
        </div>

        {{-- Course List --}}
        @php
            $filtered = $tab == 'completed'
                ? $enrollments->where('completed', true)
                : $enrollments->where('completed', false);
        @endphp

        <p class="text-sm text-gray-500 mb-4">{{ $filtered->count() }} results</p>

        <div class="space-y-3">
            @forelse($filtered as $enrollment)
            <div class="card p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                     style="background:{{ $enrollment->course->thumbnail_color ?? '#374151' }}">
                    {{ $enrollment->course->icon_text ?? 'C' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">COURSE
                        @if($enrollment->course->is_ai_native ?? false)
                        <span class="ml-1 text-xs bg-purple-100 text-purple-600 px-1.5 py-0.5 rounded font-medium">✦ AI NATIVE</span>
                        @endif
                    </p>
                    <p class="text-sm font-semibold text-gray-900">{{ $enrollment->course->title }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <div class="flex-1 bg-gray-200 rounded-full h-1 max-w-xs">
                            <div class="bg-green-500 h-1 rounded-full" style="width:{{ $enrollment->progress ?? 0 }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $enrollment->progress ?? 0 }}%</span>
                    </div>
                </div>
                <a href="{{ route('course.learn', $enrollment->course->slug) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold shrink-0"
                   style="background:#03EF62;color:#05192D">
                    Continue
                </a>
            </div>
            @empty
            <div class="card p-10 text-center">
                <p class="text-gray-400 text-sm">No courses here yet.</p>
                <a href="{{ route('courses') }}" class="mt-3 inline-block text-sm text-green-600 hover:underline">Browse courses →</a>
            </div>
            @endforelse
        </div>

    </main>
</div>

</body>
</html>

