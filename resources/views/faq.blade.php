<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - DataCamp Certification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background:#f8f9fa; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
        .cert-sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:rgba(255,255,255,0.7); cursor:pointer; text-decoration:none; transition:background 0.15s; }
        .cert-sidebar-link:hover { background:rgba(255,255,255,0.08); color:white; }
        .cert-sidebar-link.active { background:rgba(3,239,98,0.15); color:#03EF62; font-weight:500; }
        .accordion-content { max-height:0; overflow:hidden; transition:max-height 0.3s ease; }
        .accordion-content.open { max-height:500px; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-56 shrink-0 pt-6 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto" style="background:#05192D;border-right:1px solid rgba(255,255,255,0.08)">
        <div class="px-3 mb-6">
            <a href="{{ route('dashboard') }}" class="cert-sidebar-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                Dashboard
            </a>
        </div>
        <div class="px-3 mb-2">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">CERTIFICATIONS</p>
            <a href="{{ route('certification.index') }}" class="cert-sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/></svg>
                Career
            </a>
            <a href="{{ route('certification.index') }}" class="cert-sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>
                Technology
            </a>
            <a href="{{ route('certification.cpe') }}" class="cert-sidebar-link">CPE</a>
            <a href="{{ route('certification.theory') }}" class="cert-sidebar-link">Theory</a>
            <a href="{{ route('certification.history') }}" class="cert-sidebar-link">History</a>
        </div>
        <div class="mt-4 pt-4 px-3" style="border-top:1px solid rgba(255,255,255,0.08)">
            <a href="{{ route('faq') }}" class="cert-sidebar-link active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                FAQ
            </a>
            <a href="{{ route('feedback') }}" class="cert-sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Give feedback
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 p-8 max-w-4xl">

        {{-- Header --}}
        <div class="rounded-2xl p-8 mb-8 flex items-center justify-between" style="background:#05192D">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Frequently Asked Questions</h1>
                <p class="text-gray-400 text-sm">Can't find the answer you're looking for?</p>
                <a href="{{ route('feedback') }}" class="text-green-400 text-sm hover:text-green-300 underline">Contact Support</a>
            </div>
            <div class="w-16 h-16 rounded-full bg-yellow-400 flex items-center justify-center text-2xl shrink-0">
                ❓
            </div>
        </div>

        @php
            $faqs = DB::table('faqs')->orderBy('seksi')->orderBy('urutan')->get()->groupBy('seksi');
        @endphp

        @foreach($faqs as $seksi => $items)
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ $seksi }}</h2>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden divide-y divide-gray-100">
                @foreach($items as $i => $faq)
                <div class="accordion-item">
                    <button onclick="toggleFaq({{ $faq->id }})"
                        class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-gray-50 transition-colors">
                        <span class="text-sm font-medium text-gray-900">{{ $faq->pertanyaan }}</span>
                        <svg id="arrow-{{ $faq->id }}" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" class="shrink-0 ml-4 transition-transform duration-300">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div id="faq-{{ $faq->id }}" class="accordion-content px-6">
                        <p class="text-sm text-gray-600 leading-relaxed pb-4">{{ $faq->jawaban }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Still need help --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
            <h3 class="text-base font-semibold text-gray-900 mb-2">Still have questions?</h3>
            <p class="text-sm text-gray-500 mb-4">Our support team is here to help you.</p>
            <a href="{{ route('feedback') }}"
               class="inline-block px-6 py-2 rounded-lg text-sm font-semibold text-white"
               style="background:#05192D">
                Contact Support
            </a>
        </div>

    </main>
</div>

<script>
function toggleFaq(id) {
    const content = document.getElementById('faq-' + id);
    const arrow = document.getElementById('arrow-' + id);
    const isOpen = content.classList.contains('open');

    // Close all
    document.querySelectorAll('.accordion-content').forEach(el => el.classList.remove('open'));
    document.querySelectorAll('[id^="arrow-"]').forEach(el => el.classList.remove('rotate-180'));

    // Open clicked if was closed
    if (!isOpen) {
        content.classList.add('open');
        arrow.classList.add('rotate-180');
    }
}
</script>
</body>
</html>