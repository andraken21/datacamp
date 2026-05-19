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
    <div class="max-w-5xl mx-auto px-6 py-10 grid grid-cols-3 gap-8">
        <div class="col-span-2">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs px-2 py-0.5 rounded bg-white/10 text-white/60">{{ $course->category }}</span>
                <span class="text-xs px-2 py-0.5 rounded
                    @if($course->difficulty=='Pemula') bg-green-900/50 text-green-300
                    @elseif($course->difficulty=='Menengah') bg-yellow-900/50 text-yellow-300
                    @else bg-red-900/50 text-red-300 @endif">
                    {{ $course->difficulty }}
                </span>
                @if($course->is_free)
                <span class="text-xs px-2 py-0.5 rounded bg-green-400/20 text-green-400">GRATIS</span>
                @else
                <span class="text-xs px-2 py-0.5 rounded bg-yellow-400/20 text-yellow-400">PRO</span>
                @endif
            </div>
            <h1 class="text-3xl font-medium mb-3">{{ $course->title }}</h1>
            <p class="text-white/60 text-sm leading-relaxed mb-5">{{ $course->description }}</p>
            <div class="flex items-center gap-5 text-sm text-white/50 mb-6">
                <span class="text-yellow-400 font-medium">&#9733; {{ $course->rating }}</span>
                <span>{{ number_format($course->students_count) }} siswa</span>
                <span>{{ $course->total_lessons }} pelajaran</span>
                <span>{{ $course->duration_hours }} jam</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-white/50">
                <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-xs">
                    {{ substr($course->instructor, 0, 1) }}
                </div>
                Instruktur: <span class="text-white/80">{{ $course->instructor }}</span>
            </div>
        </div>

        {{-- CARD ENROLL --}}
        <div class="bg-gray-900/90 rounded-2xl p-6 border border-white/10 h-fit">
            <div class="w-full h-24 rounded-xl flex items-center justify-center text-3xl font-bold text-white/30 mb-4"
                 style="background: {{ $course->thumbnail_color }}">
                {{ $course->icon_text }}
            </div>
            @if($isEnrolled)
            <div class="mb-3 text-center">
                <div class="text-xs text-white/40 mb-1">Progress kamu</div>
                <div class="w-full bg-white/10 rounded-full h-2 mb-1">
                    <div class="bg-green-400 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                </div>
                <div class="text-xs text-green-400">{{ $enrollment->progress }}% selesai</div>
            </div>
            <a href="{{ route('course.learn', $course->slug) }}"
               class="block w-full text-center bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300 mb-3">
                {{ $enrollment->progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}
            </a>
            @else
                <form method="POST" action="{{ route('course.enroll', $course->course_id) }}">
            @csrf
                <button type="submit"
                    class="w-full bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300 mb-3">
                    {{ $course->is_free ? 'Mulai Gratis' : 'Daftar Kursus' }}
                </button>
            </form>
            @endif
            <div class="space-y-2 text-xs text-white/50">
                <div class="flex justify-between">
                    <span>Total pelajaran</span>
                    <span class="text-white/80">{{ $course->total_lessons }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Durasi</span>
                    <span class="text-white/80">{{ $course->duration_hours }} jam</span>
                </div>
                <div class="flex justify-between">
                    <span>Tingkat</span>
                    <span class="text-white/80">{{ $course->difficulty }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Bahasa</span>
                    <span class="text-white/80">Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KONTEN --}}
<div class="max-w-5xl mx-auto px-6 py-8 grid grid-cols-3 gap-8">
    <div class="col-span-2">

        {{-- Silabus --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6 mb-6">
            <h2 class="text-base font-medium mb-4">Silabus Kursus</h2>
            <div class="space-y-2">
                @foreach($course->lessons as $index => $lesson)
                <div class="flex items-center gap-3 py-3 border-b border-white/5 last:border-0">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs shrink-0
                        @if($lesson->type=='quiz') bg-purple-900/50 text-purple-300
                        @else bg-white/5 text-white/40 @endif">
                        @if($lesson->type=='quiz') Q
                        @else {{ $index + 1 }} @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-white/80">{{ $lesson->title }}</p>
                        <p class="text-xs text-white/35">{{ $lesson->duration_minutes }} menit · {{ $lesson->type }}</p>
                    </div>
                    @if($lesson->is_free_preview)
                    <span class="text-xs text-green-400 border border-green-400/30 px-2 py-0.5 rounded">Preview</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tentang --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <h2 class="text-base font-medium mb-3">Tentang Kursus</h2>
            <p class="text-sm text-white/55 leading-relaxed">{{ $course->description }}</p>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="space-y-4">
        {{-- Instruktur --}}
<div class="bg-gray-900 border border-white/10 rounded-xl p-5">
    <h3 class="text-sm font-medium mb-3">Instruktur</h3>
    @if($course->instruktur)
    <div class="flex items-center gap-3">
        <img src="{{ $course->instruktur->url_foto }}" class="w-10 h-10 rounded-full object-cover">
        <div>
            <p class="text-sm text-white/80">{{ $course->instruktur->nama_instruktur }}</p>
            <p class="text-xs text-white/40">{{ $course->instruktur->jabatan }}</p>
        </div>
    </div>
    @else
    <p class="text-sm text-white/40">DataCamp Expert</p>
    @endif
</div>

        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Kursus serupa</h3>
            @php $similar = \App\Models\Course::where('category', $course->category)->where('course_id','!=',$course->course_id)->take(3)->get(); @endphp
            @foreach($similar as $s)
            <a href="{{ route('course.detail', $s->slug) }}" class="flex gap-3 py-2.5 border-b border-white/5 last:border-0 hover:text-green-400 group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium text-white shrink-0"
                     style="background:{{ $s->thumbnail_color }}">{{ $s->icon_text }}</div>
                <div>
                    <p class="text-xs text-white/70 group-hover:text-green-400 line-clamp-2">{{ $s->title }}</p>
                    <p class="text-xs text-white/30 mt-0.5">&#9733; {{ $s->rating }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>