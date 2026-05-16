<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #1a1a2e; }
        /* DataCamp dark navy background */
        body { background-color: #0d0d1a; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }

        /* Card hover */
        .course-card:hover { border-color: rgba(3, 239, 98, 0.35) !important; transform: translateY(-1px); }
        .course-card { transition: all 0.15s ease; }

        /* Sidebar active */
        .sidebar-active { background: rgba(3, 239, 98, 0.12); color: #03ef62 !important; border-radius: 6px; }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); border-radius: 6px; }

        /* Search focus */
        .search-input:focus { border-color: rgba(3, 239, 98, 0.5); box-shadow: 0 0 0 3px rgba(3, 239, 98, 0.08); }

        /* Badge pill */
        .badge-beginner  { background: rgba(3,239,98,0.12);  color: #03ef62;  }
        .badge-mid       { background: rgba(250,189,0,0.12); color: #fabd00; }
        .badge-expert    { background: rgba(239,68,68,0.12); color: #f87171; }
        .badge-pro       { background: rgba(250,189,0,0.12); color: #fabd00; border: 1px solid rgba(250,189,0,0.25); }
        .badge-free      { background: rgba(3,239,98,0.12);  color: #03ef62;  border: 1px solid rgba(3,239,98,0.25); }

        /* Pagination */
        .page-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); padding: 6px 12px; border-radius: 6px; font-size: 13px; }
        .page-btn:hover, .page-btn.active { background: rgba(3,239,98,0.15); border-color: rgba(3,239,98,0.4); color: #03ef62; }
    </style>
</head>
<body class="text-white min-h-screen" style="background-color:#0d0d1a;">

{{-- ===== NAVBAR ===== --}}
<x-navbar />

{{-- ===== PAGE WRAPPER ===== --}}
<div class="max-w-[1280px] mx-auto px-6">

    {{-- PAGE HEADER --}}
    <div class="pt-8 pb-5">
        <h1 class="text-[22px] font-semibold text-white mb-1">Kursus DataCamp</h1>
        <p class="text-sm text-white/45">Pelajari DataCamp dari nol hingga mahir dengan instruktur berpengalaman</p>
    </div>

    {{-- SEARCH BAR ROW --}}
    <form method="GET" action="{{ route('courses') }}" class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kursus..."
                class="search-input w-full bg-[#1a1a2e] border border-white/12 text-white text-sm pl-10 pr-4 py-2.5 rounded-lg placeholder-white/30 focus:outline-none transition-all">
        </div>

        {{-- SORT DROPDOWN --}}
        <div class="relative">
            <select name="sort" onchange="this.form.submit()"
                class="appearance-none bg-[#1a1a2e] border border-white/12 text-white text-sm pl-4 pr-9 py-2.5 rounded-lg focus:outline-none cursor-pointer">
                <option value="popular"  {{ request('sort')=='popular' ?'selected':'' }}>Terpopuler</option>
                <option value="rating"   {{ request('sort')=='rating'  ?'selected':'' }}>Rating tertinggi</option>
                <option value="newest"   {{ request('sort')=='newest'  ?'selected':'' }}>Terbaru</option>
            </select>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <button type="submit"
            class="bg-[#03ef62] hover:bg-[#00d455] text-gray-900 font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
            Cari
        </button>
    </form>

    {{-- MAIN LAYOUT: Sidebar + Grid --}}
    <div class="flex gap-7 pb-12">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="w-44 shrink-0 pt-1">

            {{-- KATEGORI --}}
            <div class="mb-7">
                <p class="text-[10px] font-semibold text-white/35 uppercase tracking-[0.1em] mb-2.5">Kategori</p>

                <a href="{{ route('courses') }}"
                   class="sidebar-link block px-2.5 py-1.5 text-sm mb-0.5 {{ !request('category') || request('category')=='all' ? 'sidebar-active font-medium' : 'text-white/60' }}">
                    Semua
                </a>

                @foreach($categories as $cat)
                <a href="{{ route('courses', array_merge(request()->except('category'), ['category'=>$cat])) }}"
                   class="sidebar-link block px-2.5 py-1.5 text-sm mb-0.5 {{ request('category')==$cat ? 'sidebar-active font-medium' : 'text-white/60' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>

            {{-- TINGKAT --}}
            <div>
                <p class="text-[10px] font-semibold text-white/35 uppercase tracking-[0.1em] mb-2.5">Tingkat</p>

                @foreach(['Pemula','Menengah','Expert'] as $diff)
                <a href="{{ route('courses', array_merge(request()->all(), ['difficulty'=>$diff])) }}"
                   class="sidebar-link block px-2.5 py-1.5 text-sm mb-0.5 {{ request('difficulty')==$diff ? 'sidebar-active font-medium' : 'text-white/60' }}">
                    {{ $diff }}
                </a>
                @endforeach
            </div>
        </aside>

        {{-- ===== COURSE GRID ===== --}}
        <main class="flex-1 min-w-0">

            {{-- Result count --}}
            <p class="text-[12px] text-white/40 mb-4">
                Menampilkan <span class="text-[#03ef62] font-medium">{{ $courses->total() }}</span> kursus
            </p>

            @if($courses->count())

            {{-- GRID 3 cols --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                @foreach($courses as $course)
                <a href="{{ route('course.detail', $course->slug) }}"
                   class="course-card block bg-[#13131f] border border-white/8 rounded-xl overflow-hidden">

                    {{-- Thumbnail --}}
                    <div class="h-[130px] flex items-center justify-center relative overflow-hidden"
                         style="background: linear-gradient(135deg, {{ $course->thumbnail_color ?? '#1e3a5f' }}, #0d0d1a 80%);">
                        <span class="text-5xl font-black text-white/20 select-none">{{ $course->icon_text }}</span>

                        {{-- Badge PRO/GRATIS --}}
                        @if(!$course->is_free)
                        <span class="badge-pro absolute top-3 right-3 text-[10px] font-semibold px-2 py-0.5 rounded-full">PRO</span>
                        @else
                        <span class="badge-free absolute top-3 right-3 text-[10px] font-semibold px-2 py-0.5 rounded-full">GRATIS</span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-4">

                        {{-- Difficulty + Category --}}
                        <div class="flex items-center gap-2 mb-2.5">
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                @if($course->difficulty=='Pemula')   badge-beginner
                                @elseif($course->difficulty=='Menengah') badge-mid
                                @else badge-expert @endif">
                                {{ $course->difficulty }}
                            </span>
                            <span class="text-[11px] text-white/35">{{ $course->category }}</span>
                        </div>

                        {{-- Title --}}
                        <h3 class="text-[13px] font-semibold text-white leading-snug mb-1.5 line-clamp-2">{{ $course->title }}</h3>

                        {{-- Description --}}
                        <p class="text-[11px] text-white/40 leading-relaxed mb-3.5 line-clamp-2">{{ $course->description }}</p>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-3 border-t border-white/[0.07]">
                            <span class="text-[11px] text-white/35">{{ $course->total_lessons }} pelajaran · {{ $course->duration_hours }}j</span>
                            <span class="text-[11px] text-yellow-400 flex items-center gap-0.5">
                                <svg class="w-3 h-3 fill-yellow-400" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                {{ $course->rating }}
                            </span>
                        </div>

                        {{-- Instructor --}}
                        <div class="flex items-center gap-2 mt-2.5">
                            <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center text-[10px] font-medium text-white/60 shrink-0">
                                {{ strtoupper(substr($course->instructor, 0, 1)) }}
                            </div>
                            <span class="text-[11px] text-white/35 truncate">{{ $course->instructor }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="flex items-center justify-center gap-1.5">
                {{ $courses->withQueryString()->links() }}
            </div>

            @else
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-white/35">Tidak ada kursus yang cocok.</p>
                <a href="{{ route('courses') }}" class="mt-3 text-xs text-[#03ef62] hover:underline">Reset filter →</a>
            </div>
            @endif

        </main>
    </div>
</div>

</body>
</html>