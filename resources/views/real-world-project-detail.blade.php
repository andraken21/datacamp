<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project['title'] }} - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background:#f8f9fa; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
        .chip { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:13px; border:1px solid #e0e0e0; background:white; color:#555; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">
    <x-sidebar />
    <main class="flex-1">

        {{-- HEADER --}}
        <div class="p-8" style="background:#05192D">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">PROJECT</p>
            <h1 class="text-2xl font-bold text-white mb-3">{{ $project['title'] }}</h1>
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center gap-1.5">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#03EF62" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-sm font-semibold" style="color:{{ $project['level_color'] }}">{{ $project['level'] }}</span>
                </div>
                <span class="text-gray-500 text-sm">|</span>
                <span class="text-gray-400 text-sm">Updated: {{ $project['updated'] }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ $project['url'] }}" target="_blank"
                   class="px-6 py-2 rounded-lg text-sm font-bold text-white" style="background:#03EF62; color:#05192D">
                    Start
                </a>
                <button class="flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold border border-gray-600 text-gray-300 hover:border-gray-400">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share
                </button>
            </div>
            {{-- Stats --}}
            <div class="flex items-center gap-6 mt-5">
                <div class="flex items-center gap-2 text-gray-400 text-sm">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                    </svg>
                    {{ $project['duration'] }}
                </div>
                <div class="flex items-center gap-2 text-gray-400 text-sm">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    {{ $project['exercises'] }} Exercises
                </div>
                <div class="flex items-center gap-2 text-gray-400 text-sm">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    {{ $project['participants'] }}
                </div>
                <div class="px-3 py-1 rounded-full text-xs font-bold text-white" style="background:#f59e0b">
                    {{ $project['xp'] }} XP
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="flex gap-8 p-8">
            {{-- LEFT: Description + Chapters --}}
            <div class="flex-1">
                {{-- Description --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h2 class="text-base font-bold text-gray-900 mb-3">Description</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $project['desc'] }}</p>
                </div>

                {{-- Chapters --}}
                @foreach($project['chapters'] as $i => $chapter)
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">{{ $i + 1 }}</span>
                            <h3 class="font-bold text-gray-900">{{ $chapter['title'] }}</h3>
                            @if($chapter['locked'])
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">Locked</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-32 h-1.5 bg-gray-200 rounded-full"><div class="h-1.5 bg-green-400 rounded-full w-0"></div></div>
                            <span class="text-xs text-gray-400">0%</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4 ml-10">Put your skills to the test with real-world scenarios.</p>
                    <div class="ml-10 space-y-2">
                        @foreach($chapter['exercises'] as $ex)
                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-blue-100 flex items-center justify-center">
                                    <svg width="8" height="8" viewBox="0 0 24 24" fill="#3b82f6"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                                </div>
                                <span class="text-sm text-gray-700">{{ $ex }}</span>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">{{ $chapter['xp_each'] }} XP</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 ml-10">
                        <a href="{{ $project['url'] }}" target="_blank"
                           class="px-5 py-2 rounded-lg text-sm font-bold text-white" style="background:#03EF62; color:#05192D">
                            Start Chapter
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- RIGHT: Sidebar --}}
            <div class="w-64 flex-shrink-0 space-y-4">
                {{-- Share --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3 flex items-center gap-2">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share
                    </h3>
                    <div class="flex items-center gap-2">
                        <button class="flex-1 py-2 rounded-lg text-xs font-bold text-white" style="background:#0A66C2">LinkedIn</button>
                        <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </button>
                        <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="black"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.74l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Prerequisites --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Prerequisites</h3>
                    @if(empty($project['prerequisites']))
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#03EF62"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            There are no prerequisites
                        </div>
                    @else
                        @foreach($project['prerequisites'] as $pre)
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                            {{ $pre }}
                        </div>
                        @endforeach
                    @endif
                </div>

                {{-- Instructors --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Instructors</h3>
                    @foreach($project['instructors'] as $ins)
                    <div class="mb-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($ins['name'], 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $ins['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $ins['role'] }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $ins['bio'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="px-8 pb-8">
            <a href="{{ route('real-world-projects') }}" class="text-sm text-green-600 hover:underline flex items-center gap-1">
                ← Back to Real World Projects
            </a>
        </div>
    </main>
</div>
</body>
</html>