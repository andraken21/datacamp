@extends('layouts.app')

@section('title', ($tutorial['judul'] ?? 'Tutorial') . ' — DataCamp')

@section('content')

<div class="mb-6">
    <a href="{{ route('tutorials.index') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
        ← Kembali ke daftar
    </a>
</div>

<div class="max-w-3xl">

    @if(!empty($tutorial['category']))
        <span class="inline-block bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-wide">
            {{ $tutorial['category'] }}
        </span>
    @endif

    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
        {{ $tutorial['judul'] }}
    </h1>

    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 pb-5 border-b border-gray-200 mb-8">
        @if(!empty($tutorial['author']))
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">
                    {{ strtoupper(substr($tutorial['author'], 0, 1)) }}
                </span>
                <span class="font-medium text-gray-700">{{ $tutorial['author'] }}</span>
            </div>
        @endif
        @if(!empty($tutorial['tanggal_terbit']))
            <span>📅 {{ $tutorial['tanggal_terbit'] }}</span>
        @endif
        @if(!empty($tutorial['waktu_baca_menit']))
            <span>⏱ {{ $tutorial['waktu_baca_menit'] }} min read</span>
        @endif
        @if(!empty($tutorial['url']))
            <a href="{{ $tutorial['url'] }}" target="_blank" class="ml-auto text-purple-700 hover:underline font-medium text-xs">
                Lihat di DataCamp ↗
            </a>
        @endif
    </div>

    @if(!empty($tutorial['content']))
        <div class="prose prose-gray max-w-none text-sm leading-relaxed">
            {!! $tutorial['content'] !!}
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            <p>Konten tidak tersedia.</p>
        </div>
    @endif

</div>

@endsection