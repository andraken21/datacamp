<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $practiceSession->nama_session }} — Quiz</title>
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
            display: flex;
            flex-direction: column;
        }

        /* ── Top bar ── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-100);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-exit {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .85rem;
            color: var(--gray-500);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
            white-space: nowrap;
        }
        .topbar-exit:hover { color: var(--red); }
        .topbar-exit svg { width: 16px; height: 16px; }

        .topbar-session {
            font-size: .85rem;
            font-weight: 700;
            color: var(--navy);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        /* ── Progress bar ── */
        .progress-wrap {
            flex: 1;
            height: 8px;
            background: var(--gray-100);
            border-radius: 100px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--orange), #fb923c);
            border-radius: 100px;
            transition: width .5s ease;
        }
        .progress-label {
            font-size: .8rem;
            font-weight: 700;
            color: var(--gray-500);
            white-space: nowrap;
        }

        /* ── Main content ── */
        .main {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2.5rem 1rem 4rem;
        }

        .quiz-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(15,27,45,.07);
            max-width: 680px;
            width: 100%;
            overflow: hidden;
            animation: fadeUp .35s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Question header ── */
        .q-header {
            background: var(--navy);
            padding: 1.75rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .q-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 140px; height: 140px;
            border-radius: 50%;
            background: rgba(249,115,22,.1);
        }
        .q-num {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
            margin-bottom: .6rem;
        }
        .q-text {
            color: var(--white);
            font-size: 1.05rem;
            font-weight: 600;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        /* ── Feedback banner (benar/salah dari soal sebelumnya) ── */
        .feedback-banner {
            padding: .875rem 2rem;
            font-size: .875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .feedback-banner svg { width: 18px; height: 18px; flex-shrink: 0; }
        .feedback-banner.correct {
            background: var(--green-bg);
            color: #15803d;
            border-bottom: 1px solid var(--green-border);
        }
        .feedback-banner.incorrect {
            background: var(--red-bg);
            color: #b91c1c;
            border-bottom: 1px solid var(--red-border);
        }

        /* ── Options ── */
        .options-wrap { padding: 1.75rem 2rem; }
        .options-label {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }

        .option-item { margin-bottom: .75rem; }
        .option-item:last-child { margin-bottom: 0; }

        .option-label {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }
        .option-label:hover {
            border-color: var(--orange);
            background: #fff7ed;
        }

        .option-radio { display: none; }
        .option-radio:checked + .option-label {
            border-color: var(--orange);
            background: #fff7ed;
        }
        .option-radio:checked + .option-label .option-bullet {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
        }

        .option-bullet {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid var(--gray-300);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 800;
            color: var(--gray-500);
            flex-shrink: 0;
            transition: all .2s;
            margin-top: 1px;
        }
        .option-text {
            font-size: .9rem;
            color: var(--gray-700);
            line-height: 1.55;
            font-weight: 500;
        }

        /* ── Selected state feedback (after submit) ── */
        .option-label.is-correct {
            border-color: var(--green) !important;
            background: var(--green-bg) !important;
        }
        .option-label.is-correct .option-bullet {
            background: var(--green);
            border-color: var(--green);
            color: var(--white);
        }
        .option-label.is-wrong {
            border-color: var(--red) !important;
            background: var(--red-bg) !important;
        }
        .option-label.is-wrong .option-bullet {
            background: var(--red);
            border-color: var(--red);
            color: var(--white);
        }

        /* ── Footer ── */
        .quiz-footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .xp-badge {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            font-weight: 700;
            color: var(--orange);
        }
        .xp-badge svg { width: 16px; height: 16px; }

        .btn-check {
            padding: .8rem 2rem;
            border-radius: var(--radius);
            border: none;
            background: var(--navy);
            color: var(--white);
            font-family: inherit;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .btn-check:hover:not(:disabled) { background: var(--navy2); transform: translateY(-1px); }
        .btn-check:disabled { opacity: .45; cursor: not-allowed; transform: none; }

        .btn-next {
            padding: .8rem 2rem;
            border-radius: var(--radius);
            border: none;
            background: var(--orange);
            color: var(--white);
            font-family: inherit;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
        }
        .btn-next:hover { background: var(--orange2); transform: translateY(-1px); }
        .btn-next svg { width: 18px; height: 18px; }

        /* ── Feedback result box (inline) ── */
        .result-box {
            margin: 0 2rem 1.5rem;
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            font-size: .875rem;
            line-height: 1.6;
            display: none;
        }
        .result-box.correct {
            background: var(--green-bg);
            border: 1.5px solid var(--green-border);
            color: #15803d;
        }
        .result-box.incorrect {
            background: var(--red-bg);
            border: 1.5px solid var(--red-border);
            color: #b91c1c;
        }
        .result-box .result-title {
            font-weight: 800;
            font-size: .95rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .4rem;
        }
        .result-box .result-title svg { width: 18px; height: 18px; }
        .result-box .jawaban-benar {
            font-size: .8rem;
            opacity: .85;
        }

        /* Hidden input group */
        .hidden { display: none; }
    </style>
</head>
<body>

    {{-- Top bar --}}
    <div class="topbar">
        <a href="{{ route('practice.index') }}" class="topbar-exit">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Keluar
        </a>
        <div class="topbar-session">{{ $practiceSession->nama_session }}</div>
        <div class="progress-wrap">
            <div class="progress-bar" style="width: {{ $totalSoal > 0 ? round(($sudahDijawab / $totalSoal) * 100) : 0 }}%"></div>
        </div>
        <div class="progress-label">{{ $nomorSoal }}/{{ $totalSoal }}</div>
    </div>

    {{-- Main --}}
    <div class="main">
        <div class="quiz-card">

            {{-- Feedback soal sebelumnya (dari session flash) --}}
            @if(session('last_answer'))
                @php $la = session('last_answer'); @endphp
                <div class="feedback-banner {{ $la['is_correct'] ? 'correct' : 'incorrect' }}">
                    @if($la['is_correct'])
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Jawaban kamu benar! Lanjutkan!
                    @else
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Jawaban kurang tepat. Tetap semangat!
                    @endif
                </div>
            @endif

            {{-- Question --}}
            <div class="q-header">
                <div class="q-num">Soal {{ $nomorSoal }} dari {{ $totalSoal }}</div>
                <div class="q-text">{{ $currentQuestion->pertanyaan }}</div>
            </div>

            {{-- Options form --}}
            <form id="quiz-form" method="POST" action="{{ route('practice.answer', $userSession->user_session_id) }}">
                @csrf
                <input type="hidden" name="question_id" value="{{ $currentQuestion->question_id }}">
                <input type="hidden" name="jawaban_dipilih" id="jawaban-hidden" value="">

                <div class="options-wrap">
                    <div class="options-label">Pilih jawaban yang benar</div>

                    @foreach($opsi as $index => $teks)
                        @php $huruf = chr(65 + $loop->index); @endphp
                        <div class="option-item">
                            <input
                                class="option-radio"
                                type="radio"
                                name="opsi_pilih"
                                id="opsi_{{ $huruf }}"
                                value="{{ $teks }}"
                                data-text="{{ $teks }}"
                            >
                            <label class="option-label" for="opsi_{{ $huruf }}" id="label_{{ $huruf }}">
                                <span class="option-bullet">{{ $huruf }}</span>
                                <span class="option-text">{{ $teks }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                {{-- Inline result box (muncul setelah periksa) --}}
                <div class="result-box" id="result-box">
                    <div class="result-title" id="result-title"></div>
                    <div class="jawaban-benar" id="jawaban-benar-text">
                        <label id="label_"></label>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="quiz-footer">
                    <div class="xp-badge">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        +50 XP saat selesai
                    </div>

                    {{-- Tombol Periksa (sebelum jawab) --}}
                    <button type="button" class="btn-check" id="btn-periksa" disabled onclick="periksaJawaban()">
                        Periksa
                    </button>

                    {{-- Tombol Lanjut (setelah periksa) — hidden dulu --}}
                    <button type="submit" class="btn-next hidden" id="btn-lanjut">
                        {{ $nomorSoal >= $totalSoal ? 'Lihat Hasil' : 'Lanjut' }}
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        const jawabBenar = @json(trim($currentQuestion->jawaban_benar));
        let pilihanTerpilih = null;

        // Enable tombol periksa saat opsi dipilih
        document.querySelectorAll('.option-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                pilihanTerpilih = this.value;
                document.getElementById('btn-periksa').disabled = false;
            });
        });

        function periksaJawaban() {
            if (!pilihanTerpilih) return;

            const isCorrect = pilihanTerpilih.trim() === jawabBenar.trim();

            // Disable semua opsi
            document.querySelectorAll('.option-radio').forEach(r => r.disabled = true);

            // Highlight opsi benar & salah
            document.querySelectorAll('.option-radio').forEach(radio => {
                const label = document.querySelector(`label[for="${radio.id}"]`);
                if (radio.value.trim() === jawabBenar.trim()) {
                    label.classList.add('is-correct');
                } else if (radio.checked && !isCorrect) {
                    label.classList.add('is-wrong');
                }
            });

            // Tampilkan result box
            const box   = document.getElementById('result-box');
            const title = document.getElementById('result-title');
            const hint  = document.getElementById('jawaban-benar-text');

            if (isCorrect) {
                box.classList.add('correct');
                title.innerHTML = `
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg> Jawaban Benar!`;
                hint.textContent = '';
            } else {
                box.classList.add('incorrect');
                title.innerHTML = `
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg> Jawaban Kurang Tepat`;
                hint.textContent = 'Jawaban benar: ' + jawabBenar;
            }

            box.style.display = 'block';

            // Set hidden input & ganti tombol
            document.getElementById('jawaban-hidden').value = pilihanTerpilih;
            document.getElementById('btn-periksa').classList.add('hidden');
            document.getElementById('btn-lanjut').classList.remove('hidden');
        }
    </script>

</body>
</html>
