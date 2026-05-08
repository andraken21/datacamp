<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tool->name }} - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background: #0a0e1a; }</style>
</head>
<body class="text-white min-h-screen">

{{-- NAVBAR --}}
<nav class="flex items-center justify-between px-6 py-3 border-b border-white/10">
    <a href="/" class="text-green-400 text-base font-medium">&#9632; </a>
    <div class="flex items-center gap-4 text-sm text-white/60">datacamp
        <a href="{{ route('katalog') }}" class="hover:text-white">Katalog</a>
    </div>
    <div class="flex gap-2">
        @auth
        <a href="{{ route('dashboard') }}" class="text-sm border border-white/25 px-3 py-1.5 rounded-md hover:border-white">Dashboard</a>
        @else
        <a href="{{ route('login') }}" class="text-sm border border-white/25 px-3 py-1.5 rounded-md hover:border-white">Masuk</a>
        <a href="{{ route('register') }}" class="text-sm bg-green-400 text-gray-900 font-medium px-3 py-1.5 rounded-md hover:bg-green-300">Mulai Belajar</a>
        @endauth
    </div>
</nav>

{{-- BREADCRUMB --}}
<div class="px-6 py-3 text-xs text-white/35">
    <a href="{{ route('katalog') }}" class="hover:text-white">Katalog</a>
    <span class="mx-2">/</span>
    <a href="{{ route('katalog', ['category' => $tool->category]) }}" class="hover:text-white">{{ $tool->category }}</a>
    <span class="mx-2">/</span>
    <span class="text-white/60">{{ $tool->name }}</span>
</div>

{{-- NOTIF --}}
@if(session('message'))
<div class="mx-6 mb-2 px-4 py-2 bg-green-400/15 border border-green-400/30 text-green-400 text-sm rounded-lg">
    {{ session('message') }}
</div>
@endif

{{-- HERO TOOL --}}
<div class="px-6 py-8 border-b border-white/8">
    <div class="max-w-5xl mx-auto flex items-start gap-6">
        <div class="w-16 h-16 rounded-xl flex items-center justify-center text-lg font-medium text-white shrink-0"
             style="background: {{ $tool->icon_color ?? '#1a1060' }}">
            {{ $tool->icon_text }}
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-medium">{{ $tool->name }}</h1>
                <span class="text-xs px-2 py-1 rounded
                    @if($tool->category=='Framework') bg-purple-900/50 text-purple-300
                    @elseif($tool->category=='Multi-Agent') bg-teal-900/50 text-teal-300
                    @elseif($tool->category=='Memory') bg-blue-900/50 text-blue-300
                    @elseif($tool->category=='Monitoring') bg-pink-900/50 text-pink-300
                    @else bg-amber-900/50 text-amber-300 @endif">
                    {{ $tool->category }}
                </span>
                <span class="text-xs px-2 py-1 rounded bg-white/5 text-white/50">{{ $tool->difficulty }}</span>
            </div>
            <p class="text-white/55 text-sm leading-relaxed mb-4 max-w-2xl">{{ $tool->description }}</p>
            <div class="flex items-center gap-6 flex-wrap">
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="text-yellow-400">&#9733;</span>
                    <span class="font-medium">{{ $tool->rating }}</span>
                    <span class="text-white/35">/5.0</span>
                </div>
                <div class="text-sm text-white/35">
                    Bahasa: <span class="text-white/70">{{ $tool->language }}</span>
                </div>
                @if($tool->stars_github > 0)
                <div class="text-sm text-white/35">
                    GitHub: <span class="text-white/70">{{ number_format($tool->stars_github) }} &#9733;</span>
                </div>
                @endif
                <div class="flex flex-wrap gap-1.5">
                    @foreach($tool->tags ?? [] as $tag)
                    <span class="text-xs px-2 py-0.5 bg-white/5 text-white/40 rounded">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="flex flex-col gap-2 shrink-0">
            @if($tool->source_url)
            <a href="{{ $tool->source_url }}" target="_blank"
               class="flex items-center gap-2 bg-green-400 text-gray-900 font-medium px-4 py-2 rounded-lg text-sm hover:bg-green-300">
                Buka Source ↗
            </a>
            @endif
            @auth
            @php $isSaved = Auth::user()->savedTools->contains($tool->id); @endphp
            <form method="POST" action="{{ route('tool.save', $tool->id) }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm w-full justify-center transition-colors
                    {{ $isSaved
                        ? 'bg-green-400/20 border border-green-400/40 text-green-400'
                        : 'border border-white/20 text-white/70 hover:border-white hover:text-white' }}">
                    {{ $isSaved ? '♥ Tersimpan' : '♡ Simpan' }}
                </button>
            </form>
            @else
            <a href="{{ route('login') }}"
               class="flex items-center gap-2 border border-white/20 text-white/50 px-4 py-2 rounded-lg text-sm hover:border-white hover:text-white text-center">
                ♡ Simpan
            </a>
            @endauth
        </div>
    </div>
