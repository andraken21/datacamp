<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harga - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{background:#0a0e1a}</style>
</head>
<body class="text-white min-h-screen">
<x-navbar />

<div class="max-w-5xl mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-medium mb-3">Pilih Plan yang Tepat</h1>
        <p class="text-white/50 text-sm">Mulai gratis, upgrade kapan saja</p>
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- Free --}}
        <div class="bg-gray-900 border border-white/10 rounded-2xl p-6">
            <h2 class="text-base font-medium mb-1">Free</h2>
            <p class="text-xs text-white/40 mb-4">Untuk pemula</p>
            <div class="mb-6">
                <span class="text-3xl font-medium">Rp 0</span>
                <span class="text-white/40 text-sm">/bulan</span>
            </div>
            <a href="{{ route('register') }}" class="block w-full text-center border border-white/20 text-white py-2.5 rounded-lg text-sm hover:border-white mb-6">Mulai Gratis</a>
            <ul class="space-y-3 text-sm text-white/60">
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Akses kursus gratis</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Katalog tools lengkap</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Simpan tools favorit</li>
                <li class="flex items-center gap-2"><span class="text-white/20">✗</span> Kursus PRO</li>
                <li class="flex items-center gap-2"><span class="text-white/20">✗</span> Sertifikasi</li>
            </ul>
        </div>

        {{-- Pro --}}
        <div class="bg-gray-900 border border-green-400/40 rounded-2xl p-6 relative">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-green-400 text-gray-900 text-xs font-medium px-3 py-1 rounded-full">Paling Populer</div>
            <h2 class="text-base font-medium mb-1">Pro</h2>
            <p class="text-xs text-white/40 mb-4">Untuk profesional</p>
            <div class="mb-6">
                <span class="text-3xl font-medium">Rp 150k</span>
                <span class="text-white/40 text-sm">/bulan</span>
            </div>
            <a href="{{ route('register') }}" class="block w-full text-center bg-green-400 text-gray-900 font-medium py-2.5 rounded-lg text-sm hover:bg-green-300 mb-6">Coba 7 Hari Gratis</a>
            <ul class="space-y-3 text-sm text-white/60">
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Semua fitur Free</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Akses semua kursus PRO</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Sertifikasi resmi</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Proyek nyata</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Prioritas support</li>
            </ul>
        </div>

        {{-- Teams --}}
        <div class="bg-gray-900 border border-white/10 rounded-2xl p-6">
            <h2 class="text-base font-medium mb-1">Teams</h2>
            <p class="text-xs text-white/40 mb-4">Untuk tim & perusahaan</p>
            <div class="mb-6">
                <span class="text-3xl font-medium">Rp 300k</span>
                <span class="text-white/40 text-sm">/user/bulan</span>
            </div>
            <a href="#" class="block w-full text-center border border-white/20 text-white py-2.5 rounded-lg text-sm hover:border-white mb-6">Hubungi Kami</a>
            <ul class="space-y-3 text-sm text-white/60">
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Semua fitur Pro</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Dashboard tim</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Laporan progress</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Custom kursus</li>
                <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Dedicated support</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>