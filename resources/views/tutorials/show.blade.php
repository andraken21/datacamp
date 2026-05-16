@extends('layouts.app')

@section('title', ($tutorial['title'] ?? 'Tutorial') . ' — DataCamp')

@section('content')

{{-- Back --}}
<div class="mb-6">
    <a href="{{ route('tutorials.index') }}"
       class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
        ← Kembali ke daftar
    </a>
</div>

<div class="max-w-3xl">

    {{-- Category --}}
    @if(!empty($tutorial['category']))
        <span class="inline-block bg-purple-50 text-dc-purple text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wide">
            {{ $tutorial['category'] }}
        </span>
    @endif

    {{-- Judul --}}
    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
        {{ $tutorial['title'] }}
    </h1>

    {{-- Meta bar --}}
    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 pb-5 border-b border-gray-200 mb-8">
        @if(!empty($tutorial['author']))
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-purple-100 text-dc-purple font-bold text-xs flex items-center justify-center">
                    {{ strtoupper(substr($tutorial['author'], 0, 1)) }}
                </span>
                <span class="font-medium text-gray-700">{{ $tutorial['author'] }}</span>
            </div>
        @endif
        @if(!empty($tutorial['date_published']))
            <span>📅 {{ $tutorial['date_published'] }}</span>
        @endif
        @if(!empty($tutorial['read_time']))
            <span>⏱ {{ $tutorial['read_time'] }} read</span>
        @endif
        <a href="{{ $tutorial['url'] }}" target="_blank"
           class="ml-auto text-dc-purple hover:underline font-medium text-xs">
            Lihat di DataCamp ↗
        </a>
    </div>

    {{-- Deskripsi --}}
    @if(!empty($tutorial['description']))
        <p class="text-gray-600 text-base leading-relaxed mb-8 bg-gray-50 border-l-4 border-dc-purple pl-4 py-3 rounded-r-lg">
            {{ $tutorial['description'] }}
        </p>
    @endif

    {{-- Konten --}}
    @if(!empty($tutorial['content']))
        <div class="prose prose-gray max-w-none text-sm leading-relaxed space-y-3">
            @foreach(explode("\n", $tutorial['content']) as $line)
                @if(str_starts_with($line, '## '))
                    <h2 class="text-lg font-bold text-gray-900 mt-8 mb-2 pt-4 border-t border-gray-100">
                        {{ substr($line, 3) }}
                    </h2>
                @elseif(trim($line) !== '')
                    <p class="text-gray-700">{{ $line }}</p>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            <p>Konten tidak tersedia.</p>
            <a href="{{ $tutorial['url'] }}" target="_blank"
               class="text-dc-purple hover:underline text-sm">
                Baca langsung di DataCamp ↗
            </a>
        </div>
    @endif

</div>

@endsection