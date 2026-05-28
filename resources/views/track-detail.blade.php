<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $track->nama_track }} - DataCamp</title>
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
    <x-sidebar />

    <main class="flex-1">
        {{-- Hero --}}
        <div class="p-10" style="background:#05192D">
            <div class="max-w-4xl">
                <div class="flex items-center gap-2 mb-3">
                    <a href="{{ route('tracks.career') }}" class="text-xs text-white/50 hover:text-white">Career Tracks</a>
                    <span class="text-white/30">›</span>
                    <span class="text-xs text-white/70">{{ $track->nama_track }}</span>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full mb-4 inline-block" style="background:#03EF62;color:#05192D">
                    {{ $track->jenis_track }}
                </span>
                <h1 class="text-3xl font-bold text-white mt-3 mb-4">{{ $track->nama_track }}</h1>
                <p class="text-white/60 text-sm leading-relaxed max-w-2xl mb-6">{{ $track->deskripsi }}</p>

                <div class="flex items-center gap-6 text-sm text-white/50 mb-8">
                    <span class="flex items-center gap-1">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        {{ $track->durasi_jam ?? 0 }} hours
                    </span>
                    <span>{{ $track->total_kursus ?? 0 }} Courses</span>
                    <span>{{ $track->total_proyek ?? 0 }} Projects</span>
                    <span>{{ $track->total_asesmen ?? 0 }} Assessments</span>
                    @if($track->total_peserta)
                    <span>{{ number_format($track->total_peserta) }} learners</span>
                    @endif
                </div>

                @if($track->teknologi)
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach(explode(',', $track->teknologi) as $tech)
                    <span class="text-xs px-3 py-1 rounded-full border border-white/20 text-white/60">{{ trim($tech) }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Content --}}
        <div class="max-w-4xl mx-auto p-8">

            {{-- Stats bar --}}
            <div class="card p-5 mb-8 grid grid-cols-4 divide-x divide-gray-100">
                <div class="text-center px-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $track->total_kursus ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Courses</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $track->total_proyek ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Projects</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $track->durasi_jam ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Hours</p>
                </div>
                <div class="text-center px-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $track->total_asesmen ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Assessments</p>
                </div>
            </div>

            {{-- Courses in this track --}}
            @if($track->courses && $track->courses->count() > 0)
            <h2 class="text-lg font-bold text-gray-900 mb-4">Courses in this track</h2>
            <div class="space-y-3 mb-8">
                @foreach($track->courses as $index => $course)
<div class="card mb-3">
    <div class="p-4 flex items-center gap-4 cursor-pointer" onclick="toggleCourse({{ $index }})">
        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
            {{ $index + 1 }}
        </div>
        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0"
             style="background:{{ $course->thumbnail_color ?? '#1a1060' }}">
            {{ $course->icon_text ?? 'C' }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900">{{ $course->title ?? $course->nama_course }}</p>
            <p class="text-xs text-gray-500">{{ $course->difficulty ?? 'Pemula' }} · {{ $course->durasi ?? '0h' }}</p>
        </div>
        <svg id="arrow-{{ $index }}" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2" class="transition-transform"><path d="M6 9l6 6 6-6"/></svg>
    </div>
    <div id="course-detail-{{ $index }}" class="hidden border-t border-gray-100 px-4 py-4">
        <p class="text-sm text-gray-600 mb-4">{{ $course->deskripsi ?? $course->description }}</p>
        <a href="{{ route('course.detail', $course->slug) }}"
           class="inline-block text-sm font-medium text-green-600 hover:text-green-500">
            View Course →
        </a>
    </div>
</div>
@endforeach

<script>
function toggleCourse(index) {
    const detail = document.getElementById('course-detail-' + index);
    const arrow = document.getElementById('arrow-' + index);
    detail.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}
</script>
            </div>
            @else
            <div class="card p-8 text-center mb-8">
                <p class="text-gray-400 text-sm">Course list coming soon.</p>
            </div>
            @endif

            {{-- Back button --}}
            <a href="{{ route('tracks.career') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
                ← Back to Career Tracks
            </a>
        </div>
    </main>
</div>
</body>
</html>