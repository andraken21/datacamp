<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AgentCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background:#0a0e1a" class="min-h-screen text-white">

{{-- NAVBAR --}}
<nav class="flex items-center justify-between px-6 py-3 border-b border-white/10">
    <a href="/" class="text-green-400 text-base font-medium">&#9632; agentcamp</a>
    <div class="flex items-center gap-4">
        <a href="{{ route('katalog') }}" class="text-sm text-white/60 hover:text-white">Katalog</a>
        <div class="relative group">
            <button class="flex items-center gap-2 text-sm text-white/80 hover:text-white">
                <div class="w-7 h-7 rounded-full bg-green-400/20 border border-green-400/30 flex items-center justify-center text-green-400 text-xs font-medium">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                {{ Auth::user()->name }}
                <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><path d="M6 8L1 3h10L6 8z"/></svg>
            </button>
            {{-- Dropdown --}}
            <div class="absolute right-0 top-10 w-44 bg-gray-900 border border-white/10 rounded-xl py-1 hidden group-hover:block z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5">Profile</a>
                <div class="border-t border-white/10 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-white/5">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- HEADER --}}
<div class="px-6 pt-8 pb-6 border-b border-white/10">
    <p class="text-sm text-white/40 mb-1">Selamat datang kembali,</p>
    <h1 class="text-2xl font-medium">{{ Auth::user()->name }} 👋</h1>
</div>

{{-- STATS --}}
<div class="grid grid-cols-4 gap-4 px-6 py-6">
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Tools dipelajari</p>
        <p class="text-2xl font-medium text-white">0</p>
    </div>
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Tools disimpan</p>
        <p class="text-2xl font-medium text-white">0</p>
    </div>
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Hari berturut-turut</p>
        <p class="text-2xl font-medium text-green-400">1</p>
    </div>
    <div class="bg-white/5 rounded-xl p-4 border border-white/8">
        <p class="text-xs text-white/40 mb-1">Level</p>
        <p class="text-2xl font-medium text-white">Pemula</p>
    </div>
</div>

{{-- CONTENT --}}
<div class="grid grid-cols-3 gap-6 px-6 pb-10">

    {{-- Rekomendasi --}}
    <div class="col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-medium">Mulai dari sini</h2>
            <a href="{{ route('katalog') }}" class="text-xs text-green-400 hover:text-green-300">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-2 gap-3">
            @php
            $featured = \App\Models\Tool::where('is_featured', true)->take(4)->get();
            @endphp
            @foreach($featured as $tool)
            <a href="{{ route('tool.detail', $tool->slug) }}"
               class="block bg-gray-900 border border-white/10 rounded-xl p-4 hover:border-green-400/40 transition-colors">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium text-white mb-3"
                     style="background: {{ $tool->icon_color ?? '#1a1060' }}">
                    {{ $tool->icon_text }}
                </div>
                <h3 class="text-sm font-medium text-white mb-1">{{ $tool->name }}</h3>
                <p class="text-xs text-white/40 leading-relaxed line-clamp-2">{{ $tool->description }}</p>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/8">
                    <span class="text-xs text-white/30">{{ $tool->language }}</span>
                    <span class="text-xs text-yellow-400">&#9733; {{ $tool->rating }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Sidebar kanan --}}
    <div>
        {{-- Profile card --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5 mb-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-green-400/20 border border-green-400/30 flex items-center justify-content-center items-center justify-center text-green-400 font-medium">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/40">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="border-t border-white/8 pt-4">
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-white/40">Progress level</span>
                    <span class="text-green-400">0%</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-1.5">
                    <div class="bg-green-400 h-1.5 rounded-full" style="width: 0%"></div>
                </div>
                <p class="text-xs text-white/30 mt-2">Pelajari 1 tool untuk naik level</p>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Kategori populer</h3>
            <div class="flex flex-col gap-2">
                @foreach(['Framework','Multi-Agent','Memory','Planning','Monitoring'] as $cat)
                <a href="{{ route('katalog', ['category'=>$cat]) }}"
                   class="flex items-center justify-between text-sm text-white/60 hover:text-white py-1.5 border-b border-white/5 last:border-0">
                    {{ $cat }}
                    <span class="text-xs text-white/30">→</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>