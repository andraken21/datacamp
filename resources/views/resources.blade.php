<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
   <style>
        body{background:#0a0e1a}
        .card:hover{border-color:rgba(55,232,160,0.4)!important}
        select option{background:#111827;color:#fff}
        select{color:#fff}
</style>
</head>
<body class="text-white min-h-screen">
<x-navbar />

<div class="px-6 pt-8 pb-4">
    <h1 class="text-2xl font-medium mb-1">Resources</h1>
    <p class="text-sm text-white/50">Artikel, tutorial, dan panduan untuk belajar AI agent</p>
</div>

{{-- SEARCH & FILTER --}}
<div class="flex gap-3 px-6 pb-6">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="search-res" placeholder="Cari artikel, tutorial..."
            class="w-full bg-white/5 border border-white/10 text-white text-sm pl-10 pr-4 py-2 rounded-lg placeholder-white/30 focus:outline-none focus:border-green-400/50"
            oninput="filterResources()">
    </div>
    <select id="filter-cat" onchange="filterResources()" class="bg-white/5 border border-white/10 text-white text-sm px-3 py-2 rounded-lg">
        <option value="all">Semua kategori</option>
        <option value="Tutorial">Tutorial</option>
        <option value="Artikel">Artikel</option>
        <option value="Perbandingan">Perbandingan</option>
        <option value="List">List</option>
    </select>
</div>

<div class="px-6 pb-10">
    <p class="text-xs text-white/35 mb-4">Menampilkan <span id="count" class="text-green-400">6</span> artikel</p>
    <div class="grid grid-cols-3 gap-4" id="resources-grid">
        @php
        $resources = [
            ['title'=>'Panduan Lengkap LangChain 2025','category'=>'Tutorial','time'=>'15 menit baca','color'=>'#1a1060','icon'=>'LC','desc'=>'Pelajari cara membangun aplikasi AI dengan LangChain dari dasar hingga mahir.'],
            ['title'=>'Apa itu AI Agent? Penjelasan Lengkap','category'=>'Artikel','time'=>'8 menit baca','color'=>'#0d2b20','icon'=>'AI','desc'=>'Memahami konsep AI agent, cara kerja, dan penerapannya di dunia nyata.'],
            ['title'=>'CrewAI vs AutoGen: Mana yang Lebih Baik?','category'=>'Perbandingan','time'=>'12 menit baca','color'=>'#1a1a40','icon'=>'VS','desc'=>'Perbandingan mendalam antara CrewAI dan AutoGen untuk multi-agent systems.'],
            ['title'=>'Membangun RAG Pipeline dengan LlamaIndex','category'=>'Tutorial','time'=>'20 menit baca','color'=>'#2a1a00','icon'=>'LI','desc'=>'Step-by-step membangun RAG pipeline yang production-ready.'],
            ['title'=>'Top 10 AI Agent Tools 2025','category'=>'List','time'=>'10 menit baca','color'=>'#0a2020','icon'=>'T10','desc'=>'Daftar tools AI agent terbaik yang wajib dikuasai di tahun 2025.'],
            ['title'=>'Monitoring LLM Apps dengan LangSmith','category'=>'Tutorial','time'=>'18 menit baca','color'=>'#2a1010','icon'=>'LS','desc'=>'Cara setup monitoring, tracing, dan evaluasi untuk aplikasi LLM kamu.'],
            ['title'=>'Pengantar AutoGen untuk Pemula','category'=>'Tutorial','time'=>'14 menit baca','color'=>'#1a1a40','icon'=>'AG','desc'=>'Mulai belajar AutoGen dari nol dengan panduan step-by-step ini.'],
            ['title'=>'LangGraph: Stateful AI Agent','category'=>'Artikel','time'=>'11 menit baca','color'=>'#1a1060','icon'=>'LG','desc'=>'Memahami LangGraph dan cara membuat agent dengan state management.'],
            ['title'=>'5 Framework AI Agent Terbaik 2025','category'=>'List','time'=>'9 menit baca','color'=>'#0d2b20','icon'=>'F5','desc'=>'Rangkuman framework AI agent terpopuler beserta kelebihan dan kekurangannya.'],
        ];
        @endphp

        @foreach($resources as $res)
        <div class="card bg-gray-900 border border-white/10 rounded-xl overflow-hidden cursor-pointer transition-colors resource-card"
             data-title="{{ strtolower($res['title']) }}"
             data-category="{{ $res['category'] }}">
            <div class="h-24 flex items-center justify-center text-2xl font-bold text-white/25"
                 style="background: linear-gradient(135deg, {{ $res['color'] }}, #0a0e1a)">
                {{ $res['icon'] }}
            </div>
            <div class="p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs px-2 py-0.5 rounded bg-green-400/10 text-green-400">{{ $res['category'] }}</span>
                    <span class="text-xs text-white/30">{{ $res['time'] }}</span>
                </div>
                <h3 class="text-sm font-medium text-white mb-2 line-clamp-2">{{ $res['title'] }}</h3>
                <p class="text-xs text-white/40 line-clamp-2">{{ $res['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div id="no-results" class="hidden text-center py-16 text-white/35 text-sm">Tidak ada artikel yang cocok.</div>
</div>

<script>
function filterResources() {
    const q = document.getElementById('search-res').value.toLowerCase();
    const cat = document.getElementById('filter-cat').value;
    const cards = document.querySelectorAll('.resource-card');
    let count = 0;

    cards.forEach(card => {
        const title = card.dataset.title;
        const category = card.dataset.category;
        const matchQ = !q || title.includes(q);
        const matchCat = cat === 'all' || category === cat;

        if (matchQ && matchCat) {
            card.style.display = 'block';
            count++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('count').textContent = count;
    document.getElementById('no-results').style.display = count === 0 ? 'block' : 'none';
}
</script>

</body>
</html>