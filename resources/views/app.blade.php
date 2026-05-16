<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DataCamp Tutorials')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dc: {
                            purple: '#5624d0',
                            green:  '#03ef62',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 flex items-center h-14 gap-6">
            <a href="{{ route('tutorials.index') }}" class="font-extrabold text-lg flex items-center gap-2">
                <span class="w-7 h-7 bg-black rounded-full flex items-center justify-center">
                    <span class="border-t-[6px] border-b-[6px] border-l-[10px] border-transparent border-l-[#03ef62] ml-0.5"></span>
                </span>
                datacamp
            </a>
            <span class="text-gray-400 text-sm">/ Tutorials</span>
            <div class="ml-auto flex items-center gap-3">
                {{-- Tombol trigger scrape --}}
                <form action="{{ route('tutorials.scrape') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-dc-purple text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-purple-800 transition"
                        onclick="return confirm('Mulai scraping? Proses ini memakan waktu cukup lama.')">
                        🔄 Scrape Data
                    </button>
                </form>
                {{-- Tombol cek status --}}
                <button onclick="checkStatus()"
                    class="border border-gray-300 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                    📊 Status
                </button>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
                ✅ {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-6 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3">
                ❌ {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Status modal (hidden) --}}
    <div id="status-modal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 w-96 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg">Status Scraping</h3>
                <button onclick="document.getElementById('status-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <div id="status-content" class="space-y-3 text-sm">
                <p class="text-gray-500">Memuat...</p>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <script>
        async function checkStatus() {
            document.getElementById('status-modal').classList.remove('hidden');
            document.getElementById('status-content').innerHTML = '<p class="text-gray-500">Memuat...</p>';
            try {
                const res  = await fetch('{{ route("tutorials.status") }}');
                const data = await res.json();
                const pct  = data.total > 0 ? Math.round(data.scraped / data.total * 100) : 0;
                document.getElementById('status-content').innerHTML = `
                    <div class="flex justify-between"><span class="text-gray-500">Status</span>
                        <span class="font-semibold ${data.running ? 'text-yellow-600' : data.done ? 'text-green-600' : 'text-gray-600'}">
                            ${data.running ? '⏳ Berjalan' : data.done ? '✅ Selesai' : '💤 Idle'}
                        </span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Pesan</span><span class="font-medium">${data.message}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Progress</span><span class="font-medium">${data.scraped} / ${data.total}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Gagal</span><span class="font-medium text-red-500">${data.failed}</span></div>
                    ${data.elapsed_seconds ? `<div class="flex justify-between"><span class="text-gray-500">Waktu berjalan</span><span class="font-medium">${Math.floor(data.elapsed_seconds/60)}m ${data.elapsed_seconds%60}s</span></div>` : ''}
                    ${data.total > 0 ? `<div class="mt-2 bg-gray-100 rounded-full h-2"><div class="bg-dc-green h-2 rounded-full transition-all" style="width:${pct}%"></div></div><p class="text-right text-xs text-gray-400">${pct}%</p>` : ''}
                `;
            } catch(e) {
                document.getElementById('status-content').innerHTML = '<p class="text-red-500">Gagal terhubung ke API</p>';
            }
        }
    </script>
</body>
</html>