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
<<<<<<< HEAD
            <div class="flex gap-4">
                <a href="{{ route('courses') }}" class="px-6 py-3 rounded-lg font-semibold text-sm" style="background:#03EF62;color:#05192D">Start Learning Free</a>
                <a href="#" class="px-6 py-3 rounded-lg font-semibold text-sm border border-white/20 hover:border-white/40 text-white">See How It Works</a>
            </div>
        </div>

        {{-- Mock chat UI --}}
=======
            {{-- Tombol dihapus sesuai permintaan --}}
        </div>

        {{-- Animated AI Response Mock --}}
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-gray-800">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                <span class="ml-2 text-xs text-gray-400">Introduction to AI for Work / Generative AI</span>
            </div>
<<<<<<< HEAD
            <div class="p-5 space-y-3 text-sm">
                <p class="text-gray-700">These three factors converging created the perfect conditions for the AI breakthrough we're experiencing today.</p>
                <p class="text-gray-500 text-xs">Any questions about this?</p>
                <div class="bg-gray-50 rounded-lg p-3 mt-4">
                    <p class="text-gray-600 text-xs mb-2">what is a GPU exactly?</p>
                    <div class="flex justify-end">
                        <button class="text-xs px-3 py-1 rounded-lg text-white font-medium" style="background:#3b82f6">Send ↵</button>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400 pt-2">
                    <span class="px-2 py-1 rounded bg-gray-100">No questions</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Explore Section --}}
