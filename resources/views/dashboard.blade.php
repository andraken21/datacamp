<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCamp Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #0d0d1a; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }

        /* Card hover */
        .hover-card:hover { border-color: rgba(3,239,98,0.35) !important; }
        .hover-card { transition: border-color 0.15s ease; }

        /* Section headers */
        .section-title { font-size: 15px; font-weight: 600; color: #fff; }
        .section-link  { font-size: 12px; color: rgba(255,255,255,0.5); }
        .section-link:hover { color: #fff; }

        /* XP progress bar */
        .xp-bar { background: #1e293b; border-radius: 999px; height: 6px; }
        .xp-fill { background: #03ef62; border-radius: 999px; height: 6px; transition: width 0.6s ease; }

        /* Leaderboard rank colors */
        .rank-1 { color: #fabd00; }
        .rank-2 { color: rgba(255,255,255,0.5); }
        .rank-3 { color: #cd7f32; }

        /* Certification lock badge */
        .cert-badge { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; font-size: 12px; color: rgba(255,255,255,0.7); cursor: pointer; }
        .cert-badge:hover { background: rgba(255,255,255,0.08); }

        /* Sandbox token ring */
        @keyframes spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
    </style>
</head>
<body class="text-white min-h-screen">

{{-- ===== NAVBAR ===== --}}
<x-navbar />

{{-- ===== PAGE CONTENT ===== --}}
<div class="max-w-[1280px] mx-auto px-6 py-7">

    {{-- ============================================================
         ROW 1: Learn + My Activity
    ============================================================ --}}
    <div class="grid grid-cols-[1fr_320px] gap-5 mb-6">

        {{-- LEFT: Learn Section --}}
        <div>
            {{-- Section Header --}}
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"/>
                </svg>
                <a href="{{ route('courses') }}" class="section-title flex items-center gap-1 hover:text-[#03ef62] transition-colors">
                    Learn
                    <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <div class="ml-auto flex items-center gap-2 text-xs text-white/40">
                    <span>Basic</span>
                    <span>•</span>
                    <a href="/harga" class="text-[#03ef62] hover:underline">Upgrade</a>
                </div>
            </div>

            {{-- Continue Course Card --}}
            @php $lastEnrollment = Auth::user()->enrollments()->with('course')->latest()->first(); @endphp
            <div class="grid grid-cols-[1fr_1fr] gap-4">

                {{-- Continue Card (dark blue gradient) --}}
                <div class="rounded-xl overflow-hidden relative"
                     style="background: linear-gradient(135deg, #1e2d5a 0%, #0f1729 100%); min-height:148px;">
                    <div class="p-5 h-full flex flex-col justify-between">
                        <div>
                            <p class="text-[10px] font-semibold text-white/50 uppercase tracking-widest mb-2">COURSE</p>
                            <h2 class="text-[17px] font-bold text-white leading-snug mb-4">
                                {{ $lastEnrollment ? $lastEnrollment->course->title : 'Introduction to AI for Work' }}
                            </h2>
                        </div>
                        <a href="{{ $lastEnrollment ? route('course.learn', $lastEnrollment->course->slug) : route('courses') }}"
                           class="inline-flex items-center gap-2 bg-[#03ef62] hover:bg-[#00d455] text-gray-900 font-semibold text-sm px-4 py-2 rounded-lg self-start transition-colors">
                            Continue
                        </a>
                    </div>
                    {{-- Decorative bg element --}}
                    <div class="absolute right-0 bottom-0 w-24 h-24 opacity-10" style="background: radial-gradient(circle, #03ef62, transparent 70%);"></div>
                </div>

                {{-- Enrolled Track Card --}}
                @php $track = null; /* Replace with actual track logic */ @endphp
                <div class="bg-[#13131f] border border-white/8 rounded-xl p-5 flex flex-col justify-between hover-card">
                    <div>
                        <p class="text-[10px] font-semibold text-white/40 uppercase tracking-widest mb-2">ENROLLED TRACK</p>
                        <h3 class="text-[15px] font-semibold text-white leading-snug mb-3">Associate Data Analyst in SQL</h3>
                    </div>
                    <a href="{{ route('courses') }}" class="text-sm text-[#03ef62] hover:underline flex items-center gap-1">
                        See track
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT: My Activity --}}
        <div>
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="section-title flex items-center gap-1">
                    My Activity
                    <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>

            <div class="bg-[#13131f] border border-white/8 rounded-xl p-5 flex items-center gap-5">
                {{-- Avatar --}}
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#03ef62]/30 to-purple-600/40 border border-white/10 flex items-center justify-center text-xl font-bold text-white shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                {{-- Stats --}}
                <div class="flex-1 space-y-3">
                    <p class="text-sm font-medium text-white">Hey!</p>

                    {{-- Daily Streak --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-white/8 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white/40" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm1 11H9V9h2v4zm0-6H9V5h2v2z"/></svg>
                            </div>
                            <span class="text-xs text-white/50">Daily Streak</span>
                        </div>
                        <span class="text-xs font-semibold text-white">{{ Auth::user()->streak }} days</span>
                    </div>

                    {{-- Total XP --}}
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-white/50">Total XP</span>
                        <span class="text-xs font-semibold text-white">{{ Auth::user()->xp }} XP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 2: DataLab + Leaderboard
    ============================================================ --}}
    <div class="grid grid-cols-[1fr_320px] gap-5 mb-6">

        {{-- LEFT: DataLab / Sandbox --}}
        <div>
            <div class="flex items-center gap-2 mb-3">
                {{-- DataLab icon --}}
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <span class="section-title flex items-center gap-1">
                    DataLab
                    <svg class="w-3 h-3 text-white/40 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </span>
                <div class="ml-auto flex items-center gap-2 text-xs text-white/40">
                    <span>Starter</span>
                    <span>•</span>
                    <a href="/harga" class="text-[#03ef62] hover:underline">Upgrade</a>
                </div>
            </div>

            <div class="bg-[#13131f] border border-white/8 rounded-xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-[15px] font-semibold text-white mb-1.5">Meet DataLab</h3>
                        <p class="text-[12px] text-white/45 leading-relaxed">
                            An AI-powered cloud notebook for Python, R, and SQL. Analyze data, visualize results, and share reports — all from your browser.
                        </p>
                    </div>
                    <a href="#" class="shrink-0 mt-0.5 border border-white/15 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-white/5 transition-colors whitespace-nowrap">
                        Create Workbook
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT: Leaderboard --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="section-title flex items-center gap-1">
                        Leaderboard
                        <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
                <span class="text-[11px] font-semibold text-[#03ef62] bg-[#03ef62]/10 px-2.5 py-1 rounded-full">2 DAYS LEFT TO JOIN</span>
            </div>

            <div class="bg-[#13131f] border border-white/8 rounded-xl p-5">
                {{-- Trophy icon + description --}}
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="w-16 h-16 rounded-2xl bg-[#1a1a2e] border border-white/8 flex items-center justify-center mb-3"
                         style="background: linear-gradient(135deg, #2a2040, #1a1030);">
                        <svg class="w-8 h-8 text-[#c9a84c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3h14M5 3v6a7 7 0 0014 0V3M5 3H3m16 0h2M9 21h6m-6 0v-3m6 3v-3m-6 0a9 9 0 01-3-17.9M15 21a9 9 0 003-17.9"/>
                        </svg>
                    </div>
                    <p class="text-[13px] font-semibold text-white">Gain 250XP to enter this week's</p>
                    <p class="text-[13px] font-semibold text-white">Bit League</p>
                </div>

                {{-- XP Progress --}}
                <div class="xp-bar mb-1.5">
                    @php $xpProgress = min(100, round(Auth::user()->xp / 250 * 100)); @endphp
                    <div class="xp-fill" style="width: {{ $xpProgress }}%"></div>
                </div>
                <div class="flex justify-end">
                    <span class="text-[11px] text-white/40">{{ Auth::user()->xp }} / 250 XP</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 3: Sandbox
    ============================================================ --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <a href="#" class="section-title flex items-center gap-1 hover:text-[#03ef62] transition-colors">
                Sandbox
                <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="bg-[#13131f] border border-white/8 rounded-xl p-5">
            <div class="flex items-center gap-6">

                {{-- Token Ring --}}
                <div class="shrink-0 flex flex-col items-center gap-1.5">
                    <div class="relative w-16 h-16">
                        <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                            <circle cx="32" cy="32" r="28" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="5"/>
                            <circle cx="32" cy="32" r="28" fill="none" stroke="#fabd00" stroke-width="5"
                                    stroke-dasharray="{{ 2 * 3.14159 * 28 }}" stroke-dashoffset="0"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-[13px] font-bold text-white leading-none">1,000</span>
                            <span class="text-[9px] text-white/40">/1,000</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-white/35">1 min = 30 tokens</p>
                </div>

                {{-- Text --}}
                <div class="flex-1">
                    <h3 class="text-[14px] font-semibold text-white mb-1">You have 1000 unused tokens to practice your skills!</h3>
                    <p class="text-[12px] text-white/45 leading-relaxed mb-4">
                        Step into Sandbox that provides a simple, low-risk environment for practicing BI, Cloud, Data Warehouse, Business Intelligence, and AI tools without the complexity of setting up.
                    </p>

                    {{-- Tool Buttons --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="#" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded-lg text-[12px] text-white/70 transition-colors">
                            <span class="text-yellow-400">⬡</span> Power BI
                        </a>
                        <a href="#" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded-lg text-[12px] text-white/70 transition-colors">
                            <span class="text-green-400">◎</span> OpenAI with Python
                        </a>
                        <a href="#" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded-lg text-[12px] text-white/70 transition-colors">
                            <span class="text-orange-400">☁</span> AWS
                        </a>
                        <a href="#" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded-lg text-[12px] text-white/70 transition-colors">
                            View All
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 4: Certification
    ============================================================ --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <a href="#" class="section-title flex items-center gap-1 hover:text-[#03ef62] transition-colors">
                Certification
                <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="bg-[#13131f] border border-white/8 rounded-xl p-5 border-dashed">
            <div class="flex items-center gap-5">

                {{-- Certificate mock image --}}
                <div class="shrink-0 w-[100px] h-[80px] bg-[#1a1a2e] border border-white/10 rounded-lg flex flex-col items-center justify-center gap-1 relative overflow-hidden">
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">DATA SCIENTIST</p>
                        <div class="w-6 h-6 mt-1 text-white/20">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Text + Certs --}}
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-[14px] font-semibold text-white mb-1">You're missing out!</h3>
                            <p class="text-[12px] text-white/45 leading-relaxed">
                                Improve your chances of getting hired with an industry recognized DataCamp Certification.
                            </p>
                        </div>
                        <a href="#" class="shrink-0 border border-white/15 text-white text-sm font-medium px-4 py-1.5 rounded-lg hover:bg-white/5 transition-colors ml-4 whitespace-nowrap">
                            See All
                        </a>
                    </div>

                    {{-- Cert badges --}}
                    <div class="flex items-center gap-2 mt-3 flex-wrap">
                        @foreach(['Data Engineer', 'AI Engineer for Devel...', 'Python Data Associate'] as $cert)
                        <div class="cert-badge">
                            <svg class="w-3.5 h-3.5 text-white/30 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $cert }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 5: Mobile App Banner
    ============================================================ --}}
    <div class="bg-[#13131f] border border-white/8 rounded-xl p-6 flex items-center gap-6">
        {{-- QR Code placeholder --}}
        <div class="shrink-0 w-[90px] h-[90px] bg-white rounded-lg flex items-center justify-center">
            <div class="grid grid-cols-3 gap-0.5 w-16 h-16">
                @for($r = 0; $r < 9; $r++)
                <div class="rounded-sm {{ in_array($r, [0,2,4,6,8]) ? 'bg-black' : 'bg-gray-200' }} w-full aspect-square"></div>
                @endfor
            </div>
        </div>

        {{-- Text --}}
        <div class="flex-1">
            <h3 class="text-[15px] font-semibold text-white mb-1">Grow your data skills with DataCamp for Mobile</h3>
            <p class="text-[12px] text-white/45 mb-4">Make progress on the go with our mobile courses and daily 5-minute coding challenges.</p>

            <div class="flex items-center gap-3">
                {{-- App Store --}}
                <a href="#" class="flex items-center gap-2 bg-black border border-white/15 px-3.5 py-2 rounded-xl hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[9px] text-white/50 leading-none">Download on the</p>
                        <p class="text-[12px] font-semibold text-white leading-tight">App Store</p>
                    </div>
                </a>

                {{-- Google Play --}}
                <a href="#" class="flex items-center gap-2 bg-black border border-white/15 px-3.5 py-2 rounded-xl hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M3.18 23.76c.37.2.8.2 1.18-.02L16.95 12 12.7 7.75 3.18 23.76z" fill="#EA4335"/>
                        <path d="M20.82 10.37L17.6 8.54l-4.23 4.23 4.23 4.23 3.22-1.83c.92-.52.92-1.85 0-2.37-.31-.18-.62-.35-.93-.5l.93.5z" fill="#FBBC04"/>
                        <path d="M3.18.24C2.8.44 2.5.85 2.5 1.35v21.3c0 .5.3.91.68 1.11L13.28 12 3.18.24z" fill="#4285F4"/>
                        <path d="M4.36 23.74L16.95 12 4.36.26c-.37-.2-.8-.2-1.18.02L4.36 23.74z" fill="#34A853"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[9px] text-white/50 leading-none">GET IT ON</p>
                        <p class="text-[12px] font-semibold text-white leading-tight">Google Play</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>{{-- end max-w wrapper --}}

</body>
</html>