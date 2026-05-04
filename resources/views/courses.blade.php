<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{background:#0a0e1a} .card:hover{border-color:rgba(55,232,160,0.4)!important}</style>
</head>
<body class="text-white min-h-screen">

<x-navbar />

{{-- HEADER --}}
<div class="px-6 pt-8 pb-4">
    <h1 class="text-2xl font-medium mb-1">Kursus AI Agent</h1>
    <p class="text-sm text-white/50">Pelajari AI agent dari nol hingga mahir dengan instruktur berpengalaman</p>
</div>

{{-- SEARCH --}}
<div class="flex gap-3 px-6 pb-4">
    <form method="GET" action="{{ route('courses') }}" class="flex gap-3 w-full">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kursus..."
                class="w-full bg-white/5 border border-white/10 text-white text-sm pl-10 pr-4 py-2 rounded-lg placeholder-white/30 focus:outline-none focus:border-green-400/50">
        </div>
        <select name="sort" onchange="this.form.submit()" class="bg-white/5 border border-white/10 text-white text-sm px-3 py-2 rounded-lg">
            <option value="popular" {{ request('sort')=='popular'?'selected':'' }}>Terpopuler</option>
            <option value="rating" {{ request('sort')=='rating'?'selected':'' }}>Rating tertinggi</option>
            <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>Terbaru</option>
        </select>
        <button type="submit" class="bg-green-400 text-gray-900 font-medium px-4 py-2 rounded-lg text-sm">Cari</button>
    </form>
</div>

<div class="flex px-6 gap-6">
    {{-- SIDEBAR --}}
    <aside class="w-48 shrink-0">
        <div class="mb-6">
            <p class="text-xs text-white/35 uppercase tracking-widest mb-3">Kategori</p>
            <a href="{{ route('courses') }}" class="flex justify-between items-center px-2 py-1.5 rounded-md text-sm mb-1 {{ !request('category') || request('category')=='all' ? 'text-green-400 bg-green-400/10' : 'text-white/60 hover:text-white hover:bg-white/5' }}">Semua</a>
            @foreach($categories as $cat)
            <a href="{{ route('courses', ['category'=>$cat]) }}" class="flex justify-between items-center px-2 py-1.5 rounded-md text-sm mb-1 {{ request('category')==$cat ? 'text-green-400 bg-green-400/10' : 'text-white/60 hover:text-white hover:bg-white/5' }}">{{ $cat }}</a>
            @endforeach
        </div>
        <div>
            <p class="text-xs text-white/35 uppercase tracking-widest mb-3">Tingkat</p>
            @foreach(['Pemula','Menengah','Expert'] as $diff)
            <a href="{{ route('courses', array_merge(request()->all(), ['difficulty'=>$diff])) }}" class="flex justify-between items-center px-2 py-1.5 rounded-md text-sm mb-1 {{ request('difficulty')==$diff ? 'text-green-400 bg-green-400/10' : 'text-white/60 hover:text-white hover:bg-white/5' }}">{{ $diff }}</a>
            @endforeach
        </div>
    </aside>

    {{-- GRID --}}
    <main class="flex-1 min-w-0 pb-10">
        <p class="text-xs text-white/35 mb-4">Menampilkan <span class="text-green-400">{{ $courses->total() }}</span> kursus</p>
        @if($courses->count())
        <div class="grid grid-cols-3 gap-4 mb-6">
            @foreach($courses as $course)
            <a href="{{ route('course.detail', $course->slug) }}"
               class="card block bg-gray-900 border border-white/10 rounded-xl overflow-hidden hover:border-green-400/40 transition-colors">
                {{-- Thumbnail --}}
                <div class="h-28 flex items-center justify-center text-3xl font-bold text-white/20 relative"
                     style="background: linear-gradient(135deg, {{ $course->thumbnail_color }}, #0a0e1a)">
                    <span class="text-4xl font-bold text-white/30">{{ $course->icon_text }}</span>
                    @if(!$course->is_free)
                    <span class="absolute top-3 right-3 text-xs bg-yellow-400/20 text-yellow-400 border border-yellow-400/30 px-2 py-0.5 rounded">PRO</span>
                    @else
                    <span class="absolute top-3 right-3 text-xs bg-green-400/20 text-green-400 border border-green-400/30 px-2 py-0.5 rounded">GRATIS</span>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-0.5 rounded
                            @if($course->difficulty=='Pemula') bg-green-900/50 text-green-300
                            @elseif($course->difficulty=='Menengah') bg-yellow-900/50 text-yellow-300
                            @else bg-red-900/50 text-red-300 @endif">
                            {{ $course->difficulty }}
                        </span>
                        <span class="text-xs text-white/30">{{ $course->category }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-white mb-1 line-clamp-2">{{ $course->title }}</h3>
                    <p class="text-xs text-white/40 mb-3 line-clamp-2">{{ $course->description }}</p>
                    <div class="flex items-center justify-between text-xs text-white/35 pt-3 border-t border-white/8">
                        <span>{{ $course->total_lessons }} pelajaran · {{ $course->duration_hours }}j</span>
                        <span class="text-yellow-400">&#9733; {{ $course->rating }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-2 text-xs text-white/30">
                        <div class="w-4 h-4 rounded-full bg-white/10 flex items-center justify-center text-xs">{{ substr($course->instructor, 0, 1) }}</div>
                        {{ $course->instructor }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        {{ $courses->withQueryString()->links() }}
        @else
        <div class="text-center py-16 text-white/35 text-sm">Tidak ada kursus yang cocok.</div>
        @endif
    </main>
</div>

</body>
</html>