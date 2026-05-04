<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{background:#0a0e1a}</style>
</head>
<body class="text-white min-h-screen">

<x-navbar />

{{-- HEADER --}}
<div class="px-6 pt-6 pb-4 border-b border-white/8 flex items-center justify-between">
    <div>
        <p class="text-sm text-white/40 mb-0.5">Selamat datang kembali,</p>
        <h1 class="text-2xl font-medium">{{ Auth::user()->name }} 👋</h1>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 bg-yellow-400/10 border border-yellow-400/20 px-3 py-1.5 rounded-full">
            <span class="text-yellow-400 text-sm">⚡</span>
            <span class="text-yellow-400 text-sm font-medium">{{ Auth::user()->streak }} Hari Streak</span>
        </div>
        <div class="flex items-center gap-2 bg-purple-400/10 border border-purple-400/20 px-3 py-1.5 rounded-full">
            <span class="text-purple-400 text-sm">✦</span>
            <span class="text-purple-400 text-sm font-medium">{{ Auth::user()->xp }} XP</span>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="grid grid-cols-4 gap-4 px-6 py-5">
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Total XP</p>
        <p class="text-2xl font-medium text-purple-400">{{ Auth::user()->xp }}</p>
        <p class="text-xs text-white/30 mt-1">poin terkumpul</p>
    </div>
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Streak Harian</p>
        <div class="flex items-end gap-1">
            <p class="text-2xl font-medium text-yellow-400">{{ Auth::user()->streak }}</p>
            <p class="text-sm text-white/40 mb-1">hari</p>
        </div>
        <p class="text-xs text-white/30 mt-1">berturut-turut</p>
    </div>
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Kursus Diikuti</p>
        <p class="text-2xl font-medium text-white">{{ Auth::user()->enrollments->count() }}</p>
        <p class="text-xs text-white/30 mt-1">kursus aktif</p>
    </div>
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Tools Disimpan</p>
        <p class="text-2xl font-medium text-white">{{ Auth::user()->savedTools->count() }}</p>
        <p class="text-xs text-white/30 mt-1">tools favorit</p>
    </div>
</div>

{{-- STREAK VISUAL --}}
<div class="px-6 mb-5">
    <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-medium">Aktivitas 7 Hari Terakhir</h3>
            <span class="text-xs text-yellow-400">⚡ {{ Auth::user()->streak }} hari streak</span>
        </div>
        <div class="flex gap-2">
            @for($i = 6; $i >= 0; $i--)
            @php
                $date = now()->subDays($i)->format('Y-m-d');
                $isActive = Auth::user()->last_activity && Auth::user()->last_activity->format('Y-m-d') >= $date && $i <= Auth::user()->streak;
                $day = now()->subDays($i)->format('D');
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5">
                <div class="w-full h-8 rounded-md {{ $isActive ? 'bg-yellow-400/30 border border-yellow-400/40' : 'bg-white/5' }}"></div>
                <span class="text-xs text-white/30">{{ $day }}</span>
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-6 px-6 pb-10">

    {{-- KIRI: Kursus --}}
    <div class="col-span-2 space-y-5">

        {{-- Lanjutkan belajar --}}
        @php $enrollments = Auth::user()->enrollments()->with('course')->latest()->take(3)->get(); @endphp
        @if($enrollments->count())
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium">Lanjutkan Belajar</h2>
                <a href="{{ route('courses') }}" class="text-xs text-green-400 hover:text-green-300">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @foreach($enrollments as $enrollment)
                <a href="{{ route('course.learn', $enrollment->course->slug) }}"
                   class="flex items-center gap-4 bg-gray-900 border border-white/10 rounded-xl p-4 hover:border-green-400/40 transition-colors">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-sm font-medium text-white shrink-0"
                         style="background:{{ $enrollment->course->thumbnail_color }}">
                        {{ $enrollment->course->icon_text }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white mb-1">{{ $enrollment->course->title }}</p>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-white/10 rounded-full h-1.5">
                                <div class="bg-green-400 h-1.5 rounded-full" style="width:{{ $enrollment->progress }}%"></div>
                            </div>
                            <span class="text-xs text-green-400 shrink-0">{{ $enrollment->progress }}%</span>
                        </div>
                    </div>
                    <div class="text-xs text-white/40 shrink-0">Lanjutkan →</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Rekomendasi kursus --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium">Rekomendasi Kursus</h2>
                <a href="{{ route('courses') }}" class="text-xs text-green-400 hover:text-green-300">Lihat semua →</a>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @php $featured = \App\Models\Course::where('is_featured', true)->take(4)->get(); @endphp
                @foreach($featured as $course)
                <a href="{{ route('course.detail', $course->slug) }}"
                   class="block bg-gray-900 border border-white/10 rounded-xl p-4 hover:border-green-400/40 transition-colors">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium text-white mb-3"
                         style="background:{{ $course->thumbnail_color }}">{{ $course->icon_text }}</div>
                    <h3 class="text-xs font-medium text-white mb-1 line-clamp-2">{{ $course->title }}</h3>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/8">
                        <span class="text-xs text-white/30">{{ $course->difficulty }}</span>
                        <span class="text-xs text-yellow-400">&#9733; {{ $course->rating }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KANAN --}}
    <div class="space-y-4">

        {{-- Profile card --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-green-400/20 border border-green-400/30 flex items-center justify-center text-green-400 text-lg font-medium">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/40">{{ Auth::user()->email }}</p>
                </div>
            </div>
            {{-- XP Progress --}}
            @php
                $currentXp = Auth::user()->xp;
                $nextLevel = 100;
                $levelName = 'Pemula';
                if($currentXp >= 500) { $nextLevel = 1000; $levelName = 'Expert'; }
                elseif($currentXp >= 100) { $nextLevel = 500; $levelName = 'Menengah'; }
                $progress = min(100, round(($currentXp % $nextLevel) / $nextLevel * 100));
            @endphp
            <div class="border-t border-white/8 pt-4">
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-white/40">Level: <span class="text-white">{{ $levelName }}</span></span>
                    <span class="text-purple-400">{{ $currentXp }} / {{ $nextLevel }} XP</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-1.5">
                    <div class="bg-purple-400 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs text-white/30 mt-2">{{ $nextLevel - $currentXp }} XP lagi untuk naik level</p>
            </div>
            <a href="/profile" class="block mt-4 text-center text-xs text-green-400 border border-green-400/30 rounded-lg py-2 hover:bg-green-400/10">
                Edit Profile →
            </a>
        </div>

        {{-- Papan peringkat --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium">Papan Peringkat</h3>
                <span class="text-xs text-white/30">Minggu ini</span>
            </div>
            @php $topUsers = \App\Models\User::orderByDesc('xp')->take(5)->get(); @endphp
            @foreach($topUsers as $i => $u)
            <div class="flex items-center gap-3 py-2 border-b border-white/5 last:border-0">
                <span class="text-xs font-medium w-4 {{ $i==0?'text-yellow-400':($i==1?'text-white/50':($i==2?'text-amber-600':'text-white/30')) }}">{{ $i+1 }}</span>
                <div class="w-6 h-6 rounded-full bg-green-400/20 flex items-center justify-center text-xs text-green-400">
                    {{ strtoupper(substr($u->name, 0, 1)) }}
                </div>
                <span class="flex-1 text-xs text-white/70 {{ Auth::id()==$u->id?'text-white font-medium':'' }}">{{ $u->name }}</span>
                <span class="text-xs text-purple-400">{{ $u->xp }} XP</span>
            </div>
            @endforeach
        </div>

        {{-- Kategori --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Kategori populer</h3>
            @foreach(['Framework','Multi-Agent','Memory','Planning','Monitoring'] as $cat)
            <a href="{{ route('katalog', ['category'=>$cat]) }}"
               class="flex items-center justify-between text-sm text-white/60 hover:text-white py-1.5 border-b border-white/5 last:border-0">
                {{ $cat }}<span class="text-xs text-white/30">→</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>