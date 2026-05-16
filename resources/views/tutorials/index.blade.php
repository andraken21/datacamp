@extends('layouts.app')

@section('title', 'Tutorials — DataCamp')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
        <span>Tutorials</span>
        <span>·</span>
        <span class="font-semibold text-gray-700">{{ number_format($total) }} Tutorials</span>
    </div>

    {{-- Topic filter pills (mirip DataCamp) --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('tutorials.index') }}"
           class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
                  {{ empty($filters['category'] ?? '') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-gray-500' }}">
            All
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('tutorials.index', array_merge($filters, ['category' => $cat, 'page' => 1])) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
                      {{ ($filters['category'] ?? '') === $cat ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:border-gray-500' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Search + filter bar --}}
    <form method="GET" action="{{ route('tutorials.index') }}"
          class="flex flex-wrap gap-3 items-center">
        {{-- Pertahankan category filter --}}
        @if(!empty($filters['category']))
            <input type="hidden" name="category" value="{{ $filters['category'] }}">
        @endif

        <div class="flex-1 min-w-64 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                   placeholder="Search tutorials..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
        </div>

        <div class="min-w-40">
            <input type="text" name="author" value="{{ $filters['author'] ?? '' }}"
                   placeholder="Author..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
        </div>

        <button type="submit"
                class="bg-[#5624d0] text-white text-sm font-semibold px-5 py-2 rounded-lg hover:bg-purple-800 transition">
            Search
        </button>
        @if(array_filter($filters))
            <a href="{{ route('tutorials.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 underline">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Tutorial grid --}}
@if(count($tutorials) > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        @foreach($tutorials as $tutorial)
            <a href="{{ route('tutorials.show', $tutorial['slug']) }}"
               class="group bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:border-purple-200 transition-all duration-200 flex flex-col overflow-hidden">

                {{-- Card top: category + title --}}
                <div class="p-5 flex flex-col gap-3 flex-1">

                    {{-- Category badge --}}
                    @if(!empty($tutorial['category']))
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-md w-fit
                            {{ match(strtolower($tutorial['category'] ?? '')) {
                                'python'             => 'bg-blue-50 text-blue-700',
                                'sql'                => 'bg-orange-50 text-orange-700',
                                'r'                  => 'bg-green-50 text-green-700',
                                'machine learning'   => 'bg-yellow-50 text-yellow-700',
                                'data science'       => 'bg-indigo-50 text-indigo-700',
                                'excel', 'spreadsheets' => 'bg-emerald-50 text-emerald-700',
                                'power bi'           => 'bg-yellow-50 text-yellow-800',
                                'tableau'            => 'bg-blue-50 text-blue-800',
                                default              => 'bg-purple-50 text-purple-700',
                            } }}">
                            {{ $tutorial['category'] }}
                        </span>
                    @else
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-md w-fit bg-gray-100 text-gray-500">
                            TUTORIAL
                        </span>
                    @endif

                    {{-- Title --}}
                    <h2 class="font-bold text-gray-900 text-sm leading-snug line-clamp-3 group-hover:text-[#5624d0] transition-colors">
                        {{ $tutorial['title'] }}
                    </h2>

                    {{-- Description --}}
                    @if(!empty($tutorial['description']))
                        <p class="text-xs text-gray-500 line-clamp-2 flex-1 leading-relaxed">
                            {{ $tutorial['description'] }}
                        </p>
                    @endif
                </div>

                {{-- Card bottom: author + meta --}}
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        {{-- Avatar placeholder --}}
                        @if(!empty($tutorial['author']))
                            <div class="w-6 h-6 rounded-full bg-[#5624d0] flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-[10px] font-bold">
                                    {{ strtoupper(substr($tutorial['author'], 0, 1)) }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-600 truncate font-medium">{{ $tutorial['author'] }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400 flex-shrink-0">
                        @if(!empty($tutorial['read_time']))
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                                </svg>
                                {{ $tutorial['read_time'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($totalPages > 1)
        <div class="flex items-center justify-center gap-1 pb-8">
            @if($currentPage > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}"
                   class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            @php
                $start = max(1, $currentPage - 2);
                $end   = min($totalPages, $currentPage + 2);
            @endphp

            @if($start > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}"
                   class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">1</a>
                @if($start > 2)<span class="text-gray-400 px-1">...</span>@endif
            @endif

            @for($p = $start; $p <= $end; $p++)
                <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}"
                   class="w-9 h-9 flex items-center justify-center border rounded-lg text-sm font-medium transition
                          {{ $p === $currentPage ? 'bg-[#5624d0] text-white border-[#5624d0]' : 'border-gray-300 hover:bg-gray-50' }}">
                    {{ $p }}
                </a>
            @endfor

            @if($end < $totalPages)
                @if($end < $totalPages - 1)<span class="text-gray-400 px-1">...</span>@endif
                <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}"
                   class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">
                    {{ $totalPages }}
                </a>
            @endif

            @if($currentPage < $totalPages)
                <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}"
                   class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endif
        </div>
    @endif

@else
    {{-- Empty state --}}
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-lg mb-1">Belum ada data tutorial</h3>
        <p class="text-gray-500 text-sm">Klik tombol <strong>Scrape Data</strong> di navbar untuk mulai mengambil data.</p>
    </div>
@endif

@endsection