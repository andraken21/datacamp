<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Alongs - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
<<<<<<< HEAD
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; text-decoration:none; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
        .sidebar-link svg { width:18px; height:18px; opacity:0.6; }
=======
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .filter-btn { padding:6px 14px; border-radius:999px; font-size:13px; border:1px solid #e0e0e0; background:white; cursor:pointer; color:#444; text-decoration:none; display:inline-block; }
        .filter-btn.active { background:#05192D; color:white; border-color:#05192D; }
        .filter-btn:hover:not(.active) { background:#f0f0f0; }
    </style>
</head>
<body>
<x-navbar />
<div class="flex min-h-screen">
    <x-sidebar />
    <main class="flex-1">

        {{-- Hero Banner --}}
        <div class="p-8 flex items-center justify-between" style="background:#05192D">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-2xl font-bold text-white">Code Alongs</h1>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background:#ec4899;color:white">💠 Solve real-world problems</span>
                </div>
                <p class="text-sm text-gray-300 max-w-lg">Our instructors will guide you through a vast selection of real world problems from start to finish.</p>
            </div>
            <div class="hidden lg:block">
                <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
                    <circle cx="40" cy="70" r="16" fill="#ec4899" opacity="0.9"/>
                    <circle cx="90" cy="30" r="10" fill="none" stroke="#ec4899" stroke-width="1.5" opacity="0.5"/>
                    <circle cx="90" cy="70" r="10" fill="none" stroke="#ec4899" stroke-width="1.5" opacity="0.5"/>
                    <path d="M40 70 Q60 40 90 30" stroke="#ec4899" stroke-width="1.5" fill="none" stroke-dasharray="4 2"/>
                    <text x="25" y="74" font-size="7" fill="white" font-weight="bold">APPLY</text>
                </svg>
            </div>
        </div>

<<<<<<< HEAD
        {{-- Featured Section --}}
        <div class="p-6 mb-2" style="background:#0f1b2d">
            <p class="text-xs font-semibold text-pink-400 flex items-center gap-1 mb-2">💠 FEATURED CODE ALONGS</p>
            <h2 class="text-xl font-bold text-white mb-1">Become an AI Developer
                <span class="text-sm font-normal text-gray-400 ml-2">Taught by instructors from:</span>
                <span class="text-xs text-gray-400 ml-1">Microsoft · Pinecone · Imperial College London · Fidelity</span>
            </h2>
            @php
                $featured = [
                    ['title' => 'Building AI Systems with OpenAI & LangChain', 'desc' => 'Master the OpenAI API & LangChain packages and build AI proof of concepts along the way.', 'courses' => [
                        ['name' => 'Introduction to Large Language Models with GPT & LangChain', 'instructor' => 'James Chapman'],
                        ['name' => 'Prompt Engineering with GPT & LangChain', 'instructor' => 'James Chapman'],
                        ['name' => 'Building Multimodal AI Applications with LangChain & the OpenAI API', 'instructor' => 'James Chapman'],
                    ]],
                    ['title' => 'Building AI Systems with Hugging Face', 'desc' => 'Uncover the power of the open source AI ecosystem with HuggingFace.', 'courses' => [
                        ['name' => 'Using Open Source AI Models with Hugging Face', 'instructor' => 'Alara Dirik'],
                        ['name' => 'Building NLP Applications with HuggingFace', 'instructor' => 'Jacob Marquez'],
                        ['name' => 'Image Classification with Hugging Face', 'instructor' => 'Priyanka Asnani'],
                    ]],
                ];
            @endphp
            @foreach($featured as $feat)
            <div class="mb-6">
                <h3 class="text-base font-semibold text-white mb-1">{{ $feat['title'] }}</h3>
                <p class="text-xs text-gray-400 mb-3">{{ $feat['desc'] }}</p>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($feat['courses'] as $i => $course)
                    <div class="rounded-xl p-4 border border-white/10 hover:border-pink-400/40 transition-colors cursor-pointer" style="background:#1a2540">
                        <p class="text-sm font-medium text-white mb-4 line-clamp-2">{{ $course['name'] }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">{{ $course['instructor'] }}</span>
                            <span class="text-xs text-gray-500">{{ $i + 1 }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-6">
            {{-- Filter pills --}}
            @php $activeFilter = request('topic', 'all'); @endphp
            <div class="flex flex-wrap gap-2 mb-2">
                <a href="{{ route('code-alongs') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
                @foreach(['Python','SQL','Power BI','Tableau','Excel','Julia','Docker','Git','Business Intelligence','Spreadsheets','R','Artificial Intelligence','OpenAI'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach(['Generative AI','Azure','Java','ChatGPT','AWS','Airflow','AI Agents','Snowflake','Sigma'] as $topic)
                <a href="?topic={{ strtolower($topic) }}" class="filter-btn {{ $activeFilter==strtolower($topic) ? 'active' : '' }}">{{ $topic }}</a>
                @endforeach
                <span class="filter-btn">+3</span>
            </div>

            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500"><span class="font-semibold text-gray-900">198</span> Code-alongs</p>
                <select class="border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg focus:outline-none">
                    <option>Topics</option>
                </select>
            </div>

            {{-- Code Along Cards --}}
            @php
                $codealongs = [
                    ['title' => 'Create GPTs to Automate Go-To-Market Research', 'level' => 'Intermediate', 'color' => '#3b82f6'],
                    ['title' => 'Use AI Agents to Analyze Power Grid Optimization', 'level' => 'Advanced', 'color' => '#8b5cf6'],
                    ['title' => 'Build an AI-driven Dashboard', 'level' => 'Intermediate', 'color' => '#ec4899'],
                    ['title' => 'Automate Data Pipelines with Python', 'level' => 'Basic', 'color' => '#03EF62'],
                    ['title' => 'Build a RAG Chatbot with LangChain', 'level' => 'Advanced', 'color' => '#f59e0b'],
                    ['title' => 'Analyze Financial Data with SQL', 'level' => 'Basic', 'color' => '#10b981'],
                ];
            @endphp
            <div class="grid grid-cols-3 gap-4">
                @foreach($codealongs as $ca)
                <div class="card p-5 hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">CODE ALONG</p>
                    <h3 class="text-base font-bold text-gray-900 mb-4">{{ $ca['title'] }}</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:{{ $ca['color'] }}"></div>
                            <span class="text-sm text-gray-500">{{ $ca['level'] }}</span>
                        </div>
                        <a href="#" class="px-4 py-1.5 rounded-lg text-sm font-semibold border border-gray-200 hover:bg-gray-50 text-gray-700">Start</a>
=======
        {{-- Featured Section (dari DB: proyek Guided + is_featured / take pertama) --}}
        @php
            $featuredProyek = DB::table('proyek')
                ->join('level', 'proyek.level_id', '=', 'level.level_id')
                ->leftJoin('topik', 'proyek.topik_id', '=', 'topik.topik_id')
                ->select('proyek.*', 'level.nama_level', 'topik.nama_topik')
                ->where('proyek.tipe_proyek', 'Guided')
                ->orderBy('proyek.proyek_id')
                ->take(6)
                ->get();
        @endphp

        @if($featuredProyek->count() > 0)
        <div class="p-6 mb-2" style="background:#0f1b2d">
            <p class="text-xs font-semibold text-pink-400 flex items-center gap-1 mb-3">💠 FEATURED CODE ALONGS</p>
            <div class="grid grid-cols-3 gap-3">
                @foreach($featuredProyek as $i => $p)
                <div class="rounded-xl p-4 border border-white/10 hover:border-pink-400/40 transition-colors cursor-pointer" style="background:#1a2540">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">CODE ALONG</p>
                    <p class="text-sm font-medium text-white mb-4 line-clamp-2">{{ $p->judul }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">{{ $p->nama_topik ?? '-' }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full" style="background:#ec489920;color:#ec4899">
                            {{ $p->nama_level ?? 'Guided' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="p-6">

            @php
                $activeFilter = request('topic', 'all');
                $search = request('search', '');

                // Ambil topik dari proyek Guided
                $topikList = DB::table('proyek')
                    ->join('topik', 'proyek.topik_id', '=', 'topik.topik_id')
                    ->where('proyek.tipe_proyek', 'Guided')
                    ->select('topik.nama_topik')
                    ->distinct()
                    ->orderBy('topik.nama_topik')
                    ->pluck('nama_topik');

                // Query utama: proyek Guided dari DB
                $query = DB::table('proyek')
                    ->join('level', 'proyek.level_id', '=', 'level.level_id')
                    ->leftJoin('topik', 'proyek.topik_id', '=', 'topik.topik_id')
                    ->select('proyek.*', 'level.nama_level', 'topik.nama_topik')
                    ->where('proyek.tipe_proyek', 'Guided');

                if ($activeFilter !== 'all') {
                    $query->where('topik.nama_topik', 'like', '%' . $activeFilter . '%');
                }

                if ($search) {
                    $query->where('proyek.judul', 'like', '%' . $search . '%');
                }

                $codealongs = $query->orderBy('proyek.proyek_id')->get();

                $levelColors = [
                    'Basic'        => '#03EF62',
                    'Intermediate' => '#3b82f6',
                    'Advanced'     => '#8b5cf6',
                ];
            @endphp

            {{-- Filter Pills --}}
            <div class="flex flex-wrap gap-2 mb-5">
                <a href="{{ route('code-alongs') }}" class="filter-btn {{ $activeFilter=='all' ? 'active' : '' }}">All</a>
                @foreach($topikList as $topik)
                <a href="?topic={{ strtolower($topik) }}"
                   class="filter-btn {{ $activeFilter == strtolower($topik) ? 'active' : '' }}">
                    {{ $topik }}
                </a>
                @endforeach
            </div>

            {{-- Count + Search --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-900">{{ $codealongs->count() }}</span> Code-alongs
                </p>
                <form method="GET" action="{{ route('code-alongs') }}" class="flex items-center gap-3">
                    @if($activeFilter !== 'all')
                        <input type="hidden" name="topic" value="{{ $activeFilter }}">
                    @endif
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search code-alongs..."
                               class="border border-gray-200 bg-white text-sm pl-9 pr-4 py-2 rounded-lg w-52 focus:outline-none focus:border-pink-400">
                    </div>
                    <button type="submit" class="border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg hover:bg-gray-50">Cari</button>
                </form>
            </div>

            {{-- Code Along Cards --}}
            @if($codealongs->count() > 0)
            <div class="grid grid-cols-3 gap-4">
                @foreach($codealongs as $ca)
                @php $color = $levelColors[$ca->nama_level ?? ''] ?? '#ec4899'; @endphp
                <div class="card p-5 hover:shadow-md transition-shadow flex flex-col">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">CODE ALONG</p>
                    <h3 class="text-base font-bold text-gray-900 mb-3 flex-1">{{ $ca->judul }}</h3>
                    @if($ca->nama_topik)
                    <p class="text-xs text-gray-400 mb-3">{{ $ca->nama_topik }}
                        @if($ca->durasi_menit)
                        · {{ $ca->durasi_menit }} menit
                        @endif
                    </p>
                    @endif
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background:{{ $color }}"></div>
                            <span class="text-sm text-gray-500">{{ $ca->nama_level ?? 'Guided' }}</span>
                        </div>
                        @if($ca->url)
                        <a href="{{ $ca->url }}" target="_blank"
                           class="px-4 py-1.5 rounded-lg text-sm font-semibold text-white"
                           style="background:#05192D">
                            Start
                        </a>
                        @else
                        <span class="px-4 py-1.5 rounded-lg text-sm text-gray-400 border border-gray-200 cursor-not-allowed">
                            Start
                        </span>
                        @endif
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
                    </div>
                </div>
                @endforeach
            </div>
<<<<<<< HEAD
=======
            @else
            <div class="text-center py-16 text-gray-400">
                <svg class="mx-auto mb-4 w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
                <p class="text-sm">Belum ada code-along{{ $search ? " untuk \"$search\"" : '' }}.</p>
            </div>
            @endif

>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
        </div>
    </main>
</div>
</body>
</html>