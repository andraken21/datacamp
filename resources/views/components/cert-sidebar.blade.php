<aside class="w-56 shrink-0 pt-6 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto" style="background:#05192D;border-right:1px solid rgba(255,255,255,0.08)">
    <div class="px-3 mb-6">
        <a href="{{ route('dashboard') }}" class="cert-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Dashboard
        </a>
    </div>

    <div class="px-3 mb-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">CERTIFICATIONS</p>

        {{-- Career --}}
        <div>
            <button onclick="toggleCert('career')" class="cert-sidebar-link w-full justify-between">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    Career
                </div>
                <svg id="career-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    class="{{ request()->routeIs('certification.career.*') ? 'rotate-180' : '' }}">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </button>
            <div id="career-sub" class="ml-4 mt-1 space-y-1 {{ request()->routeIs('certification.career.*') ? '' : 'hidden' }}">
                <a href="{{ route('certification.career.analyst') }}"   class="cert-sidebar-link text-xs {{ request()->routeIs('certification.career.analyst')   ? 'active' : '' }}">Data Analyst</a>
                <a href="{{ route('certification.career.scientist') }}" class="cert-sidebar-link text-xs {{ request()->routeIs('certification.career.scientist') ? 'active' : '' }}">Data Scientist</a>
                <a href="{{ route('certification.career.engineer') }}"  class="cert-sidebar-link text-xs {{ request()->routeIs('certification.career.engineer')  ? 'active' : '' }}">Data Engineer</a>
            </div>
        </div>

        {{-- Technology --}}
        <div>
            <button onclick="toggleCert('tech')" class="cert-sidebar-link w-full justify-between">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83"/></svg>
                    Technology
                </div>
                <svg id="tech-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    class="{{ request()->routeIs('certification.tech.*') ? 'rotate-180' : '' }}">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </button>
            <div id="tech-sub" class="ml-4 mt-1 space-y-1 {{ request()->routeIs('certification.tech.*') ? '' : 'hidden' }}">
                <a href="{{ route('certification.tech.powerbi') }}" class="cert-sidebar-link text-xs {{ request()->routeIs('certification.tech.powerbi') ? 'active' : '' }}">Power BI</a>
                <a href="{{ route('certification.tech.tableau') }}" class="cert-sidebar-link text-xs {{ request()->routeIs('certification.tech.tableau') ? 'active' : '' }}">Tableau</a>
                <a href="{{ route('certification.tech.sql') }}"     class="cert-sidebar-link text-xs {{ request()->routeIs('certification.tech.sql')     ? 'active' : '' }}">SQL</a>
            </div>
        </div>

        <a href="{{ route('certification.cpe') }}"     class="cert-sidebar-link {{ request()->routeIs('certification.cpe')     ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            CPE
        </a>
        <a href="{{ route('certification.theory') }}"  class="cert-sidebar-link {{ request()->routeIs('certification.theory')  ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Theory
        </a>
        <a href="{{ route('certification.history') }}" class="cert-sidebar-link {{ request()->routeIs('certification.history') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            History
        </a>
    </div>

    <div class="mt-4 pt-4 px-3" style="border-top:1px solid rgba(255,255,255,0.08)">
        <a href="{{ route('faq') }}"      class="cert-sidebar-link {{ request()->routeIs('faq')      ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            FAQ
        </a>
        <a href="{{ route('feedback') }}" class="cert-sidebar-link {{ request()->routeIs('feedback') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Give feedback
        </a>
    </div>
</aside>

<script>
function toggleCert(id) {
    const sub   = document.getElementById(id + '-sub');
    const arrow = document.getElementById(id + '-arrow');
    sub.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}
</script>