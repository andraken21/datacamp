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
            --gray-200:#e2e8f0;
            --gray-300:#cbd5e1;
            --gray-500:#64748b;
            --gray-700:#334155;
            --green:   #22c55e;
            --green-bg:#f0fdf4;
            --red:     #ef4444;
            --red-bg:  #fef2f2;
            --radius:  14px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5rem 1rem 2rem;
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
        .topbar-brand { font-weight: 800; font-size: 1.05rem; color: var(--navy); text-decoration: none; }
        .topbar-progress {
            flex: 1;
            max-width: 320px;
            margin: 0 2rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .progress-bar {
            flex: 1;
            height: 8px;
            background: var(--gray-100);
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: var(--orange);
            border-radius: 99px;
            transition: width .4s ease;
        }
        .progress-text { font-size: .78rem; font-weight: 700; color: var(--gray-500); white-space: nowrap; }
        .topbar-exit {
            font-size: .82rem;
            color: var(--gray-500);
            text-decoration: none;
            font-weight: 600;
            padding: .3rem .75rem;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            transition: all .2s;
        }
        .topbar-exit:hover { background: var(--gray-100); color: var(--navy); }

        /* ── Card ── */
        .card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(15,27,45,.08);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            animation: fadeUp .35s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: var(--navy);
            padding: 1.5rem 2rem;
        }
        .soal-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            margin-bottom: .5rem;
        }
        .soal-text {
            color: var(--white);
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.55;
        }

        .card-body { padding: 1.5rem 2rem; }

        /* ── Options ── */
        .options { display: flex; flex-direction: column; gap: .75rem; }
        .option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .875rem 1.1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all .18s;
            background: var(--white);
            text-align: left;
            font-family: inherit;
            font-size: .9rem;
            color: var(--gray-700);
            font-weight: 500;
            width: 100%;
        }
        .option:hover:not(:disabled) { border-color: var(--orange); background: #fff8f5; }
        .option.selected { border-color: var(--orange); background: #fff8f5; color: var(--navy); }
        .option.correct  { border-color: var(--green);  background: var(--green-bg); color: #15803d; }
        .option.wrong    { border-color: var(--red);    background: var(--red-bg);   color: #b91c1c; }
        .option:disabled { cursor: default; }

        .option-letter {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 800;
            color: var(--gray-500);
            flex-shrink: 0;
            transition: all .18s;
        }
        .option.selected .option-letter { background: var(--orange); color: var(--white); }
        .option.correct  .option-letter { background: var(--green);  color: var(--white); }
        .option.wrong    .option-letter { background: var(--red);    color: var(--white); }

        /* ── Feedback ── */
        .feedback {
            margin-top: 1.25rem;
            padding: .875rem 1rem;
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: .5rem;
        }
        .feedback.show { display: flex; }
        .feedback.correct { background: var(--green-bg); color: #15803d; }
        .feedback.wrong   { background: var(--red-bg);   color: #b91c1c; }
        .feedback svg { width: 18px; height: 18px; flex-shrink: 0; }

        /* ── Footer ── */
        .card-footer {
            padding: 0 2rem 2rem;
            display: flex;
            gap: .75rem;
        }
        .btn {
            flex: 1;
            padding: .875rem;
            border-radius: var(--radius);
            border: none;
            font-family: inherit;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }
        .btn-periksa {
            background: var(--navy);
            color: var(--white);
        }
        .btn-periksa:hover:not(:disabled) { background: #1e3050; }
        .btn-periksa:disabled { background: var(--gray-200); color: var(--gray-500); cursor: not-allowed; }
        .btn-lanjut {
            background: var(--orange);
            color: var(--white);
            display: none;
        }
        .btn-lanjut:hover { background: var(--orange2); transform: translateY(-1px); }
        .btn-lanjut.show { display: flex; }

        /* ── Hasil Akhir ── */
        #hasil-screen {
            display: none;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 3rem 2rem;
            animation: fadeUp .4s ease both;
        }
        #hasil-screen.show { display: flex; }
        .hasil-icon {
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        .hasil-icon svg { width: 40px; height: 40px; }
        .hasil-title { font-size: 1.6rem; font-weight: 800; color: var(--navy); margin-bottom: .5rem; }
        .hasil-sub   { font-size: .9rem; color: var(--gray-500); margin-bottom: 2rem; }
        .hasil-stats {
            display: flex;
            gap: 1px;
            background: var(--gray-100);
            border-radius: 14px;
            overflow: hidden;
            width: 100%;
            margin-bottom: 2rem;
        }
        .hasil-stat { flex: 1; padding: 1.25rem 1rem; background: var(--white); }
        .hasil-stat-val { font-size: 1.75rem; font-weight: 800; color: var(--navy); }
        .hasil-stat-lbl { font-size: .72rem; color: var(--gray-500); font-weight: 500; margin-top: .2rem; }
        .btn-selesai {
            width: 100%;
            padding: .95rem;
            border-radius: var(--radius);
            border: none;
            background: var(--orange);
            color: var(--white);
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-selesai:hover { background: var(--orange2); }
        .btn-ulangi {
            width: 100%;
            padding: .875rem;
            border-radius: var(--radius);
            border: 1.5px solid var(--gray-200);
            background: transparent;
            color: var(--gray-700);
            font-family: inherit;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: .75rem;
            transition: all .2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-ulangi:hover { background: var(--gray-100); }
    </style>
</head>
<body>

    {{-- Top bar --}}
    <div class="topbar">
        <a href="{{ route('practice.index') }}" class="topbar-brand">Practice</a>
        <div class="topbar-progress">
            <div class="progress-bar">
                <div class="progress-fill" id="progress-fill" style="width:0%"></div>
            </div>
            <span class="progress-text" id="progress-text">0 / {{ count($soalList) }}</span>
        </div>
        <a href="{{ route('practice.index') }}" class="topbar-exit">Exit</a>
    </div>

    {{-- Soal Card --}}
    <div class="card" id="soal-card">
        <div class="card-header">
            <div class="soal-label" id="soal-label">Soal 1 dari {{ count($soalList) }}</div>
            <div class="soal-text" id="soal-text"></div>
        </div>
        <div class="card-body">
            <div class="options" id="options-container"></div>
            <div class="feedback" id="feedback">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" id="feedback-icon"></svg>
                <span id="feedback-text"></span>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-periksa" id="btn-periksa" disabled onclick="periksa()">Check</button>
            <button class="btn btn-lanjut" id="btn-lanjut" onclick="lanjut()">
                Next
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Hasil Akhir --}}
    <div class="card" id="hasil-screen">
        <div id="hasil-screen-inner"></div>
    </div>

    <script>
        const soalList = @json($soalList);
        const totalSoal = soalList.length;
        let current = 0;
        let benar = 0;
        let selectedJawaban = null;
        let sudahDiperiksa = false;

        const letters = ['A', 'B', 'C'];

        function tampilSoal(index) {
            const soal = soalList[index];
            selectedJawaban = null;
            sudahDiperiksa = false;

            document.getElementById('soal-label').textContent = `Question ${index + 1} of ${totalSoal}`;
            document.getElementById('soal-text').textContent = soal.pertanyaan;

            // Progress
            const pct = Math.round((index / totalSoal) * 100);
            document.getElementById('progress-fill').style.width = pct + '%';
            document.getElementById('progress-text').textContent = `${index} / ${totalSoal}`;

            // Opsi
            let opsi = [
                { text: soal.opsi_1, value: soal.opsi_1 },
                { text: soal.opsi_2, value: soal.opsi_2 },
                { text: soal.opsi_3, value: soal.opsi_3 },
            ].filter(o => o.text);

            // Fisher-Yates shuffle
            for (let i = opsi.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [opsi[i], opsi[j]] = [opsi[j], opsi[i]];
            }
            const container = document.getElementById('options-container');
            container.innerHTML = '';
            opsi.forEach((o, i) => {
                const btn = document.createElement('button');
                btn.className = 'option';
                btn.dataset.jawaban = o.value;
                btn.innerHTML = `<span class="option-letter">${letters[i]}</span><span>${o.text}</span>`;
                btn.onclick = () => pilih(btn, o.value);
                container.appendChild(btn);
            });

            // Reset UI
            const fb = document.getElementById('feedback');
            fb.className = 'feedback';
            fb.style.display = 'none';
            document.getElementById('btn-periksa').disabled = true;
            document.getElementById('btn-periksa').style.display = '';
            const btnLanjut = document.getElementById('btn-lanjut');
            btnLanjut.classList.remove('show');

            // Animasi card
            const card = document.getElementById('soal-card');
            card.style.animation = 'none';
            card.offsetHeight;
            card.style.animation = 'fadeUp .35s ease both';
        }

        function pilih(btn, jawaban) {
            if (sudahDiperiksa) return;
            selectedJawaban = jawaban;
            document.querySelectorAll('.option').forEach(o => o.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('btn-periksa').disabled = false;
        }

        function periksa() {
            if (!selectedJawaban || sudahDiperiksa) return;
            sudahDiperiksa = true;

            const soal = soalList[current];
            const benarJawaban = soal.jawaban_benar;
            const fb = document.getElementById('feedback');

            document.querySelectorAll('.option').forEach(btn => {
                btn.disabled = true;
                const jaw = btn.dataset.jawaban;
                if (jaw === benarJawaban) btn.classList.add('correct');
                else if (jaw === selectedJawaban) btn.classList.add('wrong');
            });

            if (selectedJawaban === benarJawaban) {
                benar++;
                fb.className = 'feedback correct show';
                fb.style.display = 'flex';
                document.getElementById('feedback-icon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                document.getElementById('feedback-text').textContent = 'Correct! 🎉';
            } else {
                fb.className = 'feedback wrong show';
                fb.style.display = 'flex';
                document.getElementById('feedback-icon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                document.getElementById('feedback-text').textContent = `Wrong answer. Correct: ${benarJawaban}`;
            }

            document.getElementById('btn-periksa').style.display = 'none';
            document.getElementById('btn-lanjut').classList.add('show');
        }

        function lanjut() {
            current++;
            if (current < totalSoal) {
                tampilSoal(current);
            } else {
                tampilHasil();
            }
        }

        function tampilHasil() {
            document.getElementById('soal-card').style.display = 'none';

            const pct = Math.round((benar / totalSoal) * 100);
            document.getElementById('progress-fill').style.width = '100%';
            document.getElementById('progress-text').textContent = `${totalSoal} / ${totalSoal}`;

            const salah = totalSoal - benar;
            const iconColor = pct >= 70 ? '#22c55e' : '#f97316';
            const iconBg    = pct >= 70 ? '#f0fdf4' : '#fff7ed';
            const iconPath  = pct >= 70
                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>';
            const title = pct >= 70 ? 'Great Job! 🎉' : 'Keep Practicing! 💪';
            const sub   = pct >= 70 ? 'You completed this session well.' : "Don't give up, retry for a better result.";

            document.getElementById('hasil-screen-inner').innerHTML = `
                <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:2.5rem 2rem;">
                    <div class="hasil-icon" style="background:${iconBg}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="${iconColor}" stroke-width="2">${iconPath}</svg>
                    </div>
                    <div class="hasil-title">${title}</div>
                    <div class="hasil-sub">${sub}</div>
                    <div class="hasil-stats">
                        <div class="hasil-stat">
                            <div class="hasil-stat-val" style="color:var(--orange)">${pct}%</div>
                            <div class="hasil-stat-lbl">Score</div>
                        </div>
                        <div class="hasil-stat">
                            <div class="hasil-stat-val" style="color:var(--green)">${benar}</div>
                            <div class="hasil-stat-lbl">Correct</div>
                        </div>
                        <div class="hasil-stat">
                            <div class="hasil-stat-val" style="color:var(--red)">${salah}</div>
                            <div class="hasil-stat-lbl">Wrong</div>
                        </div>
                        <div class="hasil-stat">
                            <div class="hasil-stat-val">${totalSoal}</div>
                            <div class="hasil-stat-lbl">Total</div>
                        </div>
                    </div>
                    <a href="{{ route('practice.index') }}" class="btn-selesai">Finish</a>
                    <a href="{{ route('practice.intro', $session->session_id) }}" class="btn-ulangi">Retry Practice</a>
                </div>
            `;

            const hasilCard = document.getElementById('hasil-screen');
            hasilCard.style.display = 'block';
            hasilCard.style.animation = 'fadeUp .4s ease both';
        }

        // Init
        if (totalSoal > 0) {
            tampilSoal(0);
        } else {
            document.getElementById('soal-card').innerHTML = `
                <div style="padding:3rem 2rem;text-align:center;color:var(--gray-500)">
                    <p style="font-size:1rem;font-weight:600">No questions available for this session.</p>
                    <a href="{{ route('practice.index') }}" style="margin-top:1rem;display:inline-block;color:var(--orange);font-weight:700;text-decoration:none">← Back</a>
                </div>
            `;
        }
    </script>
</body>
</html>