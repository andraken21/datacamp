<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $session->nama_session }} — Practice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:    #0f1b2d;
            --navy2:   #162236;
            --orange:  #f97316;
            --orange2: #ea6a08;
            --white:   #ffffff;
            --gray-50: #f8fafc;
            --gray-100:#f1f5f9;
            --gray-300:#cbd5e1;
            --gray-500:#64748b;
            --gray-700:#334155;
            --green:   #22c55e;
            --radius:  14px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* ── Top bar ── */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 56px;
            background: var(--white);
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 100;
        }
        .topbar-brand {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--navy);
            text-decoration: none;
        }
        .topbar-back {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .875rem;
            color: var(--gray-500);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }
        .topbar-back:hover { color: var(--navy); }
        .topbar-back svg { width: 16px; height: 16px; }

        /* ── Card ── */
        .card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(15,27,45,.08);
            max-width: 560px;
            width: 100%;
            overflow: hidden;
            margin-top: 56px;
            animation: fadeUp .4s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Card header ── */
        .card-header {
            background: var(--navy);
            padding: 2.5rem 2rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(249,115,22,.12);
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -30px; right: 60px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(249,115,22,.07);
        }
        .topic-badge {
            display: inline-block;
            background: rgba(249,115,22,.2);
            color: var(--orange);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .3rem .75rem;
            border-radius: 100px;
            margin-bottom: 1rem;
        }
        .card-title {
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.3;
            position: relative;
            z-index: 1;
        }

        /* ── Stats row ── */
        .stats-row {
            display: flex;
            gap: 1px;
            background: var(--gray-100);
            border-bottom: 1px solid var(--gray-100);
        }
        .stat {
            flex: 1;
            padding: 1.1rem 1rem;
            text-align: center;
            background: var(--white);
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1;
        }
        .stat-label {
            font-size: .72rem;
            color: var(--gray-500);
            margin-top: .25rem;
            font-weight: 500;
        }

        /* ── Rules ── */
        .card-body { padding: 1.75rem 2rem; }

        .rules-title {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }
        .rule-item {
            display: flex;
            align-items: flex-start;
            gap: .875rem;
            padding: .75rem 0;
            border-bottom: 1px solid var(--gray-100);
        }
        .rule-item:last-child { border-bottom: none; }
        .rule-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--gray-50);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .rule-icon svg { width: 18px; height: 18px; color: var(--orange); }
        .rule-text { font-size: .875rem; color: var(--gray-700); line-height: 1.5; }
        .rule-text strong { color: var(--navy); font-weight: 700; }

        /* ── Attempt info ── */
        .attempt-info {
            background: var(--gray-50);
            border: 1px solid var(--gray-100);
            border-radius: 10px;
            padding: .875rem 1rem;
            font-size: .82rem;
            color: var(--gray-500);
            margin-top: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .attempt-info svg { width: 16px; height: 16px; color: var(--orange); flex-shrink: 0; }

        /* ── CTA ── */
        .card-footer {
            padding: 0 2rem 2rem;
            display: flex;
            gap: .75rem;
        }
        .btn-back {
            flex: 1;
            padding: .875rem;
            border-radius: var(--radius);
            border: 1.5px solid var(--gray-300);
            background: transparent;
            color: var(--gray-700);
            font-family: inherit;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
        }
        .btn-back:hover { background: var(--gray-100); }

        .btn-start {
            flex: 2;
            padding: .875rem;
            border-radius: var(--radius);
            border: none;
            background: var(--orange);
            color: var(--white);
            font-family: inherit;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }
        .btn-start:hover { background: var(--orange2); transform: translateY(-1px); }
        .btn-start svg { width: 18px; height: 18px; }
    </style>
</head>
<body>

    {{-- Top bar --}}
    <div class="topbar">
        <a href="{{ route('practice.index') }}" class="topbar-brand">Practice</a>
        <a href="{{ route('practice.index') }}" class="topbar-back">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    {{-- Card --}}
    <div class="card">

        {{-- Header --}}
        <div class="card-header">
            <div class="topic-badge">{{ $session->topik->nama_topik ?? 'Practice' }}</div>
            <h1 class="card-title">{{ $session->nama_session }}</h1>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat">
                <div class="stat-value">{{ $totalSoal }}</div>
                <div class="stat-label">Questions</div>
            </div>
            <div class="stat">
                <div class="stat-value">3</div>
                <div class="stat-label">Choices</div>
            </div>
            <div class="stat">
                <div class="stat-value">+50</div>
                <div class="stat-label">XP</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $totalAttempt > 0 ? $totalAttempt : '—' }}</div>
                <div class="stat-label">Attempt</div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body">
    <div class="rules-title">Practice Rules</div>

    <div class="rule-item">
        <div class="rule-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="rule-text">Choose <strong>one answer</strong> that best fits from the 3 available options.</div>
    </div>

    <div class="rule-item">
        <div class="rule-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </div>
        <div class="rule-text">Press <strong>Check</strong> to see whether your answer is correct or wrong.</div>
    </div>

    <div class="rule-item">
        <div class="rule-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </div>
        <div class="rule-text">After checking, press <strong>Next</strong> to move to the next question.</div>
    </div>

    <div class="rule-item">
        <div class="rule-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="rule-text">Complete all questions to earn <strong>+50 XP</strong> and see your final result.</div>
    </div>

    @if($totalAttempt > 0)
    <div class="attempt-info">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        You have attempted this session <strong>&nbsp;{{ $totalAttempt }}x&nbsp;</strong>. This will be attempt #{{ $totalAttempt + 1 }}.
    </div>
    @endif
</div>

        {{-- Footer --}}
        <div class="card-footer">
            <a href="{{ route('practice.index') }}" class="btn-back">Cancelled</a>
            <form method="POST" action="{{ route('practice.start', $session->session_id) }}" style="flex:2; display:flex;">
                @csrf
                <button type="submit" class="btn-start" style="width:100%;">
                    Start Practice
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>
        </div>

    </div>

</body>
</html>
