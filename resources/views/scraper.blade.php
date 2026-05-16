<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraper - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{background:#0a0e1a}</style>
</head>
<body class="text-white min-h-screen">
<x-navbar />

<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-medium mb-2">Data Scraper</h1>
    <p class="text-sm text-white/50 mb-8">Ambil data DataCamp tools dari GitHub dan Hugging Face</p>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-400/15 border border-green-400/30 text-green-400 text-sm rounded-lg">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-400/15 border border-red-400/30 text-red-400 text-sm rounded-lg">
        ✗ {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-4">

        {{-- Scrape GitHub --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-lg">⚡</div>
                <div>
                    <h3 class="text-sm font-medium">GitHub Scraper</h3>
                    <p class="text-xs text-white/40">Scraping repo DataCamp dari GitHub API</p>
                </div>
            </div>
            <form method="POST" action="{{ route('scraper.run') }}">
                @csrf
                <input type="hidden" name="source" value="github">
                <button type="submit" class="w-full bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300">
                    Jalankan GitHub Scraper
                </button>
            </form>
        </div>

        {{-- Scrape HuggingFace --}}
        <div class="bg-gray-900 border border-white/10 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-lg">🤗</div>
                <div>
                    <h3 class="text-sm font-medium">Hugging Face Scraper</h3>
                    <p class="text-xs text-white/40">Scraping model AI dari Hugging Face</p>
                </div>
            </div>
            <form method="POST" action="{{ route('scraper.run') }}">
                @csrf
                <input type="hidden" name="source" value="huggingface">
                <button type="submit" class="w-full bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300">
                    Jalankan HuggingFace Scraper
                </button>
            </form>
        </div>

        {{-- Scrape All --}}
        <div class="bg-gray-900 border border-green-400/20 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-400/10 flex items-center justify-center text-lg">🚀</div>
                <div>
                    <h3 class="text-sm font-medium">Scrape Semua</h3>
                    <p class="text-xs text-white/40">Jalankan semua scraper sekaligus</p>
                </div>
            </div>
            <form method="POST" action="{{ route('scraper.run') }}">
                @csrf
                <input type="hidden" name="source" value="all">
                <button type="submit" class="w-full bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300">
                    Jalankan Semua Scraper
                </button>
            </form>
        </div>

        {{-- Info --}}
        <div class="bg-white/3 border border-white/8 rounded-xl p-5">
            <h3 class="text-xs font-medium text-white/50 uppercase tracking-widest mb-3">Cara Pakai</h3>
            <ol class="space-y-2 text-xs text-white/40">
                <li>1. Buka terminal baru</li>
                <li>2. Masuk ke folder scraper: <code class="text-green-400">cd scraper</code></li>
                <li>3. Jalankan Python API: <code class="text-green-400">uvicorn main:app --reload --port 8001</code></li>
                <li>4. Kembali ke halaman ini dan klik tombol scraper</li>
                <li>5. Data otomatis tersimpan ke database</li>
            </ol>
        </div>
    </div>

    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('katalog') }}" class="text-sm text-green-400 hover:text-green-300">← Lihat Katalog</a>
        <p class="text-xs text-white/30">Total tools: {{ \App\Models\Tool::count() }}</p>
    </div>
</div>

</body>
</html>