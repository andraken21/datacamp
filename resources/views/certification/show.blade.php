{{-- resources/views/certification/show.blade.php --}}
@extends('layouts.app')

@section('title', $sertifikasi->nama . ' | DataCamp')

@php
    // Ambil konten_detail (JSON) dengan aman
    $detail   = $sertifikasi->konten_detail ?? [];
    $awarded  = $detail['certifications_awarded'] ?? 0;
    $shareable = $detail['shareable_certificate'] ?? false;

    // Topik: bisa dari relasi ATAU dari kolom JSON topik_tercakup
    $topikList = $sertifikasi->topik->pluck('topik')->toArray();
    if (empty($topikList) && !empty($sertifikasi->topik_tercakup)) {
        $topikList = $sertifikasi->topik_tercakup; // sudah di-cast ke array
    }

    // Gains: dari sertifikasi_section WHERE judul_section = 'what_you_gain'
    $gains = $sertifikasi->sections->where('judul_section', 'what_you_gain');

    // FAQs: dari sertifikasi_faq (relasi) ATAU konten_faq (JSON fallback)
    $faqs = $sertifikasi->faqs;
    $useFaqJson = $faqs->isEmpty() && !empty($sertifikasi->konten_faq);
@endphp

@section('content')

{{-- ═══ PROMO BANNER ═══════════════════════════════════════════ --}}
@if($sertifikasi->promo)
<div class="dc-promo-banner">
    <span class="dc-promo-icon">🚀</span>
    <span class="dc-promo-text">{!! $sertifikasi->promo !!}</span>
    <a href="#" class="dc-promo-link">Learn more ↗</a>
</div>
@endif