<div class="py-20 px-8" style="background:#131929">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-white mb-12">Explore AI-native curriculum, built just for you</h2>
        @php
            $ainativeCourses = [
                ['type'=>'TRACK','title'=>'AI Engineering with LangChain','level'=>'Intermediate','duration'=>'36 hr','desc'=>'From prompt engineering to agentic systems—develop the complete skill set to build AI applications that scale, with an AI tutor by your side.'],
                ['type'=>'COURSE','title'=>'Introduction to AI for Work','level'=>'Basic','duration'=>'2 hr - 3 hr','desc'=>'Build your AI foundation with hands-on, AI-native learning that adapts to your pace. Explore how AI works, and learn how to use it effectively and responsibly.'],
                ['type'=>'COURSE','title'=>'Introduction to SQL','level'=>'Basic','duration'=>'30 min - 1 hr','desc'=>'Learn SQL faster with the DataCamp AI-native experience. Practice querying and organizing data in real databases, with lessons that adjust to your pace.'],
                ['type'=>'COURSE','title'=>'LLM Application Fundamentals with LangChain','level'=>'Intermediate','duration'=>'2 hr - 4 hr','desc'=>'Learn to build conversational LLM applications — with reliable structured output, persistent conversation history, and real-time streaming.'],
                ['type'=>'COURSE','title'=>'Prompt Engineering with LangChain','level'=>'Intermediate','duration'=>'1 hr - 3 hr','desc'=>'Learn to write effective prompts and systematically improve them — applying techniques, structural patterns, and optimization strategies.'],
                ['type'=>'COURSE','title'=>'Intermediate SQL','level'=>'Intermediate','duration'=>'4 hr - 6 hr','desc'=>'Accompanied at every step with hands-on practice queries, this course teaches you everything you need to know to analyze data using your own SQL code today!'],
            ];
        @endphp
        <div class="grid grid-cols-3 gap-4">
            @foreach($ainativeCourses as $course)
            <div class="rounded-xl p-5 border border-white/10 hover:border-purple-400/40 transition-colors" style="background:#1a2540">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    {{ $course['type'] }}
                </p>
                <h3 class="text-base font-bold text-white mb-2">{{ $course['title'] }}</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                        <span class="text-xs text-gray-400">{{ $course['level'] }}</span>
                    </div>
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        {{ $course['duration'] }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed mb-4">{{ $course['desc'] }}</p>
                <div class="flex items-center justify-between">
                    <a href="#" class="text-sm text-gray-400 hover:text-white">See Details →</a>
                    <a href="#" class="px-4 py-1.5 rounded-lg text-xs font-semibold" style="background:#03EF62;color:#05192D">
                        {{ $course['type'] === 'TRACK' ? 'Start Track' : 'Start Course' }}
                    </a>
=======
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
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
                </div>
            </div>
            @endforeach
        </div>
<<<<<<< HEAD
    </div>
</div>

{{-- Why Section --}}
<div class="py-20 px-8 text-center" style="background:#0f1729">
    <div class="max-w-4xl mx-auto mb-12">
        <h2 class="text-3xl font-bold text-white mb-4">The future of learning has arrived</h2>
        <p class="text-gray-400 leading-relaxed">Other learning providers have AI assistants layered on top of static videos or exercises. This is learning built with AI as its core. DataCamp is the only platform that offers an AI-native, personal learning engine that feels like a great one-on-one teacher.</p>
    </div>
    <div class="grid grid-cols-4 gap-4 max-w-7xl mx-auto">
        @php
            $features = [
                ['title'=>'One destination, infinite routes','desc'=>'Each lesson is built around the same goals, but no two experiences are alike. Move quickly through what you know, and spend more time mastering what you don\'t.'],
                ['title'=>'Hyper relevant','desc'=>'DataCamp\'s AI learning engine adapts to your skill level and context, bringing examples and exercises that reflect your interests, background, and challenges.'],
                ['title'=>'Always up to date','desc'=>'The world changes fast—your learning should too. With AI-native content, every course stays aligned with today\'s tools, data, and trends.','highlight'=>true],
                ['title'=>'Feels human; powered by AI','desc'=>'No more robotic chatbots and static lessons. DataCamp can engage like a teacher who knows you, so one hour of learning takes you further.'],
            ];
        @endphp
        @foreach($features as $feature)
        <div class="rounded-xl p-5 text-left border {{ isset($feature['highlight']) ? 'border-white/30' : 'border-white/10' }}" style="background:{{ isset($feature['highlight']) ? '#1e2a45' : '#131929' }}">
            <h3 class="font-bold text-white mb-3 text-sm">{{ $feature['title'] }}</h3>
            <p class="text-xs text-gray-400 leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Testimonials --}}
<div class="py-20 px-8" style="background:#0f1729">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-white text-center mb-12">Learners already love it</h2>
        @php
            $testimonials = [
                ['quote'=>'"I found the explanations and examples relevant to my work. The hands-on, interactive experience made concepts stick, and the adaptive pace felt like having a virtual one-to-one mentor."','name'=>'Yi-Wei Ang','role'=>'Chief Product Officer at talabat'],
                ['quote'=>'"I LOVE this format. This is definitely my new preferred learning experience. It\'s super engaging and I feel like I learned great stuff in the course. The examples were customized to fit my background."','name'=>'','role'=>'Senior Analyst at a digital media and tech company'],
                ['quote'=>'"We love how DataCamp\'s new AI-native experience adapts the speed and relevance of every lesson."','name'=>'Fernando Ospina','role'=>'Head of Capability Strategy and Development for Data Insights at Philip Morris International'],
                ['quote'=>'"The DataCamp AI-native experience excels in tailoring content to individual users based on their role and knowledge level."','name'=>'','role'=>'Data and AI owner at a multinational retail company'],
                ['quote'=>'"This is the best training tool I have ever encountered. It consistently produced accurate answers with detailed explanations, which I found very impressive."','name'=>'Kamal Deep Patra','role'=>'Business Solutions Manager at Uniper Energy'],
                ['quote'=>'"It has one of the most thorough and best course materials out there. Also super structured, aided with diagrams and proper flows and code assignments."','name'=>'','role'=>'Software engineer at Careem'],
            ];
        @endphp
        <div class="grid grid-cols-3 gap-4">
            @foreach($testimonials as $t)
            <div class="rounded-xl p-5 border border-white/10" style="background:#131929">
                <p class="text-sm text-gray-300 leading-relaxed mb-4">{{ $t['quote'] }}</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        @if($t['name'])<p class="text-xs font-semibold text-white">{{ $t['name'] }}</p>@endif
                        <p class="text-xs text-gray-500">{{ $t['role'] }}</p>
=======
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
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
                    </div>
                </div>
            </div>
            @endforeach
        </div>
<<<<<<< HEAD
=======
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

>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
    </div>
</div>

</body>
</html>