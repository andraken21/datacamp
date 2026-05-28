<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Feedback - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background:#f8f9fa; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
        .cert-sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:rgba(255,255,255,0.7); cursor:pointer; text-decoration:none; transition:background 0.15s; }
        .cert-sidebar-link:hover { background:rgba(255,255,255,0.08); color:white; }
        .cert-sidebar-link.active { background:rgba(3,239,98,0.15); color:#03EF62; font-weight:500; }
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
            <a href="{{ route('certification.index') }}" class="cert-sidebar-link">Career</a>
            <a href="{{ route('certification.index') }}" class="cert-sidebar-link">Technology</a>
            <a href="{{ route('certification.cpe') }}" class="cert-sidebar-link">CPE</a>
            <a href="{{ route('certification.theory') }}" class="cert-sidebar-link">Theory</a>
            <a href="{{ route('certification.history') }}" class="cert-sidebar-link">History</a>
        </div>
        <div class="mt-4 pt-4 px-3" style="border-top:1px solid rgba(255,255,255,0.08)">
            <a href="{{ route('faq') }}" class="cert-sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                FAQ
            </a>
            <a href="{{ route('feedback') }}" class="cert-sidebar-link active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Give feedback
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-lg">

            @if(session('feedback_success'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Your feedback has been sent! Thank you. 🙏
            </div>
            @endif

            {{-- Feedback Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Provide Feedback</h2>
                    <a href="{{ route('certification.index') }}" class="text-gray-400 hover:text-gray-600">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </a>
                </div>

                {{-- Form --}}
                <div class="px-6 py-5">
                    @auth
                    <form method="POST" action="{{ route('feedback.submit') }}" id="feedback-form">
                        @csrf

                        {{-- Feedback type --}}
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-2">Tipe feedback (opsional)</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Bug report', 'Feature request', 'Content issue', 'General feedback'] as $type)
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipe" value="{{ $type }}" class="hidden peer">
                                    <span class="px-3 py-1 rounded-full text-xs border border-gray-200 text-gray-600 peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 hover:bg-gray-50 transition-colors select-none">
                                        {{ $type }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Textarea --}}
                        <div class="mb-4">
                            <textarea name="isi_feedback" id="feedback-text" rows="5" required
                                placeholder="Your feedback here"
                                maxlength="2000"
                                oninput="updateCount(this)"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-gray-400 resize-none placeholder-gray-400">{{ old('isi_feedback') }}</textarea>
                            <div class="flex justify-end mt-1">
                                <span id="char-count" class="text-xs text-gray-400">0 / 2000</span>
                            </div>
                        </div>

                        {{-- Halaman --}}
                        <input type="hidden" name="halaman" value="{{ request()->headers->get('referer', 'certification') }}">

                        {{-- Buttons --}}
                        <div class="flex items-center justify-between">
                            <a href="{{ route('certification.index') }}"
                               class="px-5 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-50"
                                style="background:#03EF62;color:#05192D"
                                id="submit-btn">
                                Submit Feedback
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-500 mb-4">Login untuk memberikan feedback</p>
                        <a href="{{ route('login') }}" class="px-6 py-2 rounded-lg text-sm font-semibold text-white" style="background:#05192D">
                            Login
                        </a>
                    </div>
                    @endauth
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center mt-4">
                Feedback kamu membantu kami meningkatkan platform.
            </p>
        </div>
    </main>
</div>

<script>
function updateCount(el) {
    document.getElementById('char-count').textContent = el.value.length + ' / 2000';
}
</script>
</body>
</html>