{{-- ═══ PAGE WRAPPER ════════════════════════════════════════════ --}}
<div class="dc-page">

    {{-- ── LEFT SIDEBAR ──────────────────────────────────────── --}}
    <aside class="dc-sidebar">
        <div class="dc-sidebar-label">CERTIFICATIONS</div>

        <div class="dc-sidebar-group">
            <div class="dc-sidebar-header">
                <span class="dc-sidebar-ico">🏆</span> Career
                <span class="dc-chevron">▾</span>
            </div>
        </div>

        <div class="dc-sidebar-group dc-sidebar-open">
            <div class="dc-sidebar-header dc-sidebar-active-hdr">
                <span class="dc-sidebar-ico">⌨</span> Technology
                <span class="dc-chevron">▴</span>
            </div>
            <ul class="dc-sidebar-sub">
                @foreach($sidebarList as $item)
                <li class="{{ $item->slug === $sertifikasi->slug ? 'dc-sub-active' : '' }}">
                    <a href="{{ route('certification.show', [$jenis, $item->slug]) }}">
                        {{ $item->nama }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="dc-sidebar-group">
            <div class="dc-sidebar-header">
                <span class="dc-sidebar-ico">📋</span> CPE
            </div>
        </div>
        <div class="dc-sidebar-group">
            <div class="dc-sidebar-header">
                <span class="dc-sidebar-ico">📚</span> Theory
            </div>
        </div>
        <div class="dc-sidebar-group">
            <div class="dc-sidebar-header">
                <span class="dc-sidebar-ico">🕐</span> History
            </div>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ──────────────────────────────────────── --}}
    <main class="dc-main">

        {{-- Top: Header + Right Card --}}
        <div class="dc-top-grid">

            {{-- LEFT: Detail Sertifikasi --}}
            <div class="dc-detail">

                {{-- Created by --}}
                <div class="dc-created-by">
                    <span>💵</span> Created by {{ $sertifikasi->dibuat_oleh ?? 'DataCamp' }}
                </div>

                {{-- Judul --}}
                <h1 class="dc-cert-title">{{ $sertifikasi->nama }}</h1>

                {{-- Topics Covered --}}
                @if(!empty($topikList))
                <div class="dc-topics-row">
                    <span class="dc-topics-lbl">Topics Covered</span>
                    @foreach($topikList as $topik)
                        <span class="dc-topic-tag">{{ $topik }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Deskripsi --}}
                <p class="dc-desc">{{ $sertifikasi->deskripsi }}</p>

                {{-- CTA --}}
                <div class="dc-cta-row">
                    <a href="{{ $sertifikasi->url ?? '#' }}" class="dc-btn-register" target="_blank">
                        Register for Certification
                    </a>
                    @if($awarded > 0)
                    <span class="dc-awarded">
                        {{ number_format($awarded) }}+ Certifications awarded
                    </span>
                    @endif
                </div>

                {{-- Meta Bar --}}
                <div class="dc-meta-bar">
                    <div class="dc-meta-item">
                        <svg class="dc-meta-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        <div>
                            <div class="dc-meta-lbl">Skill Level</div>
                            <div class="dc-meta-val">{{ $sertifikasi->tipe ?? 'Exam' }}</div>
                        </div>
                    </div>
                    <div class="dc-meta-item">
                        <svg class="dc-meta-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <div>
                            <div class="dc-meta-lbl">Time to Complete</div>
                            <div class="dc-meta-val">{{ $sertifikasi->panduan ?? '30 days' }}</div>
                        </div>
                    </div>
                    <div class="dc-meta-item">
                        <svg class="dc-meta-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <div>
                            <div class="dc-meta-lbl">Shareable Certificate</div>
                            <div class="dc-meta-val">{{ $shareable ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>
                </div>

                {{-- What You'll Gain --}}
                @if($gains->count())
                <div class="dc-gains-section">
                    <h2 class="dc-section-h2">What you'll gain</h2>
                    <ul class="dc-gains-list">
                        @foreach($gains as $g)
                        <li>
                            <span class="dc-check">✓</span>
                            {{ $g->konten }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>{{-- /.dc-detail --}}

            {{-- RIGHT: Cards --}}
            <div class="dc-right-cards">

                {{-- Interested Card --}}
                <div class="dc-card-interested">
                    <h3>Interested in Getting Certified?</h3>
                    <p>Register and complete the {{ $sertifikasi->nama }} Career Track to get your 50% off Microsoft Exam discount code.</p>
                    <a href="{{ $detail['career_track_url'] ?? '#' }}" class="dc-btn-career">
                        Start Career Track
                    </a>
                    <div class="dc-ms-partner">
                        <svg width="16" height="16" viewBox="0 0 21 21"><rect fill="#F25022" width="10" height="10"/><rect fill="#7FBA00" x="11" width="10" height="10"/><rect fill="#00A4EF" y="11" width="10" height="10"/><rect fill="#FFB900" x="11" y="11" width="10" height="10"/></svg>
                        Microsoft Partnership
                    </div>
                </div>

                {{-- Certificate Preview Card --}}
                <div class="dc-cert-preview">
                    <div class="dc-preview-inner">
                        <div class="dc-preview-title">
                            {{ strtoupper($sertifikasi->nama) }}
                        </div>
                        <div class="dc-preview-badge">datacamp</div>
                    </div>
                    <div class="dc-share-row">
                        <span class="dc-share-lbl">Share your certificate on</span>
                        <span class="dc-share-li">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#0a66c2"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                            LinkedIn
                        </span>
                    </div>
                </div>

            </div>{{-- /.dc-right-cards --}}
        </div>{{-- /.dc-top-grid --}}

        {{-- ── FAQ SECTION ────────────────────────────────────── --}}
        @if($faqs->count() || $useFaqJson)
        <div class="dc-faq-section">

            {{-- FAQ dari relasi sertifikasi_faq --}}
            @if($faqs->count())
                @foreach($faqs as $faq)
                <div class="dc-faq-item">
                    <h3 class="dc-faq-q">{{ $faq->pertanyaan }}</h3>
                    <div class="dc-faq-a">{!! $faq->jawaban !!}</div>
                </div>
                @endforeach

            {{-- Fallback: FAQ dari kolom JSON konten_faq --}}
            @elseif($useFaqJson)
                @foreach($sertifikasi->konten_faq as $faq)
                <div class="dc-faq-item">
                    <h3 class="dc-faq-q">{{ $faq['pertanyaan'] }}</h3>
                    <div class="dc-faq-a">{!! $faq['jawaban'] !!}</div>
                </div>
                @endforeach
            @endif

        </div>
        @endif

    </main>{{-- /.dc-main --}}

</div>{{-- /.dc-page --}}

@endsection

@push('styles')
<style>
/* ══════════════════════════════════════════════
   PROMO BANNER
══════════════════════════════════════════════ */
.dc-promo-banner {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #1c2035;
    color: #fff;
    padding: 10px 28px;
    font-size: 13.5px;
    min-height: 40px;
}
.dc-promo-icon { font-size: 16px; flex-shrink: 0; }
.dc-promo-text { flex: 1; }
.dc-promo-link {
    color: #93c5fd;
    text-decoration: none;
    font-size: 12.5px;
    white-space: nowrap;
    flex-shrink: 0;
}
.dc-promo-link:hover { text-decoration: underline; }

/* ══════════════════════════════════════════════
   PAGE LAYOUT
══════════════════════════════════════════════ */
.dc-page {
    display: flex;
    min-height: calc(100vh - 110px);
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    background: #fff;
    color: #111827;
}

/* ══════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════ */
.dc-sidebar {
    width: 198px;
    min-width: 198px;
    background: #fff;
    border-right: 1px solid #e8eaed;
    padding: 18px 0;
    position: sticky;
    top: 60px;
    height: calc(100vh - 60px);
    overflow-y: auto;
    flex-shrink: 0;
}
.dc-sidebar-label {
    font-size: 10px;
    font-weight: 700;
    color: #9ca3af;
    letter-spacing: .08em;
    padding: 0 16px 10px;
    text-transform: uppercase;
}
.dc-sidebar-group { margin: 1px 0; }
.dc-sidebar-header {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    font-size: 13.5px;
    color: #374151;
    cursor: pointer;
    border-radius: 7px;
    margin: 0 5px;
    transition: background .14s;
    user-select: none;
}
.dc-sidebar-header:hover { background: #f3f4f6; }
.dc-sidebar-active-hdr { color: #111827; font-weight: 500; }
.dc-sidebar-ico { font-size: 14px; }
.dc-chevron { margin-left: auto; font-size: 10px; color: #9ca3af; }

.dc-sidebar-sub {
    list-style: none;
    margin: 2px 0 4px;
    padding: 0;
}
.dc-sidebar-sub li { margin: 1px 5px; }
.dc-sidebar-sub li a {
    display: block;
    padding: 5px 14px 5px 35px;
    font-size: 12.5px;
    color: #6b7280;
    text-decoration: none;
    border-radius: 6px;
    transition: background .14s, color .14s;
    line-height: 1.4;
}
.dc-sidebar-sub li a:hover { background: #f3f4f6; color: #111827; }
.dc-sidebar-sub li.dc-sub-active a {
    color: #05c46b;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   MAIN
══════════════════════════════════════════════ */
.dc-main {
    flex: 1;
    padding: 32px 36px 60px;
    min-width: 0;
}

/* ══════════════════════════════════════════════
   TOP GRID (detail + right cards)
══════════════════════════════════════════════ */
.dc-top-grid {
    display: flex;
    gap: 28px;
    align-items: flex-start;
}
.dc-detail { flex: 1; min-width: 0; }

/* Created by */
.dc-created-by {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: #6b7280;
    margin-bottom: 10px;
}

/* Title */
.dc-cert-title {
    font-size: 25px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 14px;
    line-height: 1.3;
}

/* Topics */
.dc-topics-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 14px;
}
.dc-topics-lbl {
    font-size: 12.5px;
    color: #6b7280;
}
.dc-topic-tag {
    padding: 3px 11px;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    cursor: default;
}

/* Description */
.dc-desc {
    font-size: 14px;
    color: #374151;
    line-height: 1.65;
    margin-bottom: 22px;
}

/* CTA */
.dc-cta-row {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.dc-btn-register {
    background: #05c46b;
    color: #fff;
    padding: 11px 22px;
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: background .18s;
}
.dc-btn-register:hover { background: #04b360; color: #fff; }
.dc-awarded {
    font-size: 13px;
    color: #6b7280;
}

/* Meta Bar */
.dc-meta-bar {
    display: flex;
    gap: 36px;
    flex-wrap: wrap;
    padding: 18px 0;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 26px;
}
.dc-meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.dc-meta-svg {
    width: 24px;
    height: 24px;
    color: #9ca3af;
    flex-shrink: 0;
}
.dc-meta-lbl {
    font-size: 10.5px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 2px;
}
.dc-meta-val {
    font-size: 13.5px;
    font-weight: 600;
    color: #111827;
}

/* Gains */
.dc-gains-section { margin-bottom: 8px; }
.dc-section-h2 {
    font-size: 19px;
    font-weight: 700;
    margin: 0 0 14px;
    color: #111827;
}
.dc-gains-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.dc-gains-list li {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    font-size: 13.5px;
    color: #374151;
    padding: 5px 0;
    line-height: 1.5;
}
.dc-check {
    color: #05c46b;
    font-weight: 700;
    font-size: 15px;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ══════════════════════════════════════════════
   RIGHT CARDS
══════════════════════════════════════════════ */
.dc-right-cards {
    width: 258px;
    min-width: 258px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* Interested Card */
.dc-card-interested {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
}
.dc-card-interested h3 {
    font-size: 14.5px;
    font-weight: 700;
    margin: 0 0 9px;
    color: #111827;
}
.dc-card-interested p {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.55;
    margin-bottom: 15px;
}
.dc-btn-career {
    display: block;
    background: #111827;
    color: #fff;
    text-align: center;
    padding: 11px;
    border-radius: 6px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 13px;
    transition: background .18s;
}
.dc-btn-career:hover { background: #1f2937; color: #fff; }
.dc-ms-partner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 12.5px;
    color: #374151;
    font-weight: 500;
}

/* Certificate Preview */
.dc-cert-preview {
    background: #1c2035;
    border-radius: 10px;
    overflow: hidden;
}
.dc-preview-inner {
    padding: 22px 18px 16px;
    text-align: center;
}
.dc-preview-title {
    font-size: 11.5px;
    font-weight: 700;
    color: #fff;
    line-height: 1.45;
    margin-bottom: 14px;
    letter-spacing: .03em;
}
.dc-preview-badge {
    display: inline-block;
    border: 2px solid #05c46b;
    color: #05c46b;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 14px;
    border-radius: 20px;
}
.dc-share-row {
    background: rgba(255,255,255,.05);
    padding: 10px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}
.dc-share-lbl { font-size: 11px; color: #9ca3af; }
.dc-share-li {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    color: #60a5fa;
    font-weight: 600;
    cursor: pointer;
}

/* ══════════════════════════════════════════════
   FAQ SECTION
══════════════════════════════════════════════ */
.dc-faq-section {
    margin-top: 40px;
    border-top: 1px solid #e5e7eb;
    padding-top: 30px;
}
.dc-faq-item {
    margin-bottom: 26px;
    padding-bottom: 26px;
    border-bottom: 1px solid #f3f4f6;
}
.dc-faq-item:last-child { border-bottom: none; }
.dc-faq-q {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 9px;
}
.dc-faq-a {
    font-size: 13.5px;
    color: #374151;
    line-height: 1.65;
}
.dc-faq-a a,
.faq-link {
    color: #05c46b;
    text-decoration: none;
}
.dc-faq-a a:hover,
.faq-link:hover { text-decoration: underline; }

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media (max-width: 1024px) {
    .dc-top-grid { flex-direction: column; }
    .dc-right-cards { width: 100%; min-width: unset; flex-direction: row; }
    .dc-card-interested, .dc-cert-preview { flex: 1; }
}
@media (max-width: 768px) {
    .dc-sidebar { display: none; }
    .dc-main { padding: 20px 16px 40px; }
    .dc-right-cards { flex-direction: column; }
    .dc-meta-bar { gap: 20px; }
}
</style>
@endpush