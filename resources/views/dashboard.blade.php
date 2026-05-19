<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn - DataCamp</title>
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
    <main class="flex-1 p-6 max-w-5xl">

        @if(session('message'))
        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-300 text-green-700 text-sm rounded-lg">
            ✓ {{ session('message') }}
        </div>
        @endif

        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-green-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Hey, {{ Auth::user()->name }}!</h1>
                    <p class="text-sm text-gray-500">{{ Auth::user()->xp ?? 0 }} XP</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 bg-white">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    <span class="text-sm text-gray-600">Review</span>
                    <span class="text-sm font-bold text-gray-900">0</span>
                </div>
                <div class="flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 bg-white">
                    <span class="text-yellow-500">⚡</span>
                    <span class="text-sm text-gray-600">Daily Streak</span>
                    <span class="text-sm font-bold text-gray-900">{{ Auth::user()->streak ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Enrolled Course --}}
        @php $enrollments = Auth::user()->enrollments()->with('course')->latest()->take(1)->get(); @endphp
        @if($enrollments->count())
        @foreach($enrollments as $enrollment)
        @if($enrollment->course)
        <div class="card mb-4 p-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold"
                     style="background:{{ $enrollment->course->thumbnail_color ?? '#1a1060' }}">
                    {{ $enrollment->course->icon_text ?? '?' }}
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-0.5">LEARN</p>
                    <a href="{{ route('course.learn', $enrollment->course->slug ?? '#') }}" class="text-base font-semibold text-gray-900 hover:text-green-600 flex items-center gap-1">
                        {{ $enrollment->course->title ?? 'Course' }} →
                    </a>
                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        {{ $enrollment->course->duration_hours ?? 0 }} hr to go
                    </div>
                </div>
            </div>
            <a href="{{ route('course.learn', $enrollment->course->slug ?? '#') }}"
               class="px-5 py-2.5 rounded-lg text-sm font-semibold" style="background:#03EF62;color:#05192D">
                Let's Do This
            </a>
        </div>
        @endif
        @endforeach
        @endif

        {{-- Quick Actions --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="card p-4 flex items-center gap-3 cursor-pointer hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">ASSESS</p>
                    <p class="text-sm font-semibold text-gray-900">Analytic Fundamentals</p>
                </div>
                <svg class="ml-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            </div>
            <div class="card p-4 flex items-center gap-3 cursor-pointer hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">PRACTICE</p>
                    <p class="text-sm font-semibold text-gray-900">Introduction to Python</p>
                </div>
                <svg class="ml-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            </div>
            <div class="card p-4 flex items-center gap-3 cursor-pointer hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ec4899" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">APPLY</p>
                    <p class="text-sm font-semibold text-gray-900">Real World Project</p>
                </div>
                <svg class="ml-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            </div>
        </div>

        {{-- Leaderboard & Recommended --}}
        <div class="grid grid-cols-3 gap-4">
            {{-- Leaderboard --}}
            <div class="card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">Leaderboard</h3>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded font-medium">NEW</span>
                </div>
                @php $topUsers = \App\Models\User::orderByDesc('xp')->take(5)->get(); @endphp
                @foreach($topUsers as $i => $u)
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <span class="text-xs font-bold w-4 {{ $i==0?'text-yellow-500':($i==1?'text-gray-400':($i==2?'text-orange-400':'text-gray-300')) }}">{{ $i+1 }}</span>
                    <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($u->name,0,1)) }}
                    </div>
                    <span class="flex-1 text-xs text-gray-700 {{ Auth::id()==$u->id?'font-semibold':'' }}">{{ $u->name }}</span>
                    <span class="text-xs text-gray-500">{{ $u->xp ?? 0 }} XP</span>
                </div>
                @endforeach
            </div>

            {{-- Recommended Courses --}}
            <div class="col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">Recommended for you</h3>
                    <a href="{{ route('courses') }}" class="text-xs text-green-600 hover:text-green-500">View all →</a>
                </div>
                <div class="space-y-3">
                    @php $featured = \App\Models\Course::where('is_featured', true)->take(3)->get(); @endphp
                    @foreach($featured as $course)
                    <a href="{{ route('course.detail', $course->slug) }}" class="card p-4 flex items-center gap-4 hover:shadow-md transition-shadow block">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0"
                             style="background:{{ $course->thumbnail_color ?? '#1a1060' }}">{{ $course->icon_text ?? '?' }}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $course->title ?? '' }}</p>
                            <p class="text-xs text-gray-500">{{ $course->difficulty ?? '' }} · {{ $course->duration_hours ?? 0 }}h · ★ {{ $course->rating ?? 0 }}</p>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>