<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Tracks - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
        .sidebar-link svg { width:18px; height:18px; opacity:0.6; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .filter-btn { padding:6px 14px; border-radius:999px; font-size:13px; border:1px solid #e0e0e0; background:white; cursor:pointer; color:#444; }
        .filter-btn.active { background:#05192D; color:white; border-color:#05192D; }
        .filter-btn:hover:not(.active) { background:#f0f0f0; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">
    {{-- SIDEBAR --}}
    <x-sidebar />

    {{-- MAIN --}}
    <main class="flex-1">
        {{-- Hero Banner --}}
        <div class="p-8 flex items-center justify-between" style="background:#05192D">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-2xl font-bold text-white">Career tracks</h1>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background:#03EF62;color:#05192D">⚡ Zero to job ready</span>
                </div>
                <p class="text-sm text-gray-300 max-w-lg">Our career tracks are hand-picked by industry experts. You will learn all you need to start a new career in the data science field.</p>
            </div>
            <div class="hidden lg:block">
                <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
                    <polygon points="60,5 95,25 95,65 60,85 25,65 25,25" fill="none" stroke="#03EF62" stroke-width="2"/>
                    <polygon points="60,20 80,32 80,56 60,68 40,56 40,32" fill="none" stroke="#03EF62" stroke-width="1.5" opacity="0.6"/>
                    <polygon points="60,35 72,42 72,58 60,65 48,58 48,42" fill="#03EF62" opacity="0.3"/>
                </svg>
            </div>
        </div>

        <div class="p-6">
            {{-- Filter tabs --}}
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach(['all' => 'All', 'data-analyst' => 'Data Analyst', 'bi-analyst' => 'BI Analyst', 'data-engineer' => 'Data Engineer', 'data-scientist' => 'Data Scientist', 'ml-scientist' => 'ML Scientist', 'ml-engineer' => 'ML Engineer', 'ai-engineer' => 'AI Engineer', 'developer' => 'Developer', 'statistician' => 'Statistician'] as $val => $label)
                <a href="?filter={{ $val }}" class="filter-btn {{ $filter === $val ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>

            {{-- Count --}}
            <p class="text-sm text-gray-500 mb-4">{{ $tracks->count() }} Career tracks</p>

            {{-- Track Cards --}}
            <div class="grid grid-cols-2 gap-4">
                @forelse($tracks as $track)
                <div class="card p-6 hover:shadow-md transition-shadow">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">CAREER TRACK</p>
                    <h3 class="text-base font-bold text-gray-900 mb-2">{{ $track->nama_track }}</h3>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $track->deskripsi }}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($track->teknologi)
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium" style="background:#fff3cd;color:#856404">{{ $track->teknologi }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-1">
                                <div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-white"></div>
                                <div class="w-6 h-6 rounded-full bg-green-500 border-2 border-white"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $track->total_kursus ?? 0 }} Courses and Projects</span>
                        </div>
                        <a href="{{ route('tracks.show', $track->slug) }}" class="text-sm font-semibold px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">View Details</a>
                    </div>
                </div>
                @empty
                <div class="col-span-2 card p-10 text-center">
                    <p class="text-gray-400 text-sm">No career tracks found.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
</body>
</html>