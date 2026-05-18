@extends('layouts.app')

@section('title', 'Tutorials — DataCamp')

@section('content')

{{-- Hero Banner --}}
<div class="p-8 flex items-center justify-between" style="background:#05192D">
    <div>
        <div class="flex items-center gap-3 mb-3">
            <h1 class="text-2xl font-bold text-white">Tutorials</h1>
            <span class="text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1" style="background:#03EF62;color:#05192D">
                📖 Learn by doing
            </span>
        </div>
        <p class="text-sm text-gray-300 max-w-lg">Develop your data science skills with tutorials. We cover everything from data visualizations in Tableau to version control in Git.</p>
    </div>
    <div class="hidden lg:block">
        <svg width="120" height="100" viewBox="0 0 120 100" fill="none">
            <circle cx="90" cy="30" r="16" fill="#03EF62" opacity="0.9"/>
            <circle cx="60" cy="60" r="10" fill="none" stroke="#03EF62" stroke-width="1.5" opacity="0.5"/>
            <circle cx="30" cy="70" r="10" fill="none" stroke="#03EF62" stroke-width="1.5" opacity="0.5"/>
            <circle cx="60" cy="80" r="10" fill="none" stroke="#03EF62" stroke-width="1.5" opacity="0.5"/>
            <path d="M30 70 Q45 40 60 60 Q75 80 90 30" stroke="#03EF62" stroke-width="1.5" fill="none" stroke-dasharray="4 2"/>
            <text x="78" y="34" font-size="7" fill="#05192D" font-weight="bold">LEARN</text>
        </svg>
    </div>
</div>

<div class="p-6">
    {{-- Filter pills row 1 --}}
    @php $activeFilter = request('category', ''); @endphp
    <div class="flex flex-wrap gap-2 mb-2">
        <a href="{{ route('tutorials.index') }}" class="filter-btn {{ empty($activeFilter) ? 'active' : '' }}">All</a>
        @foreach(['Python','SQL','Power BI','Tableau','Excel','Julia','Scala','Docker','Git','Business Intelligence','Artificial Intelligence','R','Spreadsheets'] as $cat)
        <a href="{{ route('tutorials.index', array_merge($filters, ['category' => $cat, 'page' => 1])) }}"
           class="filter-btn {{ $activeFilter === $cat ? 'active' : '' }}">{{ $cat }}</a>
        @endforeach
    </div>
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach(['Snowflake','AWS','ChatGPT','OpenAI','Large Language Mod...','Generative AI','Azure','Google Cloud Platform','Databricks'] as $cat)
        <a href="{{ route('tutorials.index', array_merge($filters, ['category' => $cat, 'page' => 1])) }}"
           class="filter-btn {{ $activeFilter === $cat ? 'active' : '' }}">{{ $cat }}</a>
        @endforeach
        <span class="filter-btn">+9</span>
    </div>

    {{-- Search + count bar --}}
    <form method="GET" action="{{ route('tutorials.index') }}" class="flex items-center gap-3 mb-6">
        @if(!empty($filters['category']))
            <input type="hidden" name="category" value="{{ $filters['category'] }}">
        @endif
        <p class="text-sm text-gray-500 shrink-0"><span class="font-semibold text-gray-900">{{ number_format($total) }}</span> Tutorials</p>
        <div class="flex-1"></div>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search tutorials..."
                class="border border-gray-200 bg-white text-sm pl-9 pr-4 py-2 rounded-lg w-52 focus:outline-none focus:border-green-400">
        </div>
        <select name="sort" onchange="this.form.submit()" class="border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg focus:outline-none">
            <option>Topics</option>
        </select>
        <button type="button" class="flex items-center gap-2 border border-gray-200 bg-white text-sm px-3 py-2 rounded-lg hover:bg-gray-50">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
            More filters
        </button>
    </form>

    {{-- Tutorial Grid --}}
    @if(count($tutorials) > 0)
    <div class="grid grid-cols-3 gap-4 mb-8">
        @foreach($tutorials as $tutorial)
        <a href="{{ route('tutorials.show', $tutorial['slug']) }}"
           class="bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow flex flex-col overflow-hidden">
            <div class="p-5 flex flex-col gap-3 flex-1">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">TUTORIAL</p>
                <h2 class="font-bold text-gray-900 text-sm leading-snug line-clamp-3">{{ $tutorial['title'] }}</h2>
                @if(!empty($tutorial['category']))
                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-md w-fit
                    {{ match(strtolower($tutorial['category'] ?? '')) {
                        'python' => 'bg-blue-50 text-blue-700',
                        'sql' => 'bg-orange-50 text-orange-700',
                        'r' => 'bg-green-50 text-green-700',
                        'excel', 'spreadsheets' => 'bg-emerald-50 text-emerald-700',
                        'power bi' => 'bg-yellow-50 text-yellow-800',
                        'tableau' => 'bg-blue-50 text-blue-800',
                        default => 'bg-purple-50 text-purple-700',
                    } }}">{{ $tutorial['category'] }}</span>
                @endif
                @if(!empty($tutorial['description']))
                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $tutorial['description'] }}</p>
                @endif
            </div>
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                @if(!empty($tutorial['author']))
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($tutorial['author'], 0, 1)) }}</span>
                    </div>
                    <span class="text-xs text-gray-600 truncate">{{ $tutorial['author'] }}</span>
                </div>
                @endif
                @if(!empty($tutorial['read_time']))
                <span class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                    {{ $tutorial['read_time'] }}
                </span>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($totalPages > 1)
    <div class="flex items-center justify-center gap-1 pb-8">
        @if($currentPage > 1)
        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        @endif
        @php $start = max(1, $currentPage - 2); $end = min($totalPages, $currentPage + 2); @endphp
        @for($p = $start; $p <= $end; $p++)
        <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
           class="w-9 h-9 flex items-center justify-center border rounded-lg text-sm font-medium transition {{ $p === $currentPage ? 'text-white border-gray-900' : 'border-gray-200 hover:bg-gray-50' }}"
           style="{{ $p === $currentPage ? 'background:#05192D' : '' }}">{{ $p }}</a>
        @endfor
        @if($currentPage < $totalPages)
        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="w-9 h-9 flex items-center justify-center border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif
    </div>
    @endif

    @else
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h3 class="font-bold text-gray-800 text-lg mb-1">Belum ada data tutorial</h3>
        <p class="text-gray-500 text-sm">Klik tombol <strong>Scrape Data</strong> untuk mulai mengambil data.</p>
    </div>
    @endif
</div>

@endsection