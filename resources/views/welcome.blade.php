<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCamp - Learn Data Science and AI Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .dc-navy { background: #05192D; }
        .dc-green { color: #03EF62; }
        .bg-dc-green { background: #03EF62; }
        .btn-green { background: #03EF62; color: #05192D; font-weight: 600; }
        .btn-green:hover { background: #00d455; }
        .nav-link { color: #fff; font-size: 14px; }
        .nav-link:hover { color: #03EF62; }
        .hero-grid { background-image: linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px); background-size: 60px 60px; }
        .tab-active { background: #05192D; color: #fff; }
        .tab-inactive { background: transparent; color: #05192D; border: 1px solid #05192D; }
        .card-hover:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.12); transform: translateY(-2px); transition: all .2s; }
        input:focus { outline: none; border-color: #03EF62 !important; }
    </style>
</head>
<body class="bg-white">

{{-- NAVBAR --}}
<nav class="sticky top-0 z-50 border-b border-white/10" style="background:#05192D">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-8">
            {{-- Logo DataCamp asli --}}
            <a href="/" class="flex items-center gap-2">
                <svg width="26" height="22" viewBox="0 0 52 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0H14C22.837 0 30 7.163 30 16C30 24.837 22.837 32 14 32H0V0Z" fill="white"/>
                <path d="M14 8H6V24H14C18.418 24 22 20.418 22 16C22 11.582 18.418 8 14 8Z" fill="#1b1d2a"/>
                <path d="M36 0L52 16L36 32V20L44 16L36 12V0Z" fill="white"/>
            </svg>
                <span class="text-white font-bold text-lg italic">datacamp</span>
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

{{-- HERO --}}
<div class="hero-grid" style="background:#05192D">
    <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-5xl font-bold text-white leading-tight mb-4">Learn data and<br>AI skills</h1>
            <p class="text-white/65 text-base leading-relaxed mb-8 max-w-lg">Master in-demand skills in Python, ChatGPT, Power BI, and more through interactive courses, real-world projects, and industry recognized certifications</p>
            <div class="flex gap-3 mb-8 flex-wrap">
                <a href="{{ route('register') }}" class="btn-green px-6 py-3 rounded text-sm">Start Learning for Free</a>
                <a href="#" class="border border-white/40 text-white px-6 py-3 rounded text-sm hover:border-white">DataCamp for Business</a>
            </div>
            <div class="flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="#03EF62"><circle cx="10" cy="10" r="9" stroke="#03EF62" stroke-width="1.5" fill="none"/><text x="10" y="14" text-anchor="middle" font-size="10" fill="#03EF62">G</text></svg>
                <span class="text-yellow-400 text-sm">★★★★½</span>
                <span class="text-white/50 text-sm">4.7 out of 5</span>
            </div>
        </div>

        {{-- Register Form --}}
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <h2 class="text-xl font-bold text-center text-gray-900 mb-5">Create Your Free Account</h2>
            <div class="grid grid-cols-4 gap-2 mb-4">
                <button class="flex items-center justify-center border border-gray-200 rounded-lg py-2.5 hover:bg-gray-50">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                </button>
                <button class="flex items-center justify-center border border-gray-200 rounded-lg py-2.5 hover:bg-gray-50">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </button>
                <button class="flex items-center justify-center border border-gray-200 rounded-lg py-2.5 hover:bg-gray-50">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </button>
                <button class="flex items-center justify-center border border-gray-200 rounded-lg py-2.5 hover:bg-gray-50">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="black"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                </button>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">or</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
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

{{-- BRAND LOGOS --}}
<div class="border-b border-gray-100 py-5 bg-white">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-center gap-10 flex-wrap">
        <img src="https://www.datacamp.com/datacamp-sq.svg" alt="" class="h-5 opacity-40" onerror="this.style.display='none'">
        <span class="text-gray-400 font-bold text-sm flex items-center gap-1"><span class="text-blue-500">🐍</span> python</span>
        <span class="text-gray-400 font-bold text-sm italic">R</span>
        <span class="text-gray-400 font-bold text-sm">SQL</span>
        <span class="text-gray-400 font-bold text-sm">✦ ChatGPT</span>
        <span class="text-gray-400 font-bold text-sm">📊 Power BI</span>
        <span class="text-gray-400 font-bold text-sm">📗 Excel</span>
        <span class="text-gray-400 font-bold text-sm">❄️ Snowflake</span>
        <span class="text-gray-400 font-bold text-sm">⎇ git</span>
    </div>
</div>

{{-- WHY DATACAMP --}}
<div style="background:#05192D" class="py-16">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 gap-16 items-center">
        <div>
            <p class="text-white/40 text-xs uppercase tracking-widest mb-3">WHY DATACAMP FOR BUSINESS?</p>
            <h2 class="text-3xl font-bold text-white mb-5">Tailored data and AI upskilling for your business</h2>
            <div class="flex gap-3 mb-5 flex-wrap">
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
            <div class="border border-white/10 rounded-xl p-4 flex items-center justify-center bg-white/5">
                <span class="text-white/60 text-sm font-semibold">{{ $b }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- A PATH FOR EVERY GOAL --}}
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">A path for every goal</h2>
        <div class="flex items-center justify-between mb-8">
            <div class="flex gap-2" id="tabs">
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
            <a href="{{ route('course.detail', $course->slug) }}"
               class="card-hover bg-white border border-gray-100 rounded-xl p-5 cursor-pointer block">
                <div class="flex items-center gap-2 mb-3">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="#05192D"><rect width="16" height="16" rx="2"/><path d="M4 8l3 3 5-5" stroke="white" stroke-width="1.5" fill="none"/></svg>
                    <span class="text-xs text-gray-500 font-medium uppercase">{{ $course->difficulty }}</span>
                    <span class="text-gray-300 text-xs">·</span>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="#999" stroke-width="1.5"><circle cx="6" cy="6" r="5"/><path d="M6 3v3l2 2"/></svg>
                    <span class="text-xs text-gray-500">{{ $course->duration_hours }} hr</span>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $course->description }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-green-600 font-medium flex items-center gap-1">See Details →</span>
                    @if($course->category == 'AI' || $course->category == 'Multi-Agent')
                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded font-medium">✦ AI NATIVE</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- STATS --}}
<div style="background:#05192D" class="py-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-white mb-10">Join 14+ million learners worldwide</h2>
        <div class="grid grid-cols-4 gap-8">
            @foreach([['14M+','Learners worldwide'],['10K+','Courses & projects'],['350+','Expert instructors'],['92%','Completion rate']] as $s)
            <div>
                <div class="text-4xl font-bold dc-green mb-2">{{ $s[0] }}</div>
                <div class="text-white/50 text-sm">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

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
        t.className = j==i ? 'tab-active text-sm px-5 py-2 rounded-full font-medium transition-all' : 'tab-inactive text-sm px-5 py-2 rounded-full font-medium transition-all';
    });
}
function togglePw() {
    const i = document.getElementById('hp');
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>