<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Belajar - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{background:#0a0e1a}
        .lesson-item:hover{background:rgba(255,255,255,0.05)}
        .lesson-item.active{background:rgba(55,232,160,0.1);border-left:2px solid #37e8a0}
    </style>
</head>
<body class="text-white min-h-screen">

{{-- NAVBAR KECIL --}}
<nav class="flex items-center justify-between px-6 py-3 border-b border-white/10 sticky top-0 z-50" style="background:#0a0e1a">
    <div class="flex items-center gap-3">
        <a href="{{ route('course.detail', $course->slug) }}" class="text-white/40 hover:text-white text-sm">← Kembali</a>
        <div class="w-px h-4 bg-white/10"></div>
        <a href="/" class="text-green-400 text-sm font-medium">&#9632; datacamp</a>
        <div class="w-px h-4 bg-white/10"></div>
        <span class="text-sm text-white/60 line-clamp-1">{{ $course->title }}</span>
    </div>
    <div class="flex items-center gap-3">
        <div class="text-xs text-white/40">Progress</div>
        <div class="w-32 bg-white/10 rounded-full h-1.5">
            <div class="bg-green-400 h-1.5 rounded-full transition-all" style="width: {{ $enrollment->progress }}%"></div>
        </div>
        <span class="text-xs text-green-400 font-medium">{{ $enrollment->progress }}%</span>
    </div>
</nav>

@if(session('message'))
<div class="mx-6 mt-3 px-4 py-2 bg-green-400/15 border border-green-400/30 text-green-400 text-sm rounded-lg">
    {{ session('message') }}
</div>
@endif

<div class="flex h-[calc(100vh-52px)]">

    {{-- SIDEBAR KIRI: Daftar Lesson --}}
    <aside class="w-72 shrink-0 border-r border-white/10 overflow-y-auto">
        <div class="p-4 border-b border-white/8">
            <p class="text-xs text-white/40 uppercase tracking-widest mb-1">Silabus</p>
            <p class="text-sm font-medium">{{ $course->total_lessons }} Pelajaran · {{ $course->duration_hours }} Jam</p>
        </div>

        <div class="py-2">
            @foreach($course->lessons as $index => $lesson)
            @php $isCompleted = $completedLessons->contains($lesson->id); @endphp
            <div class="lesson-item px-4 py-3 cursor-pointer flex items-center gap-3 {{ $firstLesson && $firstLesson->id == $lesson->id ? 'active' : '' }}"
                 onclick="showLesson({{ $lesson->id }}, '{{ addslashes($lesson->title) }}', `{{ addslashes($lesson->content) }}`, '{{ $lesson->type }}', {{ $lesson->duration_minutes }}, {{ $isCompleted ? 'true' : 'false' }})">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs shrink-0 {{ $isCompleted ? 'bg-green-400 text-gray-900' : 'bg-white/10 text-white/40' }}">
                    @if($isCompleted) ✓
                    @elseif($lesson->type == 'quiz') Q
                    @else {{ $index + 1 }} @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-white/80 line-clamp-1">{{ $lesson->title }}</p>
                    <p class="text-xs text-white/35 mt-0.5">{{ $lesson->duration_minutes }} menit · {{ $lesson->type }}</p>
                </div>
                @if($lesson->is_free_preview)
                <span class="text-xs text-green-400/70">Free</span>
                @endif
            </div>
            @endforeach
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 overflow-y-auto">
        <div class="max-w-3xl mx-auto px-8 py-8">

            {{-- Video placeholder --}}
            <div id="lesson-video" class="w-full aspect-video rounded-xl flex items-center justify-center mb-6"
                 style="background: linear-gradient(135deg, {{ $course->thumbnail_color }}, #0a0e1a)">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-3 cursor-pointer hover:bg-white/20">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <p class="text-white/50 text-sm" id="video-label">Klik untuk mulai</p>
                </div>
            </div>

            {{-- Info lesson --}}
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-xl font-medium mb-1" id="lesson-title">{{ $firstLesson ? $firstLesson->title : 'Pilih pelajaran' }}</h1>
                    <p class="text-sm text-white/40" id="lesson-meta">{{ $firstLesson ? $firstLesson->duration_minutes.' menit · '.$firstLesson->type : '' }}</p>
                </div>
                <form method="POST" action="{{ route('lesson.complete', $firstLesson ? $firstLesson->id : 0) }}" id="complete-form">
                    @csrf
                    <button type="submit" id="complete-btn"
                        class="{{ $completedLessons->contains($firstLesson?->id) ? 'bg-green-400/20 border border-green-400/40 text-green-400' : 'bg-green-400 text-gray-900 hover:bg-green-300' }} px-4 py-2 rounded-lg text-sm font-medium">
                        {{ $completedLessons->contains($firstLesson?->id) ? '✓ Selesai' : 'Tandai Selesai' }}
                    </button>
                </form>
            </div>

            {{-- Konten --}}
            <div class="bg-gray-900 border border-white/10 rounded-xl p-6 mb-6">
                <h2 class="text-sm font-medium text-white/60 mb-4 uppercase tracking-widest">Materi Pelajaran</h2>
                <div id="lesson-content" class="text-sm text-white/70 leading-relaxed">
                    {{ $firstLesson ? $firstLesson->content : 'Pilih pelajaran dari sidebar kiri untuk memulai.' }}
                </div>
            </div>

            {{-- Quiz placeholder --}}
            <div id="quiz-section" class="hidden bg-gray-900 border border-white/10 rounded-xl p-6 mb-6">
                <h2 class="text-sm font-medium mb-4">&#9632; Quiz</h2>
                <p class="text-sm text-white/60 mb-4">Uji pemahaman kamu dengan pertanyaan berikut:</p>
                <div class="space-y-3">
                    <div class="bg-white/5 rounded-lg p-4 cursor-pointer hover:bg-white/10 text-sm">A. Jawaban pertama</div>
                    <div class="bg-white/5 rounded-lg p-4 cursor-pointer hover:bg-white/10 text-sm">B. Jawaban kedua</div>
                    <div class="bg-white/5 rounded-lg p-4 cursor-pointer hover:bg-white/10 text-sm">C. Jawaban ketiga</div>
                    <div class="bg-white/5 rounded-lg p-4 cursor-pointer hover:bg-white/10 text-sm">D. Jawaban keempat</div>
                </div>
            </div>

            {{-- Navigasi prev/next --}}
            <div class="flex justify-between pt-4 border-t border-white/8">
                <button onclick="prevLesson()" class="flex items-center gap-2 text-sm text-white/50 hover:text-white px-4 py-2 rounded-lg border border-white/10 hover:border-white/30">
                    ← Sebelumnya
                </button>
                <button onclick="nextLesson()" class="flex items-center gap-2 text-sm bg-green-400 text-gray-900 font-medium px-4 py-2 rounded-lg hover:bg-green-300">
                    Selanjutnya →
                </button>
            </div>
        </div>
    </main>
</div>

<script>
// Data semua lessons
const lessons = @json($course->lessons);
let currentIndex = 0;
const completedIds = @json($completedLessons);

function showLesson(id, title, content, type, duration, isCompleted) {
    // Update judul dan meta
    document.getElementById('lesson-title').textContent = title;
    document.getElementById('lesson-meta').textContent = duration + ' menit · ' + type;
    document.getElementById('lesson-content').textContent = content;
    document.getElementById('video-label').textContent = title;

    // Update form action
    document.getElementById('complete-form').action = '/lessons/' + id + '/complete';

    // Update tombol selesai
    const btn = document.getElementById('complete-btn');
    if (isCompleted) {
        btn.className = 'bg-green-400/20 border border-green-400/40 text-green-400 px-4 py-2 rounded-lg text-sm font-medium';
        btn.textContent = '✓ Selesai';
    } else {
        btn.className = 'bg-green-400 text-gray-900 hover:bg-green-300 px-4 py-2 rounded-lg text-sm font-medium';
        btn.textContent = 'Tandai Selesai';
    }

    // Show/hide quiz
    document.getElementById('quiz-section').classList.toggle('hidden', type !== 'quiz');

    // Update active di sidebar
    document.querySelectorAll('.lesson-item').forEach((el, i) => {
        el.classList.remove('active');
        if (lessons[i] && lessons[i].id === id) {
            el.classList.add('active');
            currentIndex = i;
        }
    });
}

function nextLesson() {
    if (currentIndex < lessons.length - 1) {
        currentIndex++;
        const l = lessons[currentIndex];
        showLesson(l.id, l.title, l.content, l.type, l.duration_minutes, completedIds.includes(l.id));
    }
}

function prevLesson() {
    if (currentIndex > 0) {
        currentIndex--;
        const l = lessons[currentIndex];
        showLesson(l.id, l.title, l.content, l.type, l.duration_minutes, completedIds.includes(l.id));
    }
}
</script>

</body>
</html>