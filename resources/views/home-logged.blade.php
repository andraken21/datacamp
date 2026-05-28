<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .card { background: white; border: 1px solid #e8e8e8; border-radius: 12px; }
        .card:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
<x-navbar />

{{-- PROMO BANNER --}}
<div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white py-3 px-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="bg-white rounded-lg px-3 py-1.5 flex items-center gap-2">
            <span class="text-purple-600 font-bold text-xs">Learn</span>
            <span class="text-xs text-gray-500">Data and AI</span>
        </div>
        <p class="text-sm font-medium">Last chance! <span class="text-yellow-300 font-bold">50% off</span> DataCamp Premium</p>
        <div class="bg-white/20 rounded px-3 py-1 text-sm font-mono">4d 03h 02m 17s</div>
    </div>
    <button class="bg-green-400 text-gray-900 font-semibold text-sm px-4 py-1.5 rounded-lg hover:bg-green-300 flex items-center gap-1">
        Buy Now →
    </button>
</div>

<div class="max-w-7xl mx-auto px-6 py-6 grid grid-cols-3 gap-6">

    {{-- MAIN CONTENT (col-span-2) --}}
    <div class="col-span-2 space-y-5">

        {{-- Learn Section --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-900 font-semibold hover:text-green-600">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    Learn →
                </a>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    Basic •
                    <a href="{{ route('harga') }}" class="text-purple-600 font-medium hover:text-purple-700">Upgrade</a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Current Course (dari $enrollment yang dikirim controller) --}}
                @if($enrollment && $enrollment->course)
                <div class="rounded-xl overflow-hidden relative" style="background: linear-gradient(135deg, {{ $enrollment->course->thumbnail_color ?? '#1a1060' }}, #05192D); min-height: 140px;">
                    <div class="p-5 h-full flex flex-col justify-between">
                        <div>
                            <p class="text-white/60 text-xs uppercase tracking-wide font-medium mb-2">COURSE</p>
                            <h3 class="text-white font-semibold text-base leading-tight">{{ $enrollment->course->title }}</h3>
                        </div>
                        <a href="{{ route('course.learn', $enrollment->course->slug) }}"
                           class="self-start mt-3 bg-green-400 text-gray-900 text-sm font-semibold px-4 py-1.5 rounded-lg hover:bg-green-300">
                            Continue
                        </a>
                    </div>
                </div>
                @else
                <div class="rounded-xl overflow-hidden relative" style="background: linear-gradient(135deg, #1a1060, #05192D); min-height: 140px;">
                    <div class="p-5 h-full flex flex-col justify-between">
                        <div>
                            <p class="text-white/60 text-xs uppercase tracking-wide font-medium mb-2">COURSE</p>
                            <h3 class="text-white font-semibold text-base leading-tight">Start your first course!</h3>
                        </div>
                        <a href="{{ route('courses') }}"
                           class="self-start mt-3 bg-green-400 text-gray-900 text-sm font-semibold px-4 py-1.5 rounded-lg hover:bg-green-300">
                            Browse Courses
                        </a>
                    </div>
                </div>
                @endif

                {{-- Enrolled Track (dari $enrolledTrack yang dikirim controller) --}}
                <div class="card p-5 flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2">ENROLLED TRACK</p>
                        @if($enrolledTrack)
                            <h3 class="text-gray-900 font-semibold text-base leading-tight mb-1">{{ $enrolledTrack->title }}</h3>
                        @else
                            <h3 class="text-gray-900 font-semibold text-base leading-tight mb-1">No track enrolled yet</h3>
                        @endif
                    </div>
                    @if($enrolledTrack)
                        <a href="{{ route('tracks.show', $enrolledTrack->slug) }}" class="text-green-600 text-sm font-medium hover:text-green-500 flex items-center gap-1">
                            See track →
                        </a>
                    @else
                        <a href="{{ route('tracks.career') }}" class="text-green-600 text-sm font-medium hover:text-green-500 flex items-center gap-1">
                            Browse Tracks →
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- DataLab Section --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <a href="#" class="flex items-center gap-2 text-gray-900 font-semibold hover:text-green-600">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    DataLab ↗
                </a>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    Starter •
                    <a href="{{ route('harga') }}" class="text-purple-600 font-medium">Upgrade</a>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-900 font-semibold mb-1">Meet DataLab</h3>
                        <p class="text-sm text-gray-500 max-w-lg">An AI-powered cloud notebook for Python, R, and SQL. Analyze data, visualize results, and share reports — all from your browser.</p>
                    </div>
                    <a href="{{ route('practice.index') }}" class="border border-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg hover:border-gray-400 shrink-0 ml-4">
                        Create Workbook
                    </a>
                </div>
            </div>
        </div>

        {{-- Sandbox Section --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <a href="{{ route('practice.index') }}" class="flex items-center gap-2 text-gray-900 font-semibold hover:text-green-600">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Sandbox →
                </a>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-5">
                    {{-- Token circle (hardcode 1000 karena fitur sandbox belum ada di DB) --}}
                    <div class="w-16 h-16 relative shrink-0">
                        <svg viewBox="0 0 64 64" width="64" height="64">
                            <circle cx="32" cy="32" r="28" fill="none" stroke="#e5e7eb" stroke-width="6"/>
                            <circle cx="32" cy="32" r="28" fill="none" stroke="#EAB308" stroke-width="6" stroke-dasharray="175.9" stroke-dashoffset="0" stroke-linecap="round"/>
                            <text x="32" y="36" text-anchor="middle" font-size="12" font-weight="bold" fill="#374151">1,000</text>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900 font-semibold mb-1">You have 1000 unused tokens to practice your skills!</p>
                        <p class="text-sm text-gray-500 mb-3">Step into Sandbox that provides a simple, low-risk environment for practicing BI, Cloud, Data Warehouse, Business Intelligence, and AI tools.</p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach($sandboxTools as $tool)
                            <a href="{{ $tool->url ?? route('katalog') }}" target="_blank"
                                class="border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 hover:bg-gray-50">
                                {{ $tool->nama_sandbox }}
                            </a>
                            @endforeach
                            <a href="{{ route('katalog') }}" class="border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 cursor-pointer hover:bg-gray-50">View All →</a>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 shrink-0">1 min = 30 tokens</p>
                </div>
            </div>
        </div>

        {{-- Certification Section --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <a href="{{ route('certification.index') }}" class="flex items-center gap-2 text-gray-900 font-semibold hover:text-green-600">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                    Certification →
                </a>
            </div>
            <div class="card p-5 border-dashed border-2 border-gray-200">
                <div class="flex items-center gap-5">
                    <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><rect width="40" height="40" rx="6" fill="#374151"/><text x="20" y="26" text-anchor="middle" font-size="10" fill="white" font-weight="bold">DATA</text></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-gray-900 font-semibold mb-1">You're missing out!</h3>
                        <p class="text-sm text-gray-500 mb-3">Improve your chances of getting hired with an industry recognized DataCamp Certification.</p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['Data Engineer','AI Engineer for Devel...','Python Data Associate'] as $cert)
                            <span class="border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-lg flex items-center gap-1 cursor-pointer hover:bg-gray-50">
                                🔒 {{ $cert }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('certification.index') }}" class="border border-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg hover:border-gray-400 shrink-0">
                        See All
                    </a>
                </div>
            </div>
        </div>

        {{-- Mobile App --}}
        <div class="card p-5 flex items-center gap-5">
            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center shrink-0 text-2xl">📱</div>
            <div class="flex-1">
                <h3 class="text-gray-900 font-semibold mb-1">Grow your data skills with DataCamp for Mobile</h3>
                <p class="text-sm text-gray-500 mb-3">Make progress on the go with our mobile courses and daily 5-minute coding challenges.</p>
                <div class="flex gap-2">
                    <a href="#" class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-1.5">
                        🍎 Download on the App Store
                    </a>
                    <a href="#" class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-1.5">
                        ▶ GET IT ON Google Play
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div class="space-y-4">

        {{-- My Activity --}}
        <div>
            <a href="{{ route('my-activity') }}" class="flex items-center gap-2 text-gray-900 font-semibold mb-3 hover:text-green-600">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                My Activity →
            </a>
            <div class="card p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-gray-900 font-semibold">Hey!</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->xp ?? 0 }} XP</p>
                    </div>
                </div>
                <div class="border-t border-gray-100 mt-4 pt-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Daily Streak</p>
                        <div class="flex items-center gap-1.5">
                            <span class="text-yellow-500">⚡</span>
                            <span class="font-bold text-gray-900">{{ Auth::user()->streak ?? 0 }} days</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Total XP</p>
                        <div class="flex items-center gap-1.5">
                            <span class="text-purple-500">✦</span>
                            <span class="font-bold text-gray-900">{{ Auth::user()->xp ?? 0 }} XP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leaderboard --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <a href="{{ route('leaderboard') }}" class="flex items-center gap-2 text-gray-900 font-semibold hover:text-green-600">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Leaderboard →
                </a>
                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded font-medium">10 HOURS LEFT TO JOIN</span>
            </div>
            <div class="card p-5">
                <div class="flex flex-col items-center mb-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4L28 16L16 28L4 16Z" fill="#d1d5db"/></svg>
                    </div>
                    <p class="text-sm text-gray-600 text-center">Gain <span class="font-semibold text-gray-900">250XP</span> to enter this week's Bit League</p>
                </div>

                {{-- XP Progress Bar (dari $xpPercent yang dikirim controller) --}}
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width:{{ $xpPercent }}%"></div>
                </div>
                <p class="text-xs text-gray-500 text-center">{{ Auth::user()->xp ?? 0 }} / 250 XP</p>

                {{-- Top Users (dari $topUsers yang dikirim controller) --}}
                <div class="border-t border-gray-100 mt-4 pt-4 space-y-2">
                    @foreach($topUsers as $i => $u)
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold w-4 text-center {{ $i==0?'text-yellow-500':($i==1?'text-gray-400':($i==2?'text-orange-400':'text-gray-300')) }}">{{ $i+1 }}</span>
                        <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <span class="flex-1 text-xs text-gray-700 {{Auth::id() == $u->user_id ? 'font-semibold' : '' }}">
                            {{ $u->name }} {{ Auth::id()==$u->user_id ? '(You)' : '' }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $u->xp ?? 0 }} XP</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>