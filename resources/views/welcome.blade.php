<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCamp - Learn Data Science and AI Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; margin: 0; }
        .dc-green { color: #03EF62; }
        .btn-green { background: #03EF62; color: #05192D; font-weight: 600; }
        .btn-green:hover { background: #00d455; }
        .nav-link { color: #fff; font-size: 14px; }
        .nav-link:hover { color: #03EF62; }
        .hero-grid { background-image: linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px); background-size: 60px 60px; }
        .tab-active { background: #05192D; color: #fff; }
        .tab-inactive { background: transparent; color: #05192D; border: 1px solid #05192D; }
        .card-hover:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.12); transform: translateY(-2px); transition: all .2s; }
        input:focus { outline: none; border-color: #03EF62 !important; }
        section { min-height: 100vh; display: flex; flex-direction: column; justify-content: center; }
        nav { position: sticky; top: 0; z-index: 50; }
    </style>
</head>
<body class="bg-white">

{{-- NAVBAR --}}
<nav class="border-b border-white/10" style="background:#05192D">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="/" class="flex items-center gap-2">
                <div class="flex items-center gap-1.5">
                    <div class="w-7 h-7 rounded flex items-center justify-center" style="background:#03EF62">
                        <span class="font-black text-sm" style="color:#05192D">D</span>
                    </div>
                    <svg width="10" height="14" viewBox="0 0 10 14" fill="none">
                        <path d="M1 1L9 7L1 13" stroke="#03EF62" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="font-bold text-white text-base" style="font-style:italic;letter-spacing:-0.5px">datacamp</span>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-5">
                <a href="{{ route('courses') }}" class="nav-link flex items-center gap-1">Catalog <svg width="10" height="10" viewBox="0 0 10 10" fill="white"><path d="M5 7L0 2h10z"/></svg></a>
                <a href="#" class="nav-link flex items-center gap-1">AI Upskilling <svg width="10" height="10" viewBox="0 0 10 10" fill="white"><path d="M5 7L0 2h10z"/></svg></a>
                <a href="{{ route('resources') }}" class="nav-link flex items-center gap-1">Resources <svg width="10" height="10" viewBox="0 0 10 10" fill="white"><path d="M5 7L0 2h10z"/></svg></a>
                <a href="{{ route('harga') }}" class="nav-link flex items-center gap-1">Pricing <svg width="10" height="10" viewBox="0 0 10 10" fill="white"><path d="M5 7L0 2h10z"/></svg></a>
                <a href="#" class="nav-link">For Business</a>
                <a href="#" class="nav-link">For Universities</a>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" class="opacity-70 cursor-pointer"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <span class="text-white/60 text-sm flex items-center gap-1 cursor-pointer">🌐 EN</span>
            @auth
            <a href="{{ route('dashboard') }}" class="text-white border border-white/30 text-sm px-4 py-1.5 rounded hover:border-white">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="text-white text-sm px-4 py-1.5 rounded border border-white/30 hover:border-white">Log In</a>
            <a href="{{ route('register') }}" class="btn-green text-sm px-4 py-1.5 rounded">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

{{-- SECTION 1: HERO --}}
<section class="hero-grid" style="background:#05192D; min-height:calc(100vh - 52px)">
    <div class="max-w-7xl mx-auto px-6 py-10 w-full">
        <div class="grid grid-cols-2 gap-10 items-center h-full">
            <div>
                <h1 class="text-5xl font-bold text-white leading-tight mb-4">Learn data and<br>AI skills</h1>
                <p class="text-white/65 text-base leading-relaxed mb-8 max-w-lg">Master in-demand skills in Python, ChatGPT, Power BI, and more through interactive courses, real-world projects, and industry recognized certifications</p>
                <div class="flex gap-3 mb-8 flex-wrap">
                    <a href="{{ route('register') }}" class="btn-green px-6 py-3 rounded text-sm">Start Learning for Free</a>
                    <a href="#" class="border border-white/40 text-white px-6 py-3 rounded text-sm hover:border-white">DataCamp for Business</a>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-yellow-400 text-sm">★★★★½</span>
                    <span class="text-white/50 text-sm">4.7 out of 5</span>
                </div>
                {{-- Tools bar inside hero --}}
                <div class="flex flex-wrap gap-5 mt-10 pt-8 border-t border-white/10">
                    <span class="text-white/40 text-sm flex items-center gap-1">🐍 python</span>
                    <span class="text-white/40 text-sm italic">R</span>
                    <span class="text-white/40 text-sm">SQL</span>
                    <span class="text-white/40 text-sm">✦ ChatGPT</span>
                    <span class="text-white/40 text-sm">📊 Power BI</span>
                    <span class="text-white/40 text-sm">📗 Excel</span>
                    <span class="text-white/40 text-sm">❄️ Snowflake</span>
                    <span class="text-white/40 text-sm">⎇ git</span>
                </div>
            </div>

            {{-- Register Form --}}
            <div class="bg-white rounded-2xl p-8 shadow-2xl">
                <h2 class="text-xl font-bold text-center text-gray-900 mb-5">Create Your Free Account</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="name" value="New User">
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                        <input type="email" name="email" placeholder="Email Address" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm text-gray-600 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="hp" placeholder="Password" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm pr-10">
                            <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <input type="hidden" name="password_confirmation" id="hpc">
                    </div>
                    <button type="submit" onclick="document.getElementById('hpc').value=document.getElementById('hp').value"
                        class="w-full btn-green py-3 rounded-lg text-sm font-semibold">
                        Start Learning for Free
                    </button>
                </form>
                <p class="text-xs text-gray-400 text-center mt-3 leading-relaxed">
                    By continuing, you accept our <a href="#" class="underline">Terms of Use</a>, our <a href="#" class="underline">Privacy Policy</a> and that your data is stored in the USA.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 2: WHY DATACAMP FOR BUSINESS --}}
<section style="background:#05192D">
    <div class="max-w-7xl mx-auto px-6 py-16 w-full">
        <div class="grid grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-white/40 text-xs uppercase tracking-widest mb-3">WHY DATACAMP FOR BUSINESS?</p>
                <h2 class="text-4xl font-bold text-white mb-6">Tailored data and AI upskilling for your business</h2>
                <div class="flex gap-3 mb-6 flex-wrap">
                    <a href="#" class="border border-white/40 text-white px-5 py-2.5 rounded text-sm hover:border-white">DataCamp for Business</a>
                    <a href="#" class="border border-white/40 text-white px-5 py-2.5 rounded text-sm hover:border-white">Request Demo</a>
                </div>
                <p class="text-white/50 text-sm mb-4">Explore industry-wide solutions by DataCamp</p>
                <div class="flex gap-2 flex-wrap">
                    @foreach(['Healthcare', 'Technology', 'Energy', 'Government'] as $s)
                    <span class="border border-white/20 text-white/70 text-sm px-4 py-2 rounded-full cursor-pointer hover:border-white/50">{{ $s }} →</span>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                @foreach(['Google','Apple','Microsoft','ING','3M','Live Nation','Blackstone','Uber','Tesla','Oxford','Duke','AXA'] as $b)
                <div class="border border-white/10 rounded-xl p-4 flex items-center justify-center bg-white/5 h-20">
                    <span class="text-white/60 text-sm font-semibold">{{ $b }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3: A PATH FOR EVERY GOAL --}}
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16 w-full">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">A path for every goal</h2>
        <div class="flex items-center justify-between mb-8">
            <div class="flex gap-2">
                @foreach(['Top Courses','AI','Career Tracks','Skill Tracks'] as $i => $tab)
                <button onclick="switchTab({{ $i }})" id="tab{{ $i }}"
                    class="{{ $i==0 ? 'tab-active' : 'tab-inactive' }} text-sm px-5 py-2 rounded-full font-medium transition-all">
                    {{ $tab }}
                </button>
                @endforeach
            </div>
            <a href="{{ route('courses') }}" class="border border-gray-900 text-gray-900 text-sm px-5 py-2 rounded hover:bg-gray-900 hover:text-white transition-colors">Explore Top Courses</a>
        </div>
        <div class="grid grid-cols-3 gap-4">
            @php $courses = \App\Models\Course::take(6)->get(); @endphp
            @foreach($courses as $course)
            <a href="{{ route('course.detail', $course->course_id) }}"
               class="card-hover bg-white border border-gray-100 rounded-xl p-5 cursor-pointer block">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xs text-gray-500 font-medium uppercase">{{ $course->level_id }}</span>
                    <span class="text-xs text-gray-500">{{ $course->durasi }}</span>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $course->nama_course }}</h3>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $course->deskripsi }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-green-600 font-medium">See Details →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 4: STATS --}}
