<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Native - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#0f1729; }
    </style>
</head>
<body class="text-white min-h-screen">
<x-navbar />

{{-- Hero --}}
<div class="min-h-screen flex items-center" style="background:linear-gradient(135deg,#0f1729 0%,#1a1f3a 50%,#0f1729 100%)">
    <div class="max-w-7xl mx-auto px-8 py-20 grid grid-cols-2 gap-16 items-center w-full">
        <div>
            <div class="flex items-center gap-2 mb-6">
                <span class="text-purple-400 text-lg">✦</span>
                <span class="text-sm font-semibold text-purple-400 uppercase tracking-widest">AI Native</span>
            </div>
            <h1 class="text-5xl font-bold leading-tight mb-6">
                Meet your personal<br>
                <span style="color:#03EF62">AI learning engine</span>
            </h1>
            <p class="text-lg text-gray-300 mb-4 leading-relaxed">The interactive, hands-on experience you know, love, and expect from DataCamp—elevated.</p>
            <p class="text-gray-400 mb-8 leading-relaxed">In the new DataCamp AI-native experience, you'll learn faster and smarter with courses built uniquely for you; your pace, your role, your knowledge, and your goals.</p>
            {{-- Tombol dihapus sesuai permintaan --}}
        </div>

        {{-- Animated AI Response Mock --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-gray-800">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                <span class="ml-2 text-xs text-gray-400">Introduction to AI for Work / Generative AI</span>
            </div>
            <div class="p-5 text-sm" style="min-height:280px;">
                {{-- User question --}}
                <div class="flex justify-end mb-4">
                    <div class="bg-blue-500 text-white rounded-2xl rounded-tr-sm px-4 py-2 max-w-xs text-xs">
                        what is a GPU exactly?
                    </div>
                </div>

                {{-- AI typing response --}}
                <div class="flex items-start gap-2 mb-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="text-white text-xs font-bold">AI</span>
                    </div>
                    <div class="flex-1">
                        <p id="ai-response" class="text-gray-700 text-xs leading-relaxed"></p>
                        <span id="cursor" class="inline-block w-0.5 h-3 bg-gray-400 ml-0.5 animate-pulse"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Typewriter Script --}}
        <script>
            const aiText = `Great question! A GPU (Graphics Processing Unit) is a specialized computer chip originally designed to render graphics for video games.

Here's why GPUs became crucial for AI:

• Traditional CPUs handle tasks one at a time, very quickly — like a single expert working fast.

• GPUs handle thousands of simple calculations simultaneously — like having thousands of workers doing simple math at the same time.

Training AI models requires billions of mathematical calculations — exactly what GPUs excel at. What might take months on a CPU can take days or weeks on GPUs.

Without GPUs, training models like ChatGPT would be prohibitively expensive and slow. GPUs made it practically feasible to train large language models.`;

            let i = 0;
            const el = document.getElementById('ai-response');
            const cursor = document.getElementById('cursor');

            function typeWriter() {
                if (i < aiText.length) {
                    const char = aiText[i];
                    if (char === '\n') {
                        el.innerHTML += '<br>';
                    } else if (char === '•') {
                        el.innerHTML += '•';
                    } else {
                        el.innerHTML += char;
                    }
                    i++;
                    // Speed varies for natural feel
                    const speed = char === '.' || char === '!' || char === '?' ? 80 :
                                  char === '\n' ? 150 : 18;
                    setTimeout(typeWriter, speed);
                } else {
                    // Blink then hide cursor when done
                    setTimeout(() => { cursor.style.display = 'none'; }, 2000);
                }
            }

            // Start after short delay
            setTimeout(typeWriter, 800);
        </script>
    </div>
</div>

