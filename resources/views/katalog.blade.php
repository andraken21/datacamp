<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0a0e1a; }
        .card:hover { border-color: rgba(55,232,160,0.4) !important; }
        .pill.active { background: rgba(55,232,160,0.15); border-color: #37e8a0; color: #37e8a0; }
        input:focus { border-color: rgba(55,232,160,0.5) !important; outline: none; }
    </style>
</head>
<body class="text-white min-h-screen">

{{-- NAVBAR --}}
<nav class="flex items-center justify-between px-6 py-3 border-b border-white/10">
    <a href="/" class="text-green-400 font-medium text-base">&#9632; datacamp</a>
    <div class="flex gap-3">
        <a href="#" class="text-sm border border-white/25 px-3 py-1.5 rounded-md hover:border-white">Masuk</a>
        <a href="#" class="text-sm bg-green-400 text-gray-900 font-medium px-3 py-1.5 rounded-md hover:bg-green-300">Mulai Belajar</a>
    </div>
</nav>

{{-- HEADER --}}
<div class="px-6 pt-8 pb-4">
    <h1 class="text-2xl font-medium mb-1">Katalog DataCamp</h1>
    <p class="text-sm text-white/50">Temukan tools, framework, dan library untuk membangun DataCamp</p>
</div>

{{-- SEARCH & SORT --}}
<div class="flex gap-3 px-6 pb-4 items-center">
    <form method="GET" action="{{ route('katalog') }}" class="flex gap-3 w-full">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari tools, framework..."
                class="w-full bg-white/5 border border-white/10 text-white text-sm pl-10 pr-4 py-2 rounded-lg placeholder-white/30">
        </div>
        <select name="sort" onchange="this.form.submit()"
            class="bg-white/5 border border-white/10 text-white text-sm px-3 py-2 rounded-lg cursor-pointer">
            <option value="rating" {{ request('sort')=='rating'?'selected':'' }}>Rating tertinggi</option>
            <option value="stars" {{ request('sort')=='stars'?'selected':'' }}>Bintang GitHub</option>
            <option value="az" {{ request('sort')=='az'?'selected':'' }}>A - Z</option>
        </select>
        <button type="submit" class="bg-green-400 text-gray-900 text-sm font-medium px-4 py-2 rounded-lg hover:bg-green-300">Cari</button>
    </form>
</div>

{{-- ACTIVE FILTERS --}}
@if(request('search') || request('category'))
<div class="flex gap-2 px-6 pb-3 flex-wrap">
    @if(request('search'))
    <span class="flex items-center gap-1 bg-green-400/10 border border-green-400/30 text-green-400 text-xs px-3 py-1 rounded-full">
        "{{ request('search') }}"
        <a href="{{ route('katalog', array_merge(request()->except('search'))) }}" class="ml-1 hover:text-white">&#215;</a>
    </span>
    @endif
    @if(request('category') && request('category') !== 'all')
    <span class="flex items-center gap-1 bg-green-400/10 border border-green-400/30 text-green-400 text-xs px-3 py-1 rounded-full">
        {{ request('category') }}
        <a href="{{ route('katalog', array_merge(request()->except('category'))) }}" class="ml-1 hover:text-white">&#215;</a>
    </span>
    @endif
</div>
@endif

{{-- BODY: SIDEBAR + GRID --}}
<div class="flex px-6 gap-6">

    {{-- SIDEBAR --}}
    <aside class="w-48 shrink-0">
        <div class="mb-6">
            <p class="text-xs text-white/35 uppercase tracking-widest mb-3">Kategori</p>
            <a href="{{ route('katalog', array_merge(request()->except('category'), ['category'=>'all'])) }}"
               class="flex justify-between items-center px-2 py-1.5 rounded-md text-sm mb-1 {{ !request('category') || request('category')=='all' ? 'text-green-400 bg-green-400/10' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('katalog', array_merge(request()->except('category'), ['category'=>$cat])) }}"
               class="flex justify-between items-center px-2 py-1.5 rounded-md text-sm mb-1 {{ request('category')==$cat ? 'text-green-400 bg-green-400/10' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        <div>
            <p class="text-xs text-white/35 uppercase tracking-widest mb-3">Bahasa</p>
            @foreach($languages as $lang)
            <a href="{{ route('katalog', array_merge(request()->except('language'), ['language'=>$lang])) }}"
               class="flex justify-between items-center px-2 py-1.5 rounded-md text-sm mb-1 {{ request('language')==$lang ? 'text-green-400 bg-green-400/10' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                {{ $lang }}
            </a>
            @endforeach
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 min-w-0">
        <p class="text-xs text-white/35 mb-4">Menampilkan <span class="text-green-400">{{ $tools->total() }}</span> tools</p>

        @if($tools->count())
        <div class="grid grid-cols-3 gap-3 mb-6">
            @foreach($tools as $tool)
            <a href="{{ route('tool.detail', $tool->slug) }}"
               class="card block bg-gray-900 border border-white/10 rounded-xl p-4 hover:border-green-400/40 transition-colors">
                {{-- Icon --}}
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-xs font-medium mb-3 text-white"
                     style="background: {{ $tool->icon_color ?? '#1a1060' }}">
                    {{ $tool->icon_text ?? substr($tool->name,0,2) }}
                </div>
                {{-- Header --}}
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="text-sm font-medium text-white leading-tight">{{ $tool->name }}</h3>
                    <span class="text-xs px-1.5 py-0.5 rounded shrink-0
                        @if($tool->category=='Framework') bg-purple-900/50 text-purple-300
                        @elseif($tool->category=='Multi-Agent') bg-teal-900/50 text-teal-300
                        @elseif($tool->category=='Memory') bg-blue-900/50 text-blue-300
                        @elseif($tool->category=='Monitoring') bg-pink-900/50 text-pink-300
                        @else bg-amber-900/50 text-amber-300 @endif">
                        {{ $tool->category }}
                    </span>
                </div>
                {{-- Desc --}}
                <p class="text-xs text-white/45 leading-relaxed mb-3 line-clamp-2">{{ $tool->description }}</p>
                {{-- Tags --}}
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach(array_slice($tool->tags ?? [], 0, 3) as $tag)
                    <span class="text-xs px-1.5 py-0.5 bg-white/5 text-white/40 rounded">{{ $tag }}</span>
                    @endforeach
                </div>
                {{-- Footer --}}
                <div class="flex items-center justify-between pt-2 border-t border-white/8">
                    <span class="text-xs text-white/30">{{ $tool->language }}</span>
                    <span class="text-xs text-yellow-400">&#9733; {{ $tool->rating }}</span>
                </div>
            </a>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="pb-8">
            {{ $tools->withQueryString()->links() }}
        </div>

        @else
        <div class="text-center py-16 text-white/35 text-sm">
            Tidak ada tools yang cocok dengan pencarian ini.
        </div>
        @endif
    </main>
</div>

</body>
</html>