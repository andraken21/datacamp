<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Practice — {{ $practiceSession->nama_session }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:    #0f1b2d;
            --orange:  #f97316;
            --orange2: #ea6a08;
            --white:   #ffffff;
            --gray-50: #f8fafc;
            --gray-100:#f1f5f9;
            --gray-200:#e2e8f0;
            --gray-300:#cbd5e1;
            --gray-500:#64748b;
            --gray-700:#334155;
            --green:   #22c55e;
            --green-bg:#f0fdf4;
            --green-border:#bbf7d0;
            --red:     #ef4444;
            --red-bg:  #fef2f2;
            --red-border:#fecaca;
            --radius:  14px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-100);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar-brand { font-weight: 800; color: var(--navy); font-size: 1.05rem; text-decoration: none; }
        .topbar-back {
            display: flex; align-items: center; gap: .4rem;
            font-size: .85rem; font-weight: 600; color: var(--gray-500);
            text-decoration: none; transition: color .2s;
        }
        .topbar-back:hover { color: var(--navy); }
        .topbar-back svg { width: 16px; height: 16px; }

        /* ── Hero result ── */
        .hero {
            background: var(--navy);
            padding: 3rem 2rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%;
            transform: translateX(-50%);
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(249,115,22,.08);
        }

        .score-ring {
            width: 120px; height: 120px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-direction: column;
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 1;
            background: conic-gradient(
                var(--orange) calc(var(--pct) * 1%),
                rgba(255,255,255,.1) 0
            );
            box-shadow: 0 0 0 6px rgba(249,115,22,.15);
        }
        .score-inner {
            width: 96px; height: 96px;
            border-radius: 50%;
            background: var(--navy);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
        }
        .score-num {
            font-size: 1.75rem; font-weight: 800;
            color: var(--white); line-height: 1;
        }
        .score-pct { font-size: .7rem; color: rgba(255,255,255,.5); font-weight: 600; }

        .hero-title {
            font-size: 1.4rem; font-weight: 800;
            color: var(--white); margin-bottom: .4rem;
            position: relative; z-index: 1;
        }
        .hero-sub {
            font-size: .875rem;
            color: rgba(255,255,255,.55);
            position: relative; z-index: 1;
        }

        /* ── XP earned ── */
        .xp-earned {
            display: inline-flex; align-items: center; gap: .5rem;
            background: rgba(249,115,22,.2);
            color: var(--orange);
            font-size: .8rem; font-weight: 700;
            padding: .4rem 1rem;
            border-radius: 100px;
            margin-top: 1rem;
            position: relative; z-index: 1;
        }
        .xp-earned svg { width: 15px; height: 15px; }

        /* ── Stats cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--gray-200);
            border-top: 1px solid var(--gray-200);
        }
        .stat-card {
            background: var(--white);
            padding: 1.25rem 1rem;
            text-align: center;
        }
        .stat-val {
            font-size: 1.6rem; font-weight: 800;
            color: var(--navy); line-height: 1;
        }
        .stat-val.green { color: var(--green); }
        .stat-val.red   { color: var(--red); }
        .stat-lbl {
            font-size: .72rem; color: var(--gray-500);
            margin-top: .3rem; font-weight: 500;
        }

        /* ── Content ── */
        .content {
            max-width: 720px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        .section-title {
            font-size: .72rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--gray-500); margin-bottom: 1rem;
        }

        /* ── Answer review ── */
        .answer-item {
            background: var(--white);
            border-radius: var(--radius);
            border: 1.5px solid var(--gray-200);
            margin-bottom: .75rem;
            overflow: hidden;
            transition: box-shadow .2s;
        }
        .answer-item:hover { box-shadow: 0 2px 12px rgba(15,27,45,.07); }

        .answer-header {
            display: flex; align-items: center;
            gap: .875rem; padding: 1rem 1.25rem;
            cursor: pointer;
        }
        .answer-icon {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .answer-icon.correct { background: var(--green-bg); }
        .answer-icon.incorrect { background: var(--red-bg); }
        .answer-icon svg { width: 16px; height: 16px; }
        .answer-icon.correct svg { color: var(--green); }
        .answer-icon.incorrect svg { color: var(--red); }

        .answer-q {
            font-size: .875rem; font-weight: 600;
            color: var(--navy); line-height: 1.5; flex: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .answer-chevron {
            width: 18px; height: 18px;
            color: var(--gray-300); flex-shrink: 0;
            transition: transform .2s;
        }
        .answer-item.open .answer-chevron { transform: rotate(180deg); }

        .answer-detail {
            display: none;
            padding: 0 1.25rem 1.25rem;
            border-top: 1px solid var(--gray-100);
        }
        .answer-item.open .answer-detail { display: block; }

        .detail-row {
            display: flex; gap: .6rem;
            font-size: .82rem; padding: .6rem 0;
            border-bottom: 1px solid var(--gray-100);
            align-items: flex-start;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-badge {
            font-size: .68rem; font-weight: 700;
            padding: .2rem .6rem; border-radius: 6px;
            white-space: nowrap; flex-shrink: 0; margin-top: 1px;
        }
        .badge-dipilih { background: var(--gray-100); color: var(--gray-700); }
        .badge-benar   { background: var(--green-bg); color: #15803d; }

        /* ── Action buttons ── */
        .action-row {
            display: flex; gap: .875rem;
            margin-top: 2rem;
        }
        .btn-secondary {
            flex: 1; padding: .9rem;
            border-radius: var(--radius);
            border: 1.5px solid var(--gray-300);
            background: transparent;
            color: var(--gray-700);
            font-family: inherit; font-size: .9rem; font-weight: 600;
            cursor: pointer; text-align: center; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
            transition: all .2s;
        }
        .btn-secondary:hover { background: var(--gray-100); }
        .btn-primary {
            flex: 1; padding: .9rem;
            border-radius: var(--radius);
            border: none; background: var(--orange);
            color: var(--white);
            font-family: inherit; font-size: .9rem; font-weight: 700;
            cursor: pointer; text-align: center; text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            transition: all .2s;
        }
        .btn-primary:hover { background: var(--orange2); transform: translateY(-1px); }
        .btn-primary svg { width: 17px; height: 17px; }
    </style>
</head>
<body>

    {{-- Top bar --}}
    <div class="topbar">
        <a href="{{ route('practice.index') }}" class="topbar-brand">Practice</a>
        <a href="{{ route('practice.index') }}" class="topbar-back">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Practice
        </a>
    </div>

    {{-- Hero --}}
    <div class="hero">
        @php
            $emoji = $skor >= 80 ? '🎉' : ($skor >= 50 ? '💪' : '📚');
            $pesan = $skor >= 80 ? 'Luar biasa!' : ($skor >= 50 ? 'Bagus, terus berlatih!' : 'Jangan menyerah, coba lagi!');
        @endphp

        <div class="score-ring" style="--pct: {{ $skor }}">
            <div class="score-inner">
                <div class="score-num">{{ $skor }}</div>
                <div class="score-pct">SKOR</div>
            </div>
        </div>

        <div class="hero-title">{{ $emoji }} {{ $pesan }}</div>
        <div class="hero-sub">{{ $practiceSession->nama_session }}</div>

        <div class="xp-earned">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            +50 XP ditambahkan ke akunmu
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-val">{{ $totalSoal }}</div>
            <div class="stat-lbl">Total Soal</div>
        </div>
        <div class="stat-card">
            <div class="stat-val green">{{ $totalBenar }}</div>
            <div class="stat-lbl">Benar</div>
        </div>
        <div class="stat-card">
            <div class="stat-val red">{{ $totalSalah }}</div>
            <div class="stat-lbl">Salah</div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content">

        {{-- Action buttons --}}
        <div class="action-row" style="margin-top:0; margin-bottom:2rem;">
            <a href="{{ route('practice.intro', $practiceSession->session_id) }}" class="btn-secondary">
                Ulangi Practice
            </a>
            <a href="{{ route('practice.index') }}" class="btn-primary">
                Practice Lain
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        {{-- Review jawaban --}}
        <div class="section-title">Review Jawaban</div>

        @foreach($answers as $i => $answer)
            <div class="answer-item" id="item-{{ $i }}">
                <div class="answer-header" onclick="toggleItem({{ $i }})">
                    <div class="answer-icon {{ $answer->is_correct ? 'correct' : 'incorrect' }}">
                        @if($answer->is_correct)
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @endif
                    </div>
                    <div class="answer-q">{{ $answer->pertanyaan }}</div>
                    <svg class="answer-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="answer-detail">
                    <div class="detail-row">
                        <span class="detail-badge badge-dipilih">Jawabanmu</span>
                        <span style="color: {{ $answer->is_correct ? '#15803d' : '#b91c1c' }}; font-weight:600;">
                            {{ $answer->jawaban_dipilih }}
                        </span>
                    </div>
                    @if(!$answer->is_correct)
                    <div class="detail-row">
                        <span class="detail-badge badge-benar">Jawaban Benar</span>
                        <span style="color:#15803d; font-weight:600;">{{ $answer->jawaban_benar }}</span>
                        <div class="jawaban-benar" id="jawaban-benar-text"></div>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>

    <script>
        function toggleItem(i) {
            const el = document.getElementById('item-' + i);
            el.classList.toggle('open');
        }
    </script>

</body>
</html>
