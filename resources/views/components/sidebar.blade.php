<aside class="w-56 shrink-0 bg-white border-r border-gray-200 pt-4 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto">
    <div class="px-3 mb-4">
        <div class="flex items-center gap-3 p-3 mb-2">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->xp ?? 0 }} XP</p>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('my-activity') }}" class="sidebar-link {{ request()->routeIs('my-activity') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            My Activity
        </a>
        <a href="{{ route('leaderboard') }}" class="sidebar-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Leaderboard
            <span class="ml-auto text-xs bg-green-500 text-white px-1.5 py-0.5 rounded font-medium">NEW</span>
        </a>
    </div>

    <div class="px-3 mb-2">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-1">LEARN</p>
        <a href="{{ route('tracks') }}" class="sidebar-link {{ request()->routeIs('tracks*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Tracks
            <svg class="ml-auto w-3 h-3 {{ request()->routeIs('tracks*') ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
        </a>
        @if(request()->routeIs('tracks*'))
        <div class="ml-4 mt-1 space-y-1">
            <a href="{{ route('tracks.career') }}" class="sidebar-link text-xs {{ request()->routeIs('tracks.career') ? 'active' : '' }}">Career tracks</a>
            <a href="{{ route('tracks.skill') }}" class="sidebar-link text-xs {{ request()->routeIs('tracks.skill') ? 'active' : '' }}">Skill tracks</a>
        </div>
        @endif
        <a href="{{ route('courses') }}" class="sidebar-link {{ request()->routeIs('courses') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
            Courses
        </a>
        <a href="{{ route('practice') }}" class="sidebar-link {{ request()->routeIs('practice') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            Practice
        </a>
        <a href="{{ route('assessments') }}" class="sidebar-link {{ request()->routeIs('assessments') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Assessments
        </a>
        <a href="{{ route('tutorials.index') }}" class="sidebar-link {{ request()->routeIs('tutorials*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Tutorials
        </a>
    </div>

    <div class="px-3 mb-2">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-1">APPLY</p>
        <a href="#" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Real World Projects
        </a>
        <a href="#" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            Code Alongs
        </a>
        <a href="#" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Competitions
        </a>
    </div>

    <div class="mx-3 mt-4 p-3 bg-orange-50 border border-orange-200 rounded-xl">
        <p class="text-xs font-semibold text-orange-700 mb-2">Getting Started (2/4)</p>
        <div class="w-full bg-orange-200 rounded-full h-1.5">
            <div class="bg-orange-500 h-1.5 rounded-full" style="width:50%"></div>
        </div>
    </div>
</aside>