</div>

{{-- KONTEN --}}
<div class="max-w-5xl mx-auto px-6 py-8 grid grid-cols-3 gap-8">

    {{-- KIRI --}}
    <div class="col-span-2 space-y-6">

        {{-- Tentang --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <h2 class="text-base font-medium mb-3">Tentang {{ $tool->name }}</h2>
            <p class="text-sm text-white/55 leading-relaxed">{{ $tool->description }}</p>
        </div>

        {{-- Cara Install --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <h2 class="text-base font-medium mb-4">Cara Install</h2>
            @if($tool->language == 'Python')
            <div class="bg-black/40 rounded-lg p-4 font-mono text-sm text-green-400 mb-3">
                pip install {{ strtolower(str_replace(' ', '-', $tool->name)) }}
            </div>
            @elseif($tool->language == 'JavaScript' || $tool->language == 'TypeScript')
            <div class="bg-black/40 rounded-lg p-4 font-mono text-sm text-green-400 mb-3">
                npm install {{ strtolower(str_replace(' ', '-', $tool->name)) }}
            </div>
            @endif
            <p class="text-xs text-white/35">Pastikan sudah menginstall {{ $tool->language }} dan package manager yang sesuai.</p>
        </div>

        {{-- Contoh Penggunaan --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <h2 class="text-base font-medium mb-4">Contoh Penggunaan</h2>
            @if($tool->language == 'Python')
            <div class="bg-black/40 rounded-lg p-4 font-mono text-xs text-white/70 leading-relaxed">
                <span class="text-purple-400">from</span> {{ strtolower(str_replace([' ', '-'], '_', $tool->name)) }} <span class="text-purple-400">import</span> *<br><br>
                <span class="text-white/40"># Inisialisasi {{ $tool->name }}</span><br>
                agent = {{ str_replace(' ', '', $tool->name) }}()<br><br>
                <span class="text-white/40"># Jalankan task</span><br>
                result = agent.run(<span class="text-green-400">"Tulis task kamu di sini"</span>)<br>
                <span class="text-purple-400">print</span>(result)
            </div>
            @else
            <div class="bg-black/40 rounded-lg p-4 font-mono text-xs text-white/70 leading-relaxed">
                <span class="text-purple-400">import</span> { {{ str_replace(' ', '', $tool->name) }} } <span class="text-purple-400">from</span> <span class="text-green-400">'{{ strtolower(str_replace(' ', '-', $tool->name)) }}'</span>;<br><br>
                <span class="text-white/40">// Inisialisasi {{ $tool->name }}</span><br>
                <span class="text-purple-400">const</span> agent = <span class="text-purple-400">new</span> {{ str_replace(' ', '', $tool->name) }}();<br><br>
                <span class="text-white/40">// Jalankan task</span><br>
                <span class="text-purple-400">const</span> result = <span class="text-purple-400">await</span> agent.run(<span class="text-green-400">"Tulis task kamu di sini"</span>);<br>
                console.log(result);
            </div>
            @endif
        </div>
    </div>

    {{-- KANAN --}}
    <div class="space-y-4">

        {{-- Info --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-4">Informasi</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-white/40">Kategori</span>
                    <span class="text-white/80">{{ $tool->category }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-white/40">Bahasa</span>
                    <span class="text-white/80">{{ $tool->language }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-white/40">Tingkat</span>
                    <span class="text-white/80">{{ $tool->difficulty }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-white/40">Rating</span>
                    <span class="text-yellow-400">&#9733; {{ $tool->rating }}</span>
                </div>
                @if($tool->stars_github > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-white/40">GitHub Stars</span>
                    <span class="text-white/80">{{ number_format($tool->stars_github) }}</span>
                </div>
                @endif
            </div>
            @if($tool->source_url)
            <a href="{{ $tool->source_url }}" target="_blank"
               class="block mt-4 text-center text-xs text-green-400 border border-green-400/30 rounded-lg py-2 hover:bg-green-400/10">
                Lihat di GitHub ↗
            </a>
            @endif
        </div>

        {{-- Tags --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($tool->tags ?? [] as $tag)
                <span class="text-xs px-2.5 py-1 bg-white/5 border border-white/10 text-white/50 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
        </div>

        {{-- Tools serupa --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
            <h3 class="text-sm font-medium mb-3">Tools serupa</h3>
            @php
            $similar = \App\Models\Tool::where('category', $tool->category)
                ->where('id', '!=', $tool->id)->take(3)->get();
            @endphp
            @foreach($similar as $s)
            <a href="{{ route('tool.detail', $s->slug) }}"
               class="flex items-center gap-3 py-2.5 border-b border-white/5 last:border-0 hover:text-green-400 group">
                <div class="w-7 h-7 rounded-md flex items-center justify-center text-xs text-white shrink-0"
                     style="background:{{ $s->icon_color }}">{{ $s->icon_text }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white/70 group-hover:text-green-400">{{ $s->name }}</p>
                    <p class="text-xs text-white/30">{{ $s->language }}</p>
                </div>
                <span class="text-xs text-yellow-400">&#9733; {{ $s->rating }}</span>
            </a>
            @endforeach
            @if($similar->isEmpty())
            <p class="text-xs text-white/30">Tidak ada tools serupa.</p>
            @endif
        </div>
    </div>
</div>

{{-- SECTION KOMENTAR --}}
<div class="max-w-5xl mx-auto px-6 pb-10">
    <div class="col-span-2">

        {{-- Notif --}}
        @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-400/15 border border-green-400/30 text-green-400 text-sm rounded-lg">
            ✓ {{ session('success') }}
        </div>
        @endif

        {{-- Form komentar --}}
        @auth
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6 mb-6">
            <h2 class="text-base font-medium mb-4">Tulis Komentar</h2>
            <form method="POST" action="{{ route('comment.tool', $tool->slug) }}">
                @csrf
                {{-- Rating --}}
                <div class="mb-3">
                    <label class="text-xs text-white/40 mb-2 block">Rating (opsional)</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                            <span class="text-2xl peer-checked:text-yellow-400 text-white/20 hover:text-yellow-300">★</span>
                        </label>
                        @endfor
                    </div>
                </div>
                {{-- Komentar --}}
                <div class="mb-3">
                    <textarea name="body" rows="3" required
                        placeholder="Tulis komentar atau review kamu..."
                        class="w-full bg-white/5 border border-white/10 text-white text-sm px-4 py-2.5 rounded-lg placeholder-white/30 focus:outline-none focus:border-green-400/50 resize-none"></textarea>
                    @error('body')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-green-400 text-gray-900 font-medium px-4 py-2 rounded-lg text-sm hover:bg-green-300">
                    Kirim Komentar
                </button>
            </form>
        </div>
        @else
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6 mb-6 text-center">
            <p class="text-sm text-white/50 mb-3">Login untuk menulis komentar</p>
            <a href="{{ route('login') }}" class="text-sm bg-green-400 text-gray-900 font-medium px-4 py-2 rounded-lg hover:bg-green-300">Masuk</a>
        </div>
        @endauth

        {{-- List komentar --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <h2 class="text-base font-medium mb-4">
                Komentar
                <span class="text-white/30 text-sm ml-1">({{ $tool->comments->count() }})</span>
            </h2>

            @if($tool->comments->count())
            <div class="space-y-4">
                @foreach($tool->comments as $comment)
                <div class="flex gap-3 pb-4 border-b border-white/5 last:border-0">
                    <div class="w-8 h-8 rounded-full bg-green-400/20 flex items-center justify-center text-green-400 text-xs font-medium shrink-0">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-white">{{ $comment->user->name }}</span>
                                @if($comment->rating)
                                <span class="text-xs text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $comment->rating ? '★' : '☆' }}
                                    @endfor
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-white/30">{{ $comment->created_at->diffForHumans() }}</span>
                                @auth
                                @if(Auth::id() == $comment->user_id)
                                <form method="POST" action="{{ route('comment.destroy', $comment->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400/60 hover:text-red-400">Hapus</button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        </div>
                        <p class="text-sm text-white/60 leading-relaxed">{{ $comment->body }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-white/30 text-center py-4">Belum ada komentar. Jadilah yang pertama!</p>
            @endif
        </div>
    </div>
</div>
</body>
</html>