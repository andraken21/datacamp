<nav class="sticky top-0 z-50 bg-white border-b border-gray-200">
    <div class="max-w-full px-6 py-2.5 flex items-center justify-between">
        {{-- Logo --}}
<div class="flex items-center gap-6">
    <a href="/" class="flex items-center gap-2 shrink-0">
        <div class="flex items-center gap-1.5">
            <div class="w-7 h-7 rounded flex items-center justify-center" style="background:#03EF62">
                <span class="font-black text-sm" style="font-style:normal;color:#05192D">D</span>
            </div>
            <svg width="10" height="14" viewBox="0 0 10 14" fill="none">
                <path d="M1 1L9 7L1 13" stroke="#03EF62" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="font-bold text-gray-900 text-base" style="font-style:italic;letter-spacing:-0.5px">datacamp</span>
        </div>
    </a>
            </a>
            <div class="hidden md:flex items-center gap-1">
    <a href="/" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100 {{ request()->is('/') ? 'bg-gray-900 text-white font-semibold' : '' }}">Home</a>

    <a href="{{ route('learn') }}" class="px-3 py-1.5 text-sm rounded-full font-semibold transition-colors {{ request()->routeIs('learn') || request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">Learn</a>

    <a href="{{ route('ai-native') }}" class="px-3 py-1.5 text-sm text-purple-600 hover:text-purple-700 rounded-full hover:bg-purple-50 flex items-center gap-1 {{ request()->routeIs('ai-native') ? 'bg-purple-100' : '' }}">
    <span>✦</span> AI Native
    </a>

    <a href="{{ route('certification.index') }}" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100 {{ request()->routeIs('certification*') ? 'bg-gray-900 text-white font-semibold' : '' }}">Certification</a>
    <a href="#" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100">Sandbox</a>
    
</div>

        {{-- Right side --}}
        <div class="flex items-center gap-2">
            {{-- Search --}}
            <div class="relative hidden md:block">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search" class="bg-gray-100 border-none text-sm pl-9 pr-4 py-1.5 rounded-lg w-44 focus:outline-none focus:ring-2 focus:ring-green-400 focus:w-56 transition-all">
            </div>

            @auth
            {{-- AI Credits --}}
            <div class="flex items-center gap-1.5 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full">
                <div class="w-4 h-4 rounded-full bg-green-500 flex items-center justify-center">
                    <svg width="8" height="8" viewBox="0 0 8 8" fill="white"><path d="M4 1L7 4L4 7L1 4Z"/></svg>
                </div>
                <span class="text-xs font-medium text-green-700">AI Credits</span>
            </div>
            {{-- Upgrade --}}
            <a href="{{ route('harga') }}" class="bg-purple-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-purple-700">Upgrade</a>
            {{-- Language --}}
            <span class="text-gray-500 text-sm flex items-center gap-1 cursor-pointer">🌐 EN</span>
            {{-- Notifications --}}
            <button class="text-gray-500 hover:text-gray-700 relative">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </button>
            {{-- Avatar --}}
            <div class="relative">
                <button onclick="toggleDropdown()" class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>
                <div id="dropdown" class="hidden absolute right-0 top-10 w-52 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                    <a href="{{ route('katalog') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Katalog Tools</a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50">Log Out</button>
                    </form>
                </div>
            </div>
            @else
            <span class="text-gray-500 text-sm flex items-center gap-1 cursor-pointer">🌐 EN</span>
            <a href="{{ route('login') }}" class="text-sm text-gray-700 font-medium px-3 py-1.5 border border-gray-300 rounded-lg hover:border-gray-400">Log In</a>
            <a href="{{ route('register') }}" class="text-sm font-semibold px-3 py-1.5 rounded-lg" style="background:#03EF62;color:#05192D">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<script>
function toggleDropdown() {
    document.getElementById('dropdown').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const dd = document.getElementById('dropdown');
    if (dd && !e.target.closest('.relative')) dd.classList.add('hidden');
});
</script>