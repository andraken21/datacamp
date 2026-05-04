<nav class="flex items-center justify-between px-6 py-3 border-b border-white/10" style="background:#0a0e1a">
    <a href="/" class="text-green-400 text-base font-medium">&#9632; datacamp</a>
    <div class="flex items-center gap-6 text-sm text-white/60">
        <a href="{{ route('courses') }}" class="hover:text-white {{ request()->routeIs('courses') ? 'text-white' : '' }}">Kursus</a>
        <a href="{{ route('resources') }}" class="hover:text-white {{ request()->routeIs('resources') ? 'text-white' : '' }}">Resources</a>
        <a href="{{ route('harga') }}" class="hover:text-white {{ request()->routeIs('harga') ? 'text-white' : '' }}">Harga</a>
        <a href="{{ route('katalog') }}" class="hover:text-white {{ request()->routeIs('katalog') ? 'text-white' : '' }}">Katalog</a>
    </div>
    <div class="flex items-center gap-3">
        @auth
        <div class="relative">
            <button onclick="toggleDropdown()" class="flex items-center gap-2 text-sm text-white/80 hover:text-white">
                <div class="w-7 h-7 rounded-full bg-green-400/20 border border-green-400/30 flex items-center justify-center text-green-400 text-xs font-medium">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                {{ Auth::user()->name }}
                <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><path d="M6 8L1 3h10L6 8z"/></svg>
            </button>
            <div id="dropdown" class="absolute right-0 top-10 w-44 bg-gray-900 border border-white/10 rounded-xl py-1 hidden z-50">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5">Dashboard</a>
                <a href="/profile" class="block px-4 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5">Profile</a>
                <div class="border-t border-white/10 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-white/5">Keluar</button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="text-sm border border-white/25 px-3 py-1.5 rounded-md hover:border-white">Masuk</a>
        <a href="{{ route('register') }}" class="text-sm bg-green-400 text-gray-900 font-medium px-3 py-1.5 rounded-md hover:bg-green-300">Mulai Belajar</a>
        @endauth
    </div>
</nav>

<script>
function toggleDropdown() {
    const dd = document.getElementById('dropdown');
    dd.classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const dd = document.getElementById('dropdown');
    if (dd && !e.target.closest('.relative')) {
        dd.classList.add('hidden');
    }
});
</script>