<section style="background:#05192D">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center w-full">
        <h2 class="text-3xl font-bold text-white mb-12">Join 14+ million learners worldwide</h2>
        <div class="grid grid-cols-4 gap-8">
            @foreach([['14M+','Learners worldwide'],['10K+','Courses & projects'],['350+','Expert instructors'],['92%','Completion rate']] as $s)
            <div>
                <div class="text-5xl font-bold dc-green mb-3">{{ $s[0] }}</div>
                <div class="text-white/50 text-base">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer style="background:#05192D" class="border-t border-white/10 py-8">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg width="22" height="22" viewBox="0 0 100 100" fill="none"><path d="M50 10L90 50L50 90L10 50L50 10Z" fill="#03EF62"/><path d="M50 30L70 50L50 70L30 50L50 30Z" fill="#05192D"/></svg>
            <span class="text-white font-bold italic">datacamp</span>
        </div>
        <div class="flex gap-6 text-xs text-white/40">
            <a href="#" class="hover:text-white">Privacy Policy</a>
            <a href="#" class="hover:text-white">Terms of Use</a>
            <a href="#" class="hover:text-white">Cookie Notice</a>
        </div>
        <p class="text-xs text-white/40">© {{ date('Y') }} DataCamp, Inc.</p>
    </div>
</footer>

<script>
function switchTab(i) {
    document.querySelectorAll('[id^="tab"]').forEach((t,j) => {
        t.className = j==i
            ? 'tab-active text-sm px-5 py-2 rounded-full font-medium transition-all'
            : 'tab-inactive text-sm px-5 py-2 rounded-full font-medium transition-all';
    });
}
function togglePw() {
    const i = document.getElementById('hp');
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>