{{-- Explore Section - dari DB ai_native_konten --}}
<div class="py-20 px-8" style="background:#131929">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-white mb-12">Explore AI-native curriculum, built just for you</h2>

        @php
            // Ambil dari ai_native_konten, seksi "2. Explore AI-Native Curriculum"
            $activeFilter = request('tipe', 'all');

            // Ambil tipe unik untuk filter pills
            $tipeList = DB::table('ai_native_konten')
                ->where('seksi', 'like', '%Explore%')
                ->whereNotNull('tipe')
                ->select('tipe')
                ->distinct()
                ->orderBy('tipe')
                ->pluck('tipe');

            // Query konten
            $query = DB::table('ai_native_konten')
                ->where('seksi', 'like', '%Explore%');

            if ($activeFilter !== 'all') {
                $query->where('tipe', $activeFilter);
            }

            $kurikulum = $query->orderBy('id')->get();

            $levelColors = [
                'Beginner'             => '#03EF62',
                'Beginner–Intermediate' => '#10b981',
                'Intermediate'         => '#3b82f6',
                'Advanced'             => '#8b5cf6',
            ];
        @endphp

        {{-- Filter Pills --}}
        <div class="flex flex-wrap gap-2 mb-8 justify-center">
            <a href="{{ route('ai-native') }}"
               class="px-4 py-1.5 rounded-full text-sm border transition-colors
               {{ $activeFilter === 'all' ? 'bg-purple-500 border-purple-500 text-white' : 'border-white/20 text-gray-400 hover:border-white/40' }}">
                All
            </a>
            @foreach($tipeList as $tipe)
            <a href="?tipe={{ urlencode($tipe) }}"
               class="px-4 py-1.5 rounded-full text-sm border transition-colors
               {{ $activeFilter === $tipe ? 'bg-purple-500 border-purple-500 text-white' : 'border-white/20 text-gray-400 hover:border-white/40' }}">
                {{ $tipe }}
            </a>
            @endforeach
        </div>

        @if($kurikulum->count() > 0)
        <div class="grid grid-cols-3 gap-4">
            @foreach($kurikulum as $item)
            @php
                // Level ada di kolom detail_tambahan, format "Level: Beginner"
                $levelRaw   = $item->detail_tambahan ?? '';
                $levelClean = trim(str_replace('Level:', '', $levelRaw));
                $levelKey   = 'Beginner';
                foreach (['Advanced', 'Intermediate', 'Beginner–Intermediate', 'Beginner'] as $l) {
                    if (str_contains($levelRaw, $l)) { $levelKey = $l; break; }
                }
                $levelColor = $levelColors[$levelKey] ?? '#03EF62';
            @endphp
            <div class="rounded-xl p-5 border border-white/10 hover:border-purple-400/40 transition-colors flex flex-col" style="background:#1a2540">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    {{ strtoupper($item->tipe ?? 'COURSE') }}
                </p>
                <h3 class="text-base font-bold text-white mb-2 flex-1">{{ $item->judul }}</h3>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2 h-2 rounded-full" style="background:{{ $levelColor }}"></div>
                    <span class="text-xs text-gray-400">{{ $levelClean ?: 'Beginner' }}</span>
                </div>
                @if($item->deskripsi)
                <p class="text-xs text-gray-400 leading-relaxed mb-4 line-clamp-3">{{ $item->deskripsi }}</p>
                @endif
                <div class="flex items-center justify-between mt-auto">
                    @if($item->link)
                    <a href="{{ $item->link }}" target="_blank"
                       class="text-sm text-gray-400 hover:text-white transition-colors">
                        See Details →
                    </a>
                    <a href="{{ $item->link }}" target="_blank"
                       class="px-4 py-1.5 rounded-lg text-xs font-semibold"
                       style="background:#03EF62;color:#05192D">
                        Start Course
                    </a>
                    @else
                    <span class="text-xs text-gray-500 italic">Coming soon</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-gray-500">
            <p>Belum ada konten AI Native untuk filter ini.</p>
        </div>
        @endif

        {{-- Core picks link --}}
        <div class="text-center mt-10 py-5 border-t border-white/10">
            <p class="text-sm text-gray-400">
                Core picks to get started.
                <a href="{{ route('courses') }}"
                   class="text-green-400 font-semibold hover:text-green-300 transition-colors">
                    Explore All AI Native Curriculum →
                </a>
            </p>
        </div>

    </div>
</div>

{{-- Testimonials / User Reviews --}}
<div class="py-20 px-8" style="background:#0f1729">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-white text-center mb-3">Learners already love it</h2>
        <p class="text-gray-400 text-center text-sm mb-10">Bagikan pengalaman belajarmu di platform ini!</p>

        @php
            $reviews = DB::table('user_reviews')
                ->join('users', 'user_reviews.user_id', '=', 'users.user_id')
                ->where('user_reviews.halaman', 'ai-native')
                ->select('user_reviews.*', 'users.name')
                ->orderByDesc('user_reviews.created_at')
                ->get();
        @endphp

        {{-- Review Cards dari DB --}}
        @if($reviews->count() > 0)
        <div class="grid grid-cols-3 gap-4 mb-10">
            @foreach($reviews as $review)
            <div class="rounded-xl p-5 border border-white/10" style="background:#131929">
                {{-- Rating bintang --}}
                @if($review->rating)
                <div class="flex gap-0.5 mb-3">
                    @for($s = 1; $s <= 5; $s++)
                    <span class="{{ $s <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }} text-sm">★</span>
                    @endfor
                </div>
                @endif
                <p class="text-sm text-gray-300 leading-relaxed mb-4">"{{ $review->isi_review }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($review->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-white">{{ $review->name }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-10 mb-10">
            <p class="text-gray-500 text-sm">Belum ada review. Jadilah yang pertama! 👇</p>
        </div>
        @endif

        {{-- Form tulis review --}}
        <div class="max-w-xl mx-auto">
            <div class="rounded-2xl p-6 border border-white/10" style="background:#131929">
                <h3 class="text-base font-semibold text-white mb-4">✍️ Tulis Review Kamu</h3>

                @if(session('review_success'))
                <div class="mb-4 px-4 py-2 rounded-lg bg-green-900/50 border border-green-500/30 text-green-400 text-sm">
                    ✓ Review kamu sudah terkirim, terima kasih!
                </div>
                @endif

                @auth
                <form method="POST" action="{{ route('ai-native.review') }}">
                    @csrf
                    {{-- Rating bintang --}}
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 mb-2">Rating (opsional)</p>
                        <div class="flex gap-2">
                            @for($s = 1; $s <= 5; $s++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $s }}" class="hidden peer">
                                <span class="text-2xl text-gray-600 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors select-none">★</span>
                            </label>
                            @endfor
                        </div>
                    </div>

                    {{-- Isi review --}}
                    <div class="mb-4">
                        <textarea name="isi_review" rows="3" required
                            placeholder="Ceritakan pengalaman belajarmu di platform ini..."
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-purple-400 resize-none">{{ old('isi_review') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition-opacity hover:opacity-90"
                        style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">
                        Kirim Review ✨
                    </button>
                </form>
                @else
                <div class="text-center py-4">
                    <p class="text-gray-400 text-sm mb-3">Login dulu untuk menulis review</p>
                    <a href="{{ route('login') }}"
                       class="px-6 py-2 rounded-xl text-sm font-semibold text-white"
                       style="background:#7c3aed">
                        Login
                    </a>
                </div>
                @endauth
            </div>
        </div>

    </div>
</div>

</body>
</html>