<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgentCamp - Pelajari AI Agent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0a0e1a; }
        .grid-bg {
            background-image: linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .card:hover { border-color: rgba(55,232,160,0.4) !important; }
        input:focus { border-color: #37e8a0 !important; outline: none; }
    </style>
</head>
<body class="text-white min-h-screen">

{{-- BANNER --}}
<div class="flex items-center justify-between px-6 py-2.5" style="background:linear-gradient(90deg,#1a1060,#0d1f50)">
    <p class="text-xs text-white/70"><strong class="text-white">AI AGENTS IN THE ENTERPRISE</strong> — Pelajari cara tim modern mendelegasikan kerja ke AI agent.</p>
    <a href="{{ route('register') }}" class="text-xs bg-green-400 text-gray-900 font-medium px-3 py-1.5 rounded-md whitespace-nowrap hover:bg-green-300">Daftar Gratis</a>
</div>

{{-- NAVBAR --}}
<nav class="flex items-center justify-between px-6 py-3 border-b border-white/10">
    <a href="/" class="text-green-400 text-base font-medium">&#9632; agentcamp</a>
    <div class="flex items-center gap-6 text-sm text-white/60">
        <a href="{{ route('katalog') }}" class="hover:text-white">Katalog</a>
        <a href="#" class="hover:text-white">Resources</a>
        <a href="#" class="hover:text-white">Harga</a>
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

{{-- HERO --}}
<div class="grid-bg relative">
    <div class="grid grid-cols-2 gap-8 px-6 py-16 max-w-6xl mx-auto items-center min-h-[480px]">

        {{-- Kiri --}}
        <div>
            <div class="inline-flex items-center gap-2 bg-green-400/10 border border-green-400/20 text-green-400 text-xs px-3 py-1.5 rounded-full mb-6">
                <div class="w-1.5 h-1.5 bg-green-400 rounded-full"></div>
                Platform AI Agent #1 di Indonesia
            </div>
            <h1 class="text-5xl font-medium leading-tight mb-4">
                Pelajari <span class="text-green-400">AI Agent</span><br>& Otomasi Modern
            </h1>
            <p class="text-white/55 text-base leading-relaxed mb-8 max-w-md">
                Kuasai LangChain, AutoGen, CrewAI, dan framework AI agent lainnya melalui kursus interaktif dan proyek nyata.
            </p>
            <div class="flex gap-3 mb-10">
                <a href="{{ route('register') }}" class="bg-green-400 text-gray-900 font-medium px-5 py-2.5 rounded-lg text-sm hover:bg-green-300">Mulai Belajar Gratis</a>
                <a href="{{ route('katalog') }}" class="border border-white/30 text-white px-5 py-2.5 rounded-lg text-sm hover:border-white">Lihat Katalog</a>
            </div>
            <div class="flex gap-8">
                <div>
                    <p class="text-xl font-medium text-white">1,200+</p>
                    <p class="text-xs text-white/40">Tools & framework</p>
                </div>
                <div>
                    <p class="text-xl font-medium text-white">340+</p>
                    <p class="text-xs text-white/40">Kursus tersedia</p>
                </div>
                <div>
                    <p class="text-xl font-medium text-white">50K+</p>
                    <p class="text-xs text-white/40">Pengguna aktif</p>
                </div>
            </div>
        </div>

        {{-- Kanan: Form Register --}}
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm ml-auto">
            <h2 class="text-gray-900 text-lg font-medium text-center mb-5">Buat Akun Gratis</h2>

            <div class="grid grid-cols-2 gap-2 mb-5">
                <button class="flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2 text-sm text-gray-600 hover:bg-gray-50">
                    <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </button>
                <button class="flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2 text-sm text-gray-600 hover:bg-gray-50">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </button>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400">atau</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Nama kamu" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800">
                </div>
                <div class="mb-3">
                    <label class="block text-xs text-gray-500 mb-1">Alamat Email</label>
                    <input type="email" name="email" placeholder="nama@email.com" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800">
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-500 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Min. 8 karakter" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800">
                    <input type="hidden" name="password_confirmation" id="pc">
                </div>
                <button type="submit" onclick="document.getElementById('pc').value=this.form.password.value"
                    class="w-full bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300">
                    Mulai Belajar Gratis
                </button>
            </form>

            <p class="text-xs text-gray-400 text-center mt-4 leading-relaxed">
                Dengan mendaftar, kamu menyetujui <a href="#" class="text-green-600">Syarat Layanan</a> dan <a href="#" class="text-green-600">Kebijakan Privasi</a> kami.
            </p>

            <p class="text-center text-xs text-gray-500 mt-3">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-green-600 font-medium">Masuk</a>
            </p>
        </div>
    </div>
</div>

{{-- KATEGORI --}}
<div class="px-6 py-8 border-t border-white/8">
    <p class="text-xs text-white/35 uppercase tracking-widest mb-4">Jelajahi kategori</p>
    <div class="flex flex-wrap gap-2">
        @foreach(['AI Agent Frameworks','Memory & RAG','Tool Use','Multi-Agent','Planning','Monitoring','Autonomous Agents'] as $cat)
        <a href="{{ route('katalog') }}"
           class="bg-white/5 border border-white/10 text-white/65 text-xs px-3 py-1.5 rounded-full hover:bg-green-400/10 hover:border-green-400/30 hover:text-green-400">
            {{ $cat }}
        </a>
        @endforeach
    </div>
</div>

{{-- TOOLS POPULER --}}
<div class="px-6 py-8 border-t border-white/8">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-base font-medium">Tools & Framework Populer</h2>
        <a href="{{ route('katalog') }}" class="text-xs text-green-400 hover:text-green-300">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @php $tools = \App\Models\Tool::where('is_featured', true)->take(3)->get(); @endphp
        @foreach($tools as $tool)
        <a href="{{ route('tool.detail', $tool->slug) }}"
           class="card block bg-gray-900 border border-white/10 rounded-xl p-5 transition-colors">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-xs font-medium text-white mb-3"
                 style="background:{{ $tool->icon_color }}">{{ $tool->icon_text }}</div>
            <h3 class="text-sm font-medium text-white mb-1">{{ $tool->name }}</h3>
            <p class="text-xs text-white/40 leading-relaxed mb-3 line-clamp-2">{{ $tool->description }}</p>
            <div class="flex items-center justify-between pt-3 border-t border-white/8">
                <span class="text-xs text-white/30">{{ $tool->language }}</span>
                <span class="text-xs text-yellow-400">&#9733; {{ $tool->rating }}</span>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- FOOTER --}}
<footer class="border-t border-white/8 px-6 py-6 mt-4">
    <div class="flex items-center justify-between">
        <p class="text-green-400 font-medium">&#9632; agentcamp</p>
        <p class="text-xs text-white/30">© 2026 AgentCamp. All rights reserved.</p>
        <div class="flex gap-4 text-xs text-white/40">
            <a href="#" class="hover:text-white">Tentang</a>
            <a href="#" class="hover:text-white">Syarat Layanan</a>
            <a href="#" class="hover:text-white">Privasi</a>
        </div>
    </div>
</footer>

</body>
</html>