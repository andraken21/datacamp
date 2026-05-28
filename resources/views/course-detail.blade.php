<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{background:#0a0e1a}</style>
</head>
<body class="text-white min-h-screen">

<x-navbar />

{{-- HERO --}}
<div class="border-b border-white/8" style="background: linear-gradient(135deg, {{ $course->thumbnail_color }}, #0a0e1a)">
    <div class="max-w-5xl mx-auto px-6 py-12">

        <p class="text-xs text-white/40 uppercase tracking-widest mb-4">INTERACTIVE COURSE</p>

        <h1 class="text-4xl font-semibold mb-4">{{ $course->title }}</h1>

        @php $namaLevel = $course->level->nama_level ?? '-'; @endphp
        <div class="flex items-center gap-3 mb-5 text-sm text-white/60">
            <span class="flex items-center gap-1">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                {{ $namaLevel }}
            </span>
            <span>·</span>
            <span>Updated: {{ \Carbon\Carbon::parse($course->updated_at)->format('F Y') }}</span>
        </div>

        <div class="flex items-center gap-3 mb-7">
            @if($isEnrolled)
            <a href="{{ route('course.learn', $course->slug) }}"
               class="px-6 py-2.5 rounded-lg font-semibold text-sm text-gray-900 hover:opacity-90"
               style="background:#03EF62">
                {{ $enrollment->progress > 0 ? 'Continue the Course?' : 'Start to Learn' }}
            </a>
            @else
            <form method="POST" action="{{ route('course.enroll', $course->course_id) }}" class="inline">
                @csrf
                <button type="submit"
                    class="px-6 py-2.5 rounded-lg font-semibold text-sm text-gray-900 hover:opacity-90"
                    style="background:#03EF62">
                    {{ $course->is_free ? 'Mulai Gratis' : 'Daftar Kursus' }}
                </button>
            </form>
            @endif

            <button class="flex items-center justify-center w-10 h-10 rounded-lg border border-white/20 text-white/70 hover:border-white/40 hover:text-white transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
            </button>
        </div>

        <div class="flex items-center gap-6 text-sm text-white/55">
            <span class="flex items-center gap-1.5">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ $course->duration_hours }} hour
            </span>
            <span class="flex items-center gap-1.5">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                {{ $course->total_lessons }} videos
            </span>
            <span class="flex items-center gap-1.5">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                {{ $course->total_lessons * 3 }} Exercises
            </span>
            <span class="flex items-center gap-1.5">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                {{ number_format($course->students_count) }}+
            </span>
            <span class="flex items-center gap-1.5 bg-yellow-400/20 text-yellow-300 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                {{ $course->xp ?? 3900 }} XP
            </span>
        </div>
    </div>
</div>

{{-- KONTEN --}}
<div class="max-w-5xl mx-auto px-6 py-8 grid grid-cols-3 gap-8">

    {{-- KIRI (col-span-2) --}}
    <div class="col-span-2 space-y-6">

        {{-- What you'll learn --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl overflow-hidden">
            <details open>
                <summary class="flex items-center justify-between px-6 py-4 cursor-pointer list-none">
                    <div class="flex items-center gap-2 text-base font-medium">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        What you will learn
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
                </summary>
                <div class="px-6 pb-5 border-t border-white/5 pt-4">
                    <p class="text-sm text-white/55 leading-relaxed">{{ $course->description }}</p>
                </div>
            </details>
        </div>

        {{-- Description --}}
        <div>
            <h2 class="text-base font-medium mb-3">Description</h2>
            <p class="text-sm text-white/55 leading-relaxed">{{ $course->description }}</p>
        </div>

    </div>
    {{-- END KIRI --}}

    {{-- SIDEBAR KANAN --}}
    <div class="space-y-4">

        {{-- Share --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-xs font-semibold text-white/50 uppercase tracking-widest mb-3 flex items-center gap-2">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                SHARE
            </h3>
            <div class="flex gap-2">
                <button class="flex items-center gap-2 flex-1 justify-center py-2 rounded-lg text-xs font-medium bg-blue-600 hover:bg-blue-500 text-white transition-colors">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    LinkedIn
                </button>
                <button class="flex items-center justify-center w-9 h-9 rounded-lg border border-white/15 text-white/50 hover:text-white hover:border-white/30 transition-colors">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
                <button class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-500 hover:bg-blue-400 text-white transition-colors">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </button>
                <button class="flex items-center justify-center w-9 h-9 rounded-lg bg-black hover:bg-gray-800 text-white border border-white/10 transition-colors">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </button>
            </div>
        </div>

        {{-- Prerequisites --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-xs font-semibold text-white/50 uppercase tracking-widest mb-3">PREREQUISITES</h3>
            <div class="flex items-center gap-2 text-sm text-white/60">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#03EF62" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                There are no prerequisites
            </div>
        </div>

        {{-- Instruktur --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Instruktur</h3>
            @if($course->instruktur)
            <div class="flex items-center gap-3">
                <img src="{{ $course->instruktur->url_foto }}" class="w-10 h-10 rounded-full object-cover"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($course->instruktur->nama_instruktur) }}&background=1a1060&color=fff'">
                <div>
                    <p class="text-sm text-white/80">{{ $course->instruktur->nama_instruktur }}</p>
                    <p class="text-xs text-white/40">{{ $course->instruktur->jabatan }}</p>
                </div>
            </div>
            @else
            <p class="text-sm text-white/40">DataCamp Expert</p>
            @endif
        </div>

        {{-- Kursus Serupa --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Kursus serupa</h3>
            @php
                $similar = \App\Models\Course::with('level')
                    ->where('topik_id', $course->topik_id)
                    ->where('course_id', '!=', $course->course_id)
                    ->take(3)
                    ->get();
            @endphp
            @forelse($similar as $s)
            <a href="{{ route('course.detail', $s->slug) }}"
               class="flex gap-3 py-2.5 border-b border-white/5 last:border-0 hover:text-green-400 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium text-white shrink-0"
                     style="background:{{ $s->thumbnail_color }}">{{ $s->icon_text }}</div>
                <div>
                    <p class="text-xs text-white/70 group-hover:text-green-400 line-clamp-2">{{ $s->title }}</p>
                    <p class="text-xs text-white/30 mt-0.5">
                        &#9733; {{ $s->rating }}
                        &nbsp;·&nbsp;
                        {{ $s->level->nama_level ?? '-' }}
                    </p>
                </div>
            </a>
            @empty
            <p class="text-xs text-white/30">Tidak ada kursus serupa.</p>
            @endforelse
        </div>

    </div>
    {{-- END SIDEBAR --}}

</div>

</body>
</html>