{{-- ===== NAVBAR — pixel-perfect DataCamp ===== --}}
<nav style="background:#1b1d2a; border-bottom:1px solid rgba(255,255,255,0.07); position:sticky; top:0; z-index:50;">
    <div style="display:flex; align-items:center; height:56px; padding:0 20px; gap:2px;">

        {{-- ── LOGO ── --}}
        <a href="/" style="display:flex; align-items:center; gap:8px; text-decoration:none; margin-right:10px; flex-shrink:0;">
            {{-- DataCamp "Dk" SVG logo (gambar 2) --}}
            <svg width="26" height="22" viewBox="0 0 52 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0H14C22.837 0 30 7.163 30 16C30 24.837 22.837 32 14 32H0V0Z" fill="white"/>
                <path d="M14 8H6V24H14C18.418 24 22 20.418 22 16C22 11.582 18.418 8 14 8Z" fill="#1b1d2a"/>
                <path d="M36 0L52 16L36 32V20L44 16L36 12V0Z" fill="white"/>
            </svg>
            <span style="color:#fff; font-size:15px; font-weight:600; letter-spacing:-0.01em; font-family:'Inter',sans-serif;">datacamp</span>
        </a>

        {{-- ── NAV LINKS ── --}}
        @auth
        <a href="{{ route('dashboard') }}"
           style="display:flex; align-items:center; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:{{ request()->routeIs('dashboard') ? '600' : '400' }}; color:{{ request()->routeIs('dashboard') ? '#0f1117' : 'rgba(255,255,255,0.65)' }}; background:{{ request()->routeIs('dashboard') ? '#fff' : 'transparent' }}; text-decoration:none; transition:all 0.15s; white-space:nowrap;"
           onmouseover="if(!{{ request()->routeIs('dashboard') ? 'true' : 'false' }})this.style.background='rgba(255,255,255,0.08)'; this.style.color='{{ request()->routeIs('dashboard') ? '#0f1117' : '#fff' }}';"
           onmouseout="if(!{{ request()->routeIs('dashboard') ? 'true' : 'false' }})this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
            Home
        </a>
        @endauth

        <a href="{{ route('courses') }}"
           style="display:flex; align-items:center; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:400; color:rgba(255,255,255,0.65); text-decoration:none; white-space:nowrap;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#fff';"
           onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
            Learn
        </a>

        <a href="#"
           style="display:flex; align-items:center; gap:5px; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:400; color:rgba(255,255,255,0.65); text-decoration:none; white-space:nowrap;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)';"
           onmouseout="this.style.background='transparent';">
            {{-- sparkle icon --}}
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="url(#aigrad)" stroke-width="2">
                <defs><linearGradient id="aigrad" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#a855f7"/><stop offset="100%" stop-color="#ec4899"/></linearGradient></defs>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6L12 2z"/>
            </svg>
            <span style="background:linear-gradient(to right,#a855f7,#ec4899); -webkit-background-clip:text; -webkit-text-fill-color:transparent; font-weight:500;">AI Native</span>
        </a>

        <a href="#"
           style="display:flex; align-items:center; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:400; color:rgba(255,255,255,0.65); text-decoration:none; white-space:nowrap;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#fff';"
           onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
            Certification
        </a>

        <a href="#"
           style="display:flex; align-items:center; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:400; color:rgba(255,255,255,0.65); text-decoration:none; white-space:nowrap;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#fff';"
           onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
            Sandbox
        </a>

        <a href="#"
           style="display:flex; align-items:center; gap:3px; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:400; color:rgba(255,255,255,0.65); text-decoration:none; white-space:nowrap;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#fff';"
           onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
            For Business
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="#"
           style="display:flex; align-items:center; gap:4px; padding:5px 14px; border-radius:999px; font-size:13.5px; font-weight:400; color:rgba(255,255,255,0.65); text-decoration:none; white-space:nowrap;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#fff';"
           onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
            DataLab
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>

        {{-- SPACER --}}
        <div style="flex:1;"></div>

        {{-- ── SEARCH ── --}}
        <div style="position:relative; margin-right:8px;">
            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:rgba(255,255,255,0.35); pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Search"
                style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.7); font-size:13px; padding:6px 14px 6px 36px; border-radius:999px; width:200px; outline:none; font-family:'Inter',sans-serif;"
                onfocus="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.2)';"
                onblur="this.style.background='rgba(255,255,255,0.07)'; this.style.borderColor='rgba(255,255,255,0.1)';">
        </div>

        @auth
        {{-- AI Credits --}}
        <a href="#" style="display:flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:13px; font-weight:400; color:rgba(255,255,255,0.7); text-decoration:none; white-space:nowrap; margin-right:4px;"
           onmouseover="this.style.background='rgba(255,255,255,0.07)';"
           onmouseout="this.style.background='transparent';">
            <span style="display:inline-flex; width:18px; height:18px; border-radius:50%; background:#03ef62; align-items:center; justify-content:center;">
                <span style="width:8px; height:8px; border-radius:50%; background:#1b1d2a; display:block;"></span>
            </span>
            AI Credits
        </a>

        {{-- Upgrade --}}
        <a href="{{ route('harga') }}"
           style="background:#7c3aed; color:#fff; font-size:13px; font-weight:600; padding:6px 16px; border-radius:999px; text-decoration:none; white-space:nowrap; margin-right:8px; transition:background 0.15s;"
           onmouseover="this.style.background='#6d28d9';"
           onmouseout="this.style.background='#7c3aed';">
            Upgrade
        </a>

        {{-- Globe EN --}}
        <button style="display:flex; align-items:center; gap:4px; background:none; border:none; color:rgba(255,255,255,0.5); font-size:13px; cursor:pointer; padding:4px 6px; margin-right:4px;"
                onmouseover="this.style.color='#fff';" onmouseout="this.style.color='rgba(255,255,255,0.5)';">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
            EN
        </button>

        {{-- Bell --}}
        <button style="background:none; border:none; color:rgba(255,255,255,0.5); cursor:pointer; padding:4px 6px; margin-right:8px;"
                onmouseover="this.style.color='#fff';" onmouseout="this.style.color='rgba(255,255,255,0.5)';">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </button>

        {{-- Avatar dropdown --}}
        <div style="position:relative;">
            <button onclick="toggleDropdown()" style="display:flex; align-items:center; gap:6px; background:none; border:none; cursor:pointer; padding:0;">
                <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#03ef62,#00a844); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#0f1117; font-family:'Inter',sans-serif;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <svg width="12" height="12" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div id="dropdown" style="display:none; position:absolute; right:0; top:44px; width:196px; background:#22253a; border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:6px 0; box-shadow:0 8px 24px rgba(0,0,0,0.4); z-index:100;">
                <div style="padding:10px 16px 10px; border-bottom:1px solid rgba(255,255,255,0.07); margin-bottom:4px;">
                    <p style="margin:0; font-size:13px; font-weight:600; color:#fff; font-family:'Inter',sans-serif;">{{ Auth::user()->name }}</p>
                    <p style="margin:2px 0 0; font-size:11px; color:rgba(255,255,255,0.4); font-family:'Inter',sans-serif; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:10px; padding:8px 16px; font-size:13px; color:rgba(255,255,255,0.65); text-decoration:none; font-family:'Inter',sans-serif;"
                   onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#fff';"
                   onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
                <a href="/profile" style="display:flex; align-items:center; gap:10px; padding:8px 16px; font-size:13px; color:rgba(255,255,255,0.65); text-decoration:none; font-family:'Inter',sans-serif;"
                   onmouseover="this.style.background='rgba(255,255,255,0.06)'; this.style.color='#fff';"
                   onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.65)';">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </a>
                <div style="border-top:1px solid rgba(255,255,255,0.07); margin:4px 0;"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="display:flex; align-items:center; gap:10px; padding:8px 16px; font-size:13px; color:#f87171; background:none; border:none; cursor:pointer; width:100%; text-align:left; font-family:'Inter',sans-serif;"
                            onmouseover="this.style.background='rgba(255,255,255,0.06)';"
                            onmouseout="this.style.background='transparent';">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        @else
        <a href="{{ route('login') }}"
           style="border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.8); font-size:13px; font-weight:500; padding:6px 16px; border-radius:999px; text-decoration:none; margin-right:8px; font-family:'Inter',sans-serif;"
           onmouseover="this.style.borderColor='rgba(255,255,255,0.4)'; this.style.color='#fff';"
           onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.color='rgba(255,255,255,0.8)';">
            Masuk
        </a>
        <a href="{{ route('register') }}"
           style="background:#03ef62; color:#0a0a0a; font-size:13px; font-weight:700; padding:6px 16px; border-radius:999px; text-decoration:none; font-family:'Inter',sans-serif;"
           onmouseover="this.style.background='#00d455';"
           onmouseout="this.style.background='#03ef62';">
            Mulai Belajar
        </a>
        @endauth
    </div>
</nav>

<script>
function toggleDropdown() {
    const dd = document.getElementById('dropdown');
    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    const dd = document.getElementById('dropdown');
    if (dd && !e.target.closest('[onclick="toggleDropdown()"]') && !e.target.closest('#dropdown')) {
        dd.style.display = 'none';
    }
});
</script>