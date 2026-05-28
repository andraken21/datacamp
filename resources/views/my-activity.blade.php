<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .tab-active { border-bottom: 2px solid #05192D; font-weight:600; color:#05192D; }
        .tab-inactive { color:#666; border-bottom: 2px solid transparent; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">
    <x-sidebar />

    <main class="flex-1 p-8 max-w-5xl">

        <h1 class="text-2xl font-bold text-gray-900 mb-1">My Activity</h1>
        <p class="text-sm text-gray-500 mb-6">Everything you're working on and have completed</p>

        {{-- ── Stats ── --}}
        <div class="grid grid-cols-4 gap-0 card mb-8 divide-x divide-gray-100">
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $coursesCompleted }}</p>
                <p class="text-sm text-gray-500 mt-1">Courses completed</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-sm text-gray-500 mt-1">Tracks completed</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $practiceCount }}</p>
                <p class="text-sm text-gray-500 mt-1">Practice attempts</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-2xl font-bold text-gray-900">0</p>
                <p class="text-sm text-gray-500 mt-1">Certifications</p>
            </div>
        </div>

        {{-- ── Tabs ── --}}
        @php $tab = request('tab', 'inprogress'); @endphp
        <div class="flex items-center gap-6 border-b border-gray-200 mb-6">
            <a href="?tab=inprogress" class="pb-3 text-sm px-1 {{ $tab=='inprogress' ? 'tab-active' : 'tab-inactive' }}">
                In Progress
                <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">
                    {{ $enrollments->where('progress', '<', 100)->count() }}
                </span>
            </a>
            <a href="?tab=completed" class="pb-3 text-sm px-1 {{ $tab=='completed' ? 'tab-active' : 'tab-inactive' }}">
                Completed
                <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">
                    {{ $coursesCompleted }}
                </span>
            </a>
            <a href="?tab=practice" class="pb-3 text-sm px-1 {{ $tab=='practice' ? 'tab-active' : 'tab-inactive' }}">
                Practice
                <span class="ml-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">
                    {{ $practiceCount }}
                </span>
            </a>
        </div>

        {{-- ── Course Tabs (In Progress & Completed) ── --}}
        @if($tab !== 'practice')
            @php
                $filtered = $tab === 'completed'
                    ? $enrollments->where('progress', 100)
                    : $enrollments->where('progress', '<', 100);
            @endphp

            <p class="text-sm text-gray-500 mb-4">{{ $filtered->count() }} results</p>

            <div class="space-y-3">
                @forelse($filtered as $enrollment)
                    @if($enrollment->course)
                    <div class="card p-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                             style="background:{{ $enrollment->course->thumbnail_color ?? '#374151' }}">
                            {{ $enrollment->course->icon_text ?? 'C' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">COURSE</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $enrollment->course->title ?? 'Course' }}
                            </p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5 max-w-xs">
                                    <div class="bg-green-500 h-1.5 rounded-full transition-all duration-500"
                                         style="width:{{ $enrollment->progress ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600">{{ $enrollment->progress ?? 0 }}%</span>
                            </div>
                        </div>
                        <a href="{{ route('course.learn', $enrollment->course->slug ?? '#') }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold shrink-0"
                           style="background:#03EF62;color:#05192D">
                            {{ $tab === 'completed' ? 'Review' : 'Continue' }}
                        </a>
                    </div>
                    @endif
                @empty
                    <div class="card p-10 text-center">
                        <p class="text-gray-400 text-sm">No courses here yet.</p>
                        <a href="{{ route('courses') }}" class="mt-3 inline-block text-sm text-green-600 hover:underline">
                            Browse courses →
                        </a>
                    </div>
                @endforelse
            </div>

        {{-- ── Practice Tab ── --}}
        @else
            <p class="text-sm text-gray-500 mb-4">{{ $practiceCount }} attempts</p>

            <div class="space-y-3">
                @forelse($practiceSessions as $ps)
                <div class="card p-5 flex items-center gap-4">
                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 text-white font-bold text-sm"
                         style="background:#6366f1">
                        Q
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">
                            PRACTICE · {{ $ps->nama_topik }}
                        </p>
                        <p class="text-sm font-semibold text-gray-900">{{ $ps->nama_session }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            {{-- Score bar --}}
                            <div class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all duration-500
                                    {{ $ps->status === 'Finish' ? ($ps->skor >= 80 ? 'bg-green-500' : ($ps->skor >= 50 ? 'bg-yellow-400' : 'bg-red-400')) : 'bg-gray-300' }}"
                                     style="width:{{ $ps->status === 'Finish' ? $ps->skor : 0 }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-600">
                                {{ $ps->status === 'Finish' ? $ps->skor.'%' : '—' }}
                            </span>
                        </div>
                            <span class="text-xs text-gray-400">
                                Attempt #{{ $ps->attempt }}
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $ps->status === 'Finish' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $ps->status }}
                            </span>
                            @if($ps->create_at)
                            <span class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($ps->create_at)->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('practice.intro', $ps->session_id) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold shrink-0 border border-indigo-500 text-indigo-600 hover:bg-indigo-50">
                        Retry
                    </a>
                </div>
                @empty
                    <div class="card p-10 text-center">
                        <p class="text-gray-400 text-sm">No practice attempts yet.</p>
                        <a href="{{ route('practice.index') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                            Start practicing →
                        </a>
                    </div>
                @endforelse
            </div>
        @endif

    </main>
</div>
</body>
</html>