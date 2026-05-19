<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background:#f8f9fa; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
        .cert-sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:rgba(255,255,255,0.7); cursor:pointer; text-decoration:none; transition:background 0.15s; }
        .cert-sidebar-link:hover { background:rgba(255,255,255,0.08); color:white; }
        .cert-sidebar-link.active { background:rgba(3,239,98,0.15); color:#03EF62; font-weight:500; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
    </style>
</head>
<body>
<x-navbar />

<div class="flex min-h-screen">

    {{-- CERTIFICATION SIDEBAR --}}
    <aside class="w-56 shrink-0 pt-6 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto" style="background:#05192D;border-right:1px solid rgba(255,255,255,0.08)">
    <div class="px-3 mb-6">
        <a href="{{ route('dashboard') }}" class="cert-sidebar-link">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Dashboard
        </a>
    </div>

    <div class="px-3 mb-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">CERTIFICATIONS</p>

        <div>
            <button onclick="toggleCert('career')" class="cert-sidebar-link w-full justify-between">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    Career
                </div>
                <svg id="career-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="rotate-180"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div id="career-sub" class="ml-4 mt-1 space-y-1">
                <a href="#career" class="cert-sidebar-link text-xs">Data Analyst</a>
                <a href="#career" class="cert-sidebar-link text-xs">Data Scientist</a>
                <a href="#career" class="cert-sidebar-link text-xs">Data Engineer</a>
            </div>
        </div>

        <div>
            <button onclick="toggleCert('tech')" class="cert-sidebar-link w-full justify-between">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83"/></svg>
                    Technology
                </div>
                <svg id="tech-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="rotate-180"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div id="tech-sub" class="ml-4 mt-1 space-y-1">
                <a href="#tech" class="cert-sidebar-link text-xs">Power BI</a>
                <a href="#tech" class="cert-sidebar-link text-xs">Tableau</a>
                <a href="#tech" class="cert-sidebar-link text-xs">SQL</a>
            </div>
        </div>

        <a href="#cpe" class="cert-sidebar-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            CPE
        </a>
        <a href="#theory" class="cert-sidebar-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Theory
        </a>
        <a href="#history" class="cert-sidebar-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            History
        </a>
    </div>

    <div class="mt-4 pt-4 px-3" style="border-top:1px solid rgba(255,255,255,0.08)">
        <a href="#faq" class="cert-sidebar-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            FAQ
        </a>
        <a href="#" class="cert-sidebar-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Give feedback
        </a>
    </div>
</aside>

    {{-- MAIN --}}
    <main class="flex-1 overflow-y-auto">

        {{-- Hero Banner --}}
        <div class="p-8 flex items-center justify-between" style="background:#05192D">
            <div class="max-w-xl">
                <h1 class="text-2xl font-bold text-white mb-3">Welcome !</h1>
                <p class="text-sm text-gray-300 leading-relaxed mb-2">Ready to validate your skills? Earn certifications by passing structured assessments aligned to real roles. Choose from <strong class="text-white">career, technology,</strong> or <strong class="text-white">fundamentals certifications</strong> designed to validate applied skills.</p>
                <p class="text-sm text-gray-400">Not ready to sit the exam yet? Follow a guided learning plan to prepare.</p>
            </div>
            <div class="hidden lg:block">
                <svg width="140" height="120" viewBox="0 0 140 120" fill="none">
                    <polygon points="70,10 90,40 120,45 100,70 105,100 70,85 35,100 40,70 20,45 50,40" fill="none" stroke="#03EF62" stroke-width="2"/>
                    <polygon points="70,25 85,48 110,52 93,68 97,93 70,80 43,93 47,68 30,52 55,48" fill="#03EF62" opacity="0.15"/>
                    <circle cx="70" cy="58" r="18" fill="#03EF62" opacity="0.9"/>
                    <path d="M62 58l5 5 10-10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="p-6">

            {{-- Why DataCamp --}}
            <div class="card p-6 mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-2">Why DataCamp certifications?</h2>
                <p class="text-sm text-gray-600 leading-relaxed mb-2">DataCamp certifications are designed to reflect the skills employers hire for. Built using role-based competency frameworks and developed with industry input, each certification is earned through a structured, statistically rigorous assessment process that evaluates applied skills in realistic scenarios.</p>
                <p class="text-sm font-semibold text-gray-800">Prove your skills in real-world scenarios, not just in controlled practice.</p>
            </div>

            {{-- Our Certifications --}}
            <h2 class="text-xl font-bold text-gray-900 text-center mb-6">Our Certifications</h2>
            <div class="grid grid-cols-4 gap-4 mb-10">
                @php
                    $certTypes = [
                        ['icon'=>'⚡','color'=>'#06b6d4','bg'=>'#0e7490','title'=>'Technology Certification','desc'=>'Validate your skills in a technology'],
                        ['icon'=>'💼','color'=>'#22c55e','bg'=>'#15803d','title'=>'Career Certification','desc'=>'Prove that you can perform in a role'],
                        ['icon'=>'🧠','color'=>'#f59e0b','bg'=>'#b45309','title'=>'Fundamentals Certification','desc'=>'Showcase knowledge of key concepts'],
                        ['icon'=>'🎓','color'=>'#a855f7','bg'=>'#7e22ce','title'=>'CPE Credits','desc'=>'Earn recognized credits for your professional development'],
                    ];
                @endphp
                @foreach($certTypes as $ct)
                <div class="rounded-full aspect-square flex flex-col items-center justify-center text-center p-4 cursor-pointer hover:scale-105 transition-transform" style="background:{{ $ct['bg'] }}">
                    <span class="text-2xl mb-2">{{ $ct['icon'] }}</span>
                    <p class="text-xs font-bold text-white leading-tight mb-1" style="color:{{ $ct['color'] }}">{{ $ct['title'] }}</p>
                    <p class="text-xs text-white/70 leading-tight">{{ $ct['desc'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Career Certifications --}}
            <div class="mb-8" id="career">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Career Certifications <span class="text-gray-400">›</span></h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Our career certifications help you prove you have what it takes to perform in a data role.</p>
                <div class="grid grid-cols-3 gap-4">
                    @php
                        $careers = [
                            ['title'=>'Data Analyst','desc'=>'A data analyst sits between business intelligence and data science. They provide vital information to business stakeholders.'],
                            ['title'=>'Data Scientist','desc'=>'A data scientist is a professional responsible for collecting, analyzing and interpreting extremely large amounts of data.'],
                            ['title'=>'Data Engineer','desc'=>'A data engineer collects, stores, and pre-processes data for easy access and use within an organization.'],
                            ['title'=>'AI Engineer for Data Scientists','desc'=>'Prove foundational knowledge and skills required for pre-entry-level AI Engineers with a data science background.'],
                            ['title'=>'AI Engineer for Developers','desc'=>'Prove foundational knowledge and skills required for pre-entry-level AI Engineers with a developer background.'],
                        ];
                    @endphp
                    @foreach($careers as $cert)
                    <div class="card p-5">
                        <h4 class="text-sm font-bold text-gray-900 mb-2">{{ $cert['title'] }}</h4>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ $cert['desc'] }}</p>
                        <button class="text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Get Started</button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Technology Certifications --}}
            <div class="mb-8" id="tech">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Technology Certifications <span class="text-gray-400">›</span></h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Prove you have the skills in a technology to help you level up in your current role.</p>
                <div class="grid grid-cols-3 gap-4">
                    @php
                        $techCerts = [
                            ['title'=>'Exam PL-300: Microsoft Power BI Data Analyst','badge'=>'Partner Certification','promo'=>'50% off Microsoft Exam','desc'=>'This exam measures your ability to accomplish the following technical tasks in Power BI: prepare the data; model the data; visualize and analyze the data.','logo'=>'📊'],
                            ['title'=>'Tableau Certified Data Analyst','badge'=>'Prepare with DataCamp','desc'=>'This exam measures your ability to accomplish technical tasks in Tableau, mapped to the four exam domains: connecting data, analyzing data, creating charts and publishing content.','logo'=>'📈'],
                            ['title'=>'SQL Associate Certification','badge'=>'Created by DataCamp','desc'=>'The SQL Associate certification measures entry-level skills in data management and exploratory analysis using SQL.','logo'=>'SQL'],
                            ['title'=>'Microsoft Azure Fundamentals','badge'=>'Partner Certification','promo'=>'50% off Microsoft Exam','desc'=>'This exam measures your knowledge of cloud concepts and Azure Architectural components such as computing, networking, and storage.','logo'=>'☁️'],
                            ['title'=>'Exam AZ-204: Microsoft Azure Developer Associate','badge'=>'Partner Certification','promo'=>'50% off Microsoft Exam','desc'=>'This exam measures your ability to design, build, test, and maintain cloud applications and services on Microsoft Azure.','logo'=>'☁️'],
                            ['title'=>'Alteryx Designer Core Certification','badge'=>'Partner Certification','promo'=>'Free Certification','desc'=>'This exam measures your knowledge of Alteryx concepts and your proficiency with the core toolset in Alteryx Designer.','logo'=>'a'],
                            ['title'=>'Python Data Associate Certification','badge'=>'Created by DataCamp','desc'=>'The Python Data Associate certification measures entry-level skills in data management and exploratory analysis using Python.','logo'=>'🐍'],
                            ['title'=>'AWS Cloud Practitioner Certification','badge'=>'Partner Certification','desc'=>'This exam validates your understanding of AWS Cloud, services, and terminology, mapped to the four exam domains: cloud concepts, security and compliance.','logo'=>'aws'],
                            ['title'=>'GitHub Foundations Certification','badge'=>'Partner Certification','desc'=>'This exam validates your foundational knowledge of GitHub, including version control, repository management, collaboration workflows.','logo'=>'gh'],
                        ];
                    @endphp
                    @foreach($techCerts as $cert)
                    <div class="card p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white text-xs font-bold">{{ $cert['logo'] }}</div>
                            <h4 class="text-sm font-bold text-gray-900 flex-1 leading-tight">{{ $cert['title'] }}</h4>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="text-xs px-2 py-0.5 rounded border border-gray-200 text-gray-500 flex items-center gap-1">ℹ️ {{ $cert['badge'] }}</span>
                            @if(isset($cert['promo']))
                            <span class="text-xs px-2 py-0.5 rounded text-yellow-700 flex items-center gap-1" style="background:#fef3c7">⚡ {{ $cert['promo'] }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ $cert['desc'] }}</p>
                        <button class="text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Get Started</button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Specialist Package --}}
            <div class="rounded-2xl p-6 mb-8" style="background:linear-gradient(135deg,#1e1b4b,#312e81)">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <path d="M24 4L28 16L40 16L30 24L34 36L24 28L14 36L18 24L8 16L20 16Z" fill="none" stroke="#a78bfa" stroke-width="2"/>
                        </svg>
                        <div>
                            <h3 class="text-lg font-bold text-white">Specialist Certification Package</h3>
                            <p class="text-sm text-purple-200 mt-1 max-w-lg">Understanding data governance and the EU AI Act is now vital. Our Specialist Certifications empower your business in these critical areas, helping you remain competitive and compliant.</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs text-purple-300 uppercase tracking-wide mb-2">AVAILABLE FOR PURCHASE</p>
                        <button class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-purple-900 hover:bg-purple-50">Request a Demo</button>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @php
                        $specialist = [
                            ['icon'=>'🔐','title'=>'Data Governance Fundamentals','desc'=>'Explore data privacy, protection measures, quality, and ethical obligations when processing data.'],
                            ['icon'=>'🤖','title'=>'EU AI Act Literacy','desc'=>'Understand AI risk categories and compliance requirements, including integrating AI safely and effectively.'],
                            ['icon'=>'🛡️','title'=>'GDPR & Data Privacy Fundamentals','desc'=>'Learn the fundamentals of GDPR and data privacy to ensure compliance and safeguard sensitive information.'],
                        ];
                    @endphp
                    @foreach($specialist as $s)
                    <div class="rounded-xl p-4 border border-purple-400/30" style="background:rgba(255,255,255,0.07)">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">{{ $s['icon'] }}</span>
                            <h4 class="text-sm font-semibold text-white">{{ $s['title'] }}</h4>
                        </div>
                        <p class="text-xs text-purple-200 leading-relaxed">{{ $s['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center border-t border-purple-400/20 pt-4">
                    <p class="text-sm font-semibold text-white mb-1">More to come</p>
                    <p class="text-xs text-purple-300 mb-2">You will be the first to gain access to our newest additions for this package.</p>
                    <a href="#" class="text-sm text-purple-300 hover:text-white underline">Request certification</a>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
function toggleCert(id) {
    const sub = document.getElementById(id+'-sub');
    const arrow = document.getElementById(id+'-arrow');
    sub.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}
</script>

</body>
</html>