<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($cert) ? $cert->nama . ' - ' : '' }}Certification - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background:#fff; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
        .cert-sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:rgba(255,255,255,0.7); cursor:pointer; text-decoration:none; transition:background 0.15s; }
        .cert-sidebar-link:hover { background:rgba(255,255,255,0.08); color:white; }
        .cert-sidebar-link.active { background:rgba(3,239,98,0.15); color:#03EF62; font-weight:500; }
        .cert-sidebar-sub { display:flex; align-items:center; padding:7px 16px; border-radius:8px; font-size:13px; color:rgba(255,255,255,0.6); text-decoration:none; }
        .cert-sidebar-sub:hover { background:rgba(255,255,255,0.06); color:white; }
        .cert-sidebar-sub.active { color:#03EF62; font-weight:600; }
        .card { background:white; border:1px solid #e8e8e8; border-radius:12px; }
        .step-accordion { border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:12px; }
        .step-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; background:white; cursor:pointer; }
        .step-header:hover { background:#f9fafb; }
        .step-body { padding:0 20px 20px; }
        .tag { display:inline-block; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:500; }
        .topic-tag { background:#f3f4f6; color:#374151; }
        .level-badge { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:4px; font-size:12px; }
        .faq-item { border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:8px; }
        .faq-header { display:flex; align-items:center; justify-content:space-between; padding:14px 18px; background:white; cursor:pointer; }
        .faq-header:hover { background:#f9fafb; }
        .faq-body { padding:0 18px 14px; display:none; }
        .faq-body.open { display:block; }
        .btn-download {
            display:inline-flex; align-items:center; gap:8px;
            margin-top:16px; padding:8px 16px;
            border:1px solid #d1d5db; border-radius:8px;
            font-size:14px; color:#374151; text-decoration:none;
            transition:background 0.15s, border-color 0.15s;
        }
        .btn-download:hover { background:#f9fafb; border-color:#9ca3af; }
        .btn-download svg { flex-shrink:0; }
        .tech-promo-bar { background: linear-gradient(135deg, #1a1060 0%, #0f0a3c 100%); }
        .microsoft-logo { display:inline-flex; align-items:center; gap:4px; }
    </style>
</head>
<body>
<x-navbar />


@php
$studyGuideMap = [
    'Data Analyst'                    => 'Data+Analyst+Certification+Study+Guide.pdf',
    'Data Scientist'                  => 'Data+Scientist+Certification+Study+Guide.pdf',
    'Data Engineer'                   => 'Data+Engineer+Certification+Study+Guide.pdf',
    'AI Engineer for Data Scientists' => 'aie-ds-associate-certification-study-guide.pdf',
    'AI Engineer for Developers'      => 'AIE+Developer+Associate+Certification+Study+Guide.pdf',
];
$studyGuideFile = isset($cert) ? ($studyGuideMap[$cert->nama_peran] ?? null) : null;
$isTechSection = str_starts_with($section ?? '', 'tech-');

// Tech cert data mapping
$techData = [
    'tech-powerbi'   => ['partner' => 'Microsoft', 'exam_code' => 'PL-300', 'color' => '#F2C811', 'discount' => '50% off Microsoft Exam', 'skills' => ['Prepare and model data using Power Query and DAX','Create and format visualizations and reports','Deploy and maintain assets in Power BI service','Perform data analysis and identify patterns']],
    'tech-tableau'   => ['partner' => 'Tableau',   'exam_code' => 'TCA',    'color' => '#1F77B4', 'discount' => null, 'skills' => ['Connect to and prepare data sources','Create calculated fields and parameters','Build interactive dashboards and stories','Apply analytics and statistical functions']],
    'tech-sql'       => ['partner' => null,         'exam_code' => 'SQL',    'color' => '#336791', 'discount' => null, 'skills' => ['Write complex SQL queries with JOINs and subqueries','Use window functions and CTEs','Optimize query performance','Work with PostgreSQL and database design']],
    'tech-python'    => ['partner' => null,         'exam_code' => 'PDA',    'color' => '#3776AB', 'discount' => null, 'skills' => ['Manipulate data using pandas and NumPy','Create visualizations with matplotlib and seaborn','Apply statistical analysis techniques','Write clean, efficient Python code']],
    'tech-azure'     => ['partner' => 'Microsoft', 'exam_code' => 'AZ-900',  'color' => '#0078D4', 'discount' => '50% off Microsoft Exam', 'skills' => ['Describe cloud computing concepts and Azure services','Understand Azure pricing and support','Describe Azure governance and compliance features','Implement basic Azure solutions']],
    'tech-azure-dev' => ['partner' => 'Microsoft', 'exam_code' => 'AZ-204',  'color' => '#0078D4', 'discount' => '50% off Microsoft Exam', 'skills' => ['Develop Azure compute solutions','Develop for Azure storage','Implement Azure security','Monitor, troubleshoot and optimize Azure solutions']],
    'tech-github'    => ['partner' => 'GitHub',    'exam_code' => 'GHF',     'color' => '#24292E', 'discount' => null, 'skills' => ['Work with Git version control','Collaborate using pull requests and code reviews','Use GitHub Actions for automation','Manage repositories and project workflows']],
    'tech-aws'       => ['partner' => 'AWS',       'exam_code' => 'CLF-C02', 'color' => '#FF9900', 'discount' => null, 'skills' => ['Understand AWS cloud concepts and global infrastructure','Describe AWS security and compliance','Identify core AWS services and use cases','Understand AWS pricing and billing models']],
    'tech-alteryx'   => ['partner' => 'Alteryx',   'exam_code' => 'ADC',     'color' => '#0078C1', 'discount' => null, 'skills' => ['Build and run Alteryx workflows','Prepare and blend data from multiple sources','Perform spatial and predictive analytics','Automate reporting and data processes']],
    'tech-knime'     => ['partner' => 'KNIME',     'exam_code' => 'KNIME',   'color' => '#FFCE01', 'discount' => null, 'skills' => ['Build KNIME Analytics Platform workflows','Perform data wrangling and transformation','Apply machine learning nodes','Create and share reusable components']],
];
$currentTechData = $isTechSection ? ($techData[$section] ?? null) : null;
@endphp


<div class="flex min-h-screen">

{{-- SIDEBAR --}}
<aside class="w-52 shrink-0 pt-4 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto" style="background:#05192D;border-right:1px solid rgba(255,255,255,0.08)">
    <div class="px-2 mb-4">
        <a href="{{ route('dashboard') }}" class="cert-sidebar-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Dashboard
        </a>
    </div>
    <div class="px-4 mb-1">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">CERTIFICATIONS</p>
    </div>
    {{-- Career --}}
    <div class="px-2">
        <button onclick="toggleSub('career')" class="cert-sidebar-link w-full justify-between">
            <div class="flex items-center gap-2">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                Career
            </div>
            <svg id="career-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition:transform 0.2s;{{ in_array($section, ['career-analyst','career-scientist','career-engineer','career-ai-dev','career-ai-ds']) ? 'transform:rotate(180deg)' : '' }}"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div id="career-sub" class="ml-3 {{ in_array($section, ['career-analyst','career-scientist','career-engineer','career-ai-dev','career-ai-ds']) ? '' : 'hidden' }}">
            <a href="{{ route('certification.career.analyst') }}"  class="cert-sidebar-sub {{ $section==='career-analyst'  ? 'active' : '' }}">Data Analyst</a>
            <a href="{{ route('certification.career.scientist') }}" class="cert-sidebar-sub {{ $section==='career-scientist' ? 'active' : '' }}">Data Scientist</a>
            <a href="{{ route('certification.career.engineer') }}"  class="cert-sidebar-sub {{ $section==='career-engineer'  ? 'active' : '' }}">Data Engineer</a>
            <a href="{{ route('certification.career.ai-ds') }}"     class="cert-sidebar-sub {{ $section==='career-ai-ds'      ? 'active' : '' }}">AI Engineer for DS</a>
            <a href="{{ route('certification.career.ai-dev') }}"    class="cert-sidebar-sub {{ $section==='career-ai-dev'     ? 'active' : '' }}">AI Engineer for Devs</a>
        </div>
    </div>
    {{-- Technology --}}
    <div class="px-2 mt-1">
        <button onclick="toggleSub('tech')" class="cert-sidebar-link w-full justify-between">
            <div class="flex items-center gap-2">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                Technology
            </div>
            <svg id="tech-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition:transform 0.2s;{{ str_starts_with($section,'tech-') ? 'transform:rotate(180deg)' : '' }}"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div id="tech-sub" class="ml-3 {{ str_starts_with($section,'tech-') ? '' : 'hidden' }}">
            <a href="{{ route('certification.tech.powerbi') }}"   class="cert-sidebar-sub {{ $section==='tech-powerbi'   ? 'active' : '' }}">Power BI</a>
            <a href="{{ route('certification.tech.tableau') }}"   class="cert-sidebar-sub {{ $section==='tech-tableau'   ? 'active' : '' }}">Tableau</a>
            <a href="{{ route('certification.tech.sql') }}"       class="cert-sidebar-sub {{ $section==='tech-sql'       ? 'active' : '' }}">SQL</a>
            <a href="{{ route('certification.tech.python') }}"    class="cert-sidebar-sub {{ $section==='tech-python'    ? 'active' : '' }}">Python Data</a>
            <a href="{{ route('certification.tech.azure') }}"     class="cert-sidebar-sub {{ $section==='tech-azure'     ? 'active' : '' }}">Microsoft Azure</a>
            <a href="{{ route('certification.tech.azure-dev') }}" class="cert-sidebar-sub {{ $section==='tech-azure-dev' ? 'active' : '' }}">Azure Developer</a>
            <a href="{{ route('certification.tech.github') }}"    class="cert-sidebar-sub {{ $section==='tech-github'    ? 'active' : '' }}">GitHub</a>
            <a href="{{ route('certification.tech.aws') }}"       class="cert-sidebar-sub {{ $section==='tech-aws'       ? 'active' : '' }}">AWS</a>
            <a href="{{ route('certification.tech.alteryx') }}"   class="cert-sidebar-sub {{ $section==='tech-alteryx'   ? 'active' : '' }}">Alteryx</a>
            <a href="{{ route('certification.tech.knime') }}"     class="cert-sidebar-sub {{ $section==='tech-knime'     ? 'active' : '' }}">KNIME</a>
        </div>
    </div>
    {{-- Others --}}
    <div class="px-2 mt-1">
        <a href="{{ route('certification.cpe') }}"     class="cert-sidebar-link {{ $section==='cpe'     ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            CPE
        </a>
        <a href="{{ route('certification.theory') }}"  class="cert-sidebar-link {{ $section==='theory'  ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            Theory
        </a>
        <a href="{{ route('certification.history') }}" class="cert-sidebar-link {{ $section==='history' ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            History
        </a>
    </div>
    <div class="mt-4 pt-3 px-2" style="border-top:1px solid rgba(255,255,255,0.08)">
        <a href="{{ route('certification.faq') }}"      class="cert-sidebar-link {{ $section==='faq'      ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            FAQ
        </a>
        <a href="{{ route('certification.feedback') }}" class="cert-sidebar-link {{ $section==='feedback' ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Give feedback
        </a>
    </div>
</aside>

{{-- MAIN --}}
<main class="flex-1 overflow-y-auto">

@if($section === 'index')
{{-- ==================== INDEX ==================== --}}
<div class="p-10" style="background:#05192D">
    <h1 class="text-3xl font-bold text-white mb-3">Welcome!</h1>
    <p class="text-sm text-gray-300 max-w-2xl leading-relaxed">Ready to validate your skills? Earn certifications by passing structured assessments aligned to real roles. Choose from <strong class="text-white">career, technology,</strong> or <strong class="text-white">fundamentals certifications</strong> designed to validate applied skills.</p>
</div>
<div class="p-8 max-w-5xl">
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Our Certifications</h2>
    <div class="grid grid-cols-2 gap-5 mb-8">
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-2">Career Certifications</h3>
            <p class="text-sm text-gray-500 mb-4">Prove that you can perform in a data role. Available for Data Analyst, Data Scientist, Data Engineer, and AI Engineer.</p>
            <a href="{{ route('certification.career.analyst') }}" class="text-sm font-semibold text-green-600 hover:underline">Explore career certifications →</a>
        </div>
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-2">Technology Certifications</h3>
            <p class="text-sm text-gray-500 mb-4">Validate your skills in a specific technology like Power BI, Tableau, SQL, Python, Azure, AWS, and more.</p>
            <a href="{{ route('certification.tech.powerbi') }}" class="text-sm font-semibold text-green-600 hover:underline">Explore technology certifications →</a>
        </div>
    </div>
</div>

@elseif($isTechSection && isset($cert) && $cert)
{{-- ==================== TECHNOLOGY CERT (DataCamp Style) ==================== --}}
@php $td = $currentTechData; @endphp

{{-- Promo Banner --}}
@if($td && $td['discount'])
<div class="tech-promo-bar px-8 py-3 flex items-center justify-between text-sm">
    <div class="flex items-center gap-3">
        <span class="text-yellow-400">🚀</span>
        <span class="text-white font-medium">{{ $td['discount'] }}</span>
        @if($td['partner'] === 'Microsoft')
        <span class="text-gray-400 text-xs">through DataCamp & Microsoft partnership</span>
        @endif
    </div>
    @if($cert->url)
    <a href="{{ $cert->url }}" target="_blank" class="text-xs text-blue-300 underline opacity-80 hover:opacity-100">Learn more ↗</a>
    @endif
</div>
@endif

<div class="flex gap-0 min-h-screen">
    <div class="flex-1 min-w-0">
        <div class="mx-8 mt-8 rounded-xl p-6 mb-6" style="background:#05192D">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-xl font-bold text-white mb-3">{{ $cert->nama }}</h1>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @if($td && $td['partner'])
                        <span class="px-3 py-1 rounded text-xs font-medium bg-white/10 text-white">{{ $td['partner'] }} Partnership</span>
                        @endif
                        @if($td && $td['discount'])
                        <span class="px-3 py-1 rounded text-xs font-medium text-yellow-300" style="background:rgba(234,179,8,0.2)">⚡ {{ $td['discount'] }}</span>
                        @endif
                    </div>
                    @if($td && $td['discount'] && isset($td['partner']) && $td['partner'] === 'Microsoft')
                    <p class="text-sm text-gray-300">Through DataCamp, you can now earn a 50% discount on <a href="{{ $cert->url ?? '#' }}" target="_blank" class="text-blue-400 underline">Microsoft's {{ $td['exam_code'] }}!</a></p>
                    @endif
                </div>
                @if($td && isset($td['partner']) && $td['partner'] === 'Microsoft')
                <div class="shrink-0 bg-white rounded-lg px-4 py-3 flex items-center gap-2">
                    <div class="grid grid-cols-2 gap-0.5" style="width:20px;height:20px">
                        <div style="background:#F25022;width:9px;height:9px;border-radius:1px"></div>
                        <div style="background:#7FBA00;width:9px;height:9px;border-radius:1px"></div>
                        <div style="background:#00A4EF;width:9px;height:9px;border-radius:1px"></div>
                        <div style="background:#FFB900;width:9px;height:9px;border-radius:1px"></div>
                    </div>
                    <span class="text-sm font-bold text-gray-800">Microsoft</span>
                </div>
                @elseif($td && isset($td['partner']) && $td['partner'])
                <div class="shrink-0 bg-white rounded-lg px-4 py-2">
                    <span class="text-sm font-bold text-gray-800">{{ $td['partner'] }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="mx-8 mb-8">
            @if(isset($faqs) && $faqs->count() > 0)
                @foreach($faqs as $faq)
                <div class="mb-6 pb-6 border-b border-gray-100 last:border-0">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">{{ $faq->pertanyaan }}</h2>
                    <div class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($faq->jawaban)) !!}</div>
                </div>
                @endforeach
            @else
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">What is the {{ $cert->nama }} Certification?</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $cert->deskripsi }}</p>
                </div>
            @endif

            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Frequently Asked Questions</h2>
                    <a href="{{ route('certification.faq') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>
                @foreach([['How long do I have to complete the certification?','After registering, you have 30 days to complete the certification process.'],['How many attempts do I get?','You get two attempts for each exam before your certification process resets.'],['Is the certificate shareable?','Yes, you will receive a shareable certificate that you can add to LinkedIn and your resume.'],['What happens if I fail?','If you fail an exam, you can retake it. After two failed attempts, you will need to re-register.'],['What is the exam format?','The certification consists of a timed theory exam and assessments using real-world data scenarios.']] as $i => $q)
                <div class="faq-item"><div class="faq-header" onclick="toggleFaq('techfaq{{ $i }}')"><span class="text-sm font-medium text-gray-900">{{ $q[0] }}</span><svg id="techfaq{{ $i }}-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="transition:transform 0.2s;flex-shrink:0"><path d="M6 9l6 6 6-6"/></svg></div><div id="techfaq{{ $i }}-body" class="faq-body"><p class="text-sm text-gray-600">{{ $q[1] }}</p></div></div>
                @endforeach
                <div class="mt-4 text-center"><a href="{{ route('certification.faq') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">View all FAQs <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></a></div>
            </div>
        </div>
    </div>

    <div class="w-72 shrink-0 px-4 pt-8">
        <div class="sticky top-20">
            <div class="border border-gray-200 rounded-xl p-5 mb-4 bg-white shadow-sm">
                <p class="text-sm font-bold text-gray-900 mb-2">Interested in Getting Certified?</p>
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                    @if($td && isset($td['partner']) && $td['partner'] === 'Microsoft')
                    Register and complete the {{ $cert->nama }} Career Track to get your {{ $td['discount'] }} discount code.
                    @else
                    Complete the preparation track and register for the official {{ $td['partner'] ?? 'DataCamp' }} certification exam.
                    @endif
                </p>
                <a href="{{ route('tracks.career') }}" class="block w-full py-2.5 rounded-lg text-sm font-semibold text-white text-center mb-3" style="background:#05192D">Start Career Track</a>
                @if($td && isset($td['partner']) && $td['partner'] === 'Microsoft')
                <div class="flex items-center justify-center gap-2 pt-3 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-0.5" style="width:16px;height:16px"><div style="background:#F25022;width:7px;height:7px;border-radius:1px"></div><div style="background:#7FBA00;width:7px;height:7px;border-radius:1px"></div><div style="background:#00A4EF;width:7px;height:7px;border-radius:1px"></div><div style="background:#FFB900;width:7px;height:7px;border-radius:1px"></div></div>
                    <span class="text-xs font-semibold text-gray-700">Microsoft Partnership</span>
                </div>
                @endif
            </div>
            <div class="border border-gray-200 rounded-xl p-4 bg-white shadow-sm space-y-3">
                <div class="flex items-center gap-3"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5"><rect x="2" y="2" width="9" height="9" rx="1"/><rect x="13" y="2" width="9" height="9" rx="1"/><rect x="2" y="13" width="9" height="9" rx="1"/><rect x="13" y="13" width="9" height="9" rx="1"/></svg><div><p class="text-xs text-gray-400">Skill Level</p><p class="text-sm font-semibold text-gray-900">Exam</p></div></div>
                <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg><div><p class="text-xs text-gray-400">Time to Complete</p><p class="text-sm font-semibold text-gray-900">30 days</p></div></div>
                <div class="flex items-center gap-3 pt-3 border-t border-gray-100"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><div><p class="text-xs text-gray-400">Shareable Certificate</p><p class="text-sm font-semibold text-gray-900">Yes</p></div></div>
            </div>
        </div>
    </div>
</div>
@elseif(isset($cert) && $cert)
{{-- ==================== CAREER CERT ==================== --}}

{{-- Promo banner --}}
@if($cert->promo)
<div class="px-8 py-3 flex items-center justify-between text-sm" style="background:#1a1060;color:white">
    <span>🚀 {{ $cert->promo }}</span>
    <a href="{{ $cert->url }}" target="_blank" class="text-xs underline opacity-70">Learn more ↗</a>
</div>
@endif

{{-- Hero --}}
<div class="px-8 py-8 border-b border-gray-100">
    <div class="max-w-5xl flex gap-8">
        <div class="flex-1">
            <p class="text-xs text-gray-400 flex items-center gap-1 mb-2">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="#05192D"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Created by DataCamp
            </p>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $cert->nama }}</h1>
            @if($cert->topik_tercakup)
            <div class="flex items-center gap-2 flex-wrap mb-3">
                <span class="text-sm text-gray-500 font-medium">Topics Covered</span>
                @php
                    $topiks = is_array(json_decode($cert->topik_tercakup)) 
                    ? json_decode($cert->topik_tercakup) 
                    : explode(';', $cert->topik_tercakup);
                @endphp
            @foreach($topiks as $t)
                <span class="tag topic-tag">{{ trim($t) }}</span>
                @endforeach
            </div>
            @endif
            <p class="text-gray-600 text-sm leading-relaxed max-w-xl mb-5">{{ $cert->deskripsi }}</p>
            <div class="flex items-center gap-4 flex-wrap">
                @if($cert->url)
                <a href="{{ $cert->url }}" target="_blank"
                   class="px-5 py-2.5 rounded-lg text-sm font-semibold"
                   style="background:#03EF62;color:#05192D">
                    Register for Certification
                </a>
                @endif
                <span class="text-sm text-gray-400">{{ number_format(rand(10000,25000)) }}+ Certifications awarded</span>
            </div>
        </div>
        <div class="w-72 shrink-0 card p-5">
            <p class="text-sm font-bold text-gray-900 mb-1">🚀 Try the next level up</p>
            <p class="text-xs text-gray-500 mb-4">Already have some knowledge? Take a look at our higher level Certification.</p>
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr>
                        <th class="text-left py-2 text-gray-400 font-normal"></th>
                        <th class="text-center py-2 text-gray-700 font-semibold border-b border-gray-200 px-2">{{ $cert->nama_peran }} Associate</th>
                        <th class="text-center py-2 text-gray-700 font-semibold border-b border-gray-200 px-2">{{ $cert->nama_peran }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500">Level</td>
                        <td class="py-2 text-center"><span class="level-badge bg-blue-50 text-blue-700">Beginner</span></td>
                        <td class="py-2 text-center"><span class="level-badge bg-purple-50 text-purple-700">Intermediate</span></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500">Certificate</td>
                        <td class="py-2 text-center text-green-500">✓</td>
                        <td class="py-2 text-center text-green-500">✓</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-500">Job Ready</td>
                        <td class="py-2 text-center text-gray-300">—</td>
                        <td class="py-2 text-center text-green-500">✓</td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ $cert->url ?? '#' }}" target="_blank" class="mt-4 block w-full py-2 rounded-lg text-xs font-semibold text-white text-center" style="background:#05192D">Switch to {{ $cert->nama_peran }}</a>
            <a href="{{ route('certification.index') }}" class="block text-center text-xs text-blue-600 mt-2 hover:underline">View full breakdown</a>
        </div>
    </div>
</div>

{{-- Skill level / Time / Certificate --}}
<div class="border-b border-gray-100">
    <div class="max-w-5xl px-8 py-5 grid grid-cols-3 divide-x divide-gray-100">
        <div class="flex items-center gap-3 pr-6">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5"><rect x="2" y="2" width="9" height="9" rx="1"/><rect x="13" y="2" width="9" height="9" rx="1"/><rect x="2" y="13" width="9" height="9" rx="1"/><rect x="13" y="13" width="9" height="9" rx="1"/></svg>
            <div>
                <p class="text-xs text-gray-400">Skill Level</p>
                <p class="text-sm font-semibold text-gray-900">{{ $cert->dibuat_oleh ?? 'Created by DataCamp' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-6">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <div>
                <p class="text-xs text-gray-400">Time to Complete</p>
                <p class="text-sm font-semibold text-gray-900">30 days</p>
            </div>
        </div>
        <div class="flex items-center gap-3 pl-6">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <div>
                <p class="text-xs text-gray-400">Shareable Certificate</p>
                <p class="text-sm font-semibold text-gray-900">Yes</p>
            </div>
        </div>
    </div>
</div>

{{-- What you'll gain + Certificate preview --}}
<div class="max-w-5xl px-8 py-8 flex gap-10">
    <div class="flex-1">
        <h2 class="text-xl font-bold text-gray-900 mb-4">What you'll gain</h2>
        @php
        $gains = [
            'Data Analyst'   => ['Use SQL to extract, join, aggregate, validate, and clean data','Describe statistical concepts for hypothesis testing','Report characteristics of data through calculating metrics','Present data concepts to small diverse audiences'],
            'Data Scientist' => ['Build and evaluate supervised and unsupervised ML models','Apply statistical methods to interpret data','Clean and transform raw data for analysis','Communicate findings to technical and non-technical audiences'],
            'Data Engineer'  => ['Design and implement ETL/ELT data pipelines','Work with cloud platforms to store and process data','Model and optimize relational databases','Schedule and monitor workflows using modern tools'],
            'default'        => ['Validate your skills with industry-recognized certification','Stand out in the data job market','Demonstrate practical, applied knowledge','Earn a shareable certificate for LinkedIn'],
        ];
        $gainList = $gains[$cert->nama_peran] ?? $gains['default'];
        @endphp
        <ul class="space-y-2">
            @foreach($gainList as $g)
            <li class="flex items-start gap-2 text-sm text-gray-700">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" class="mt-0.5 shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                {{ $g }}
            </li>
            @endforeach
        </ul>
    </div>
    <div class="w-56 shrink-0">
        <div class="card p-4 text-center">
            <div class="w-full aspect-video bg-gray-900 rounded-lg flex flex-col items-center justify-center mb-3">
                <p class="text-white text-xs font-bold uppercase tracking-wide">{{ $cert->nama_peran }}</p>
                <p class="text-white text-xs opacity-60">ASSOCIATE</p>
                <div class="mt-2 border border-green-400 rounded px-3 py-1">
                    <p class="text-green-400 text-xs font-bold">datacamp</p>
                </div>
            </div>
            <p class="text-xs text-gray-400">Share your certificate on</p>
            <div class="flex items-center justify-center gap-1 mt-1">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                <span class="text-xs font-bold text-blue-700">LinkedIn</span>
            </div>
        </div>
    </div>
</div>

{{-- Our Process Steps --}}
<div class="max-w-5xl px-8 pb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-5">Our process</h2>
    <div class="step-accordion">
        <div class="step-header" onclick="toggleStep('step1')">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background:#05192D">1</span>
                <span class="font-semibold text-gray-900">Prepare with DataCamp</span>
            </div>
            <svg id="step1-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
        </div>
        <div id="step1-body" class="step-body">
            <p class="text-sm text-gray-600 mb-3">We recommend completing the following tracks on DataCamp to prepare for this certification.</p>
            <a href="{{ route('tracks.career') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-800 hover:bg-gray-100">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Associate {{ $cert->nama_peran }} in SQL
            </a>
            <h4 class="text-sm font-semibold text-gray-900 mt-5 mb-2">Skill assessments and readiness</h4>
            <p class="text-sm text-gray-600 mb-3">If you aren't sure whether your skills are at the level required, complete the <a href="{{ route('assessments') }}" class="text-blue-600 underline">readiness quiz</a> and <a href="{{ route('assessments') }}" class="text-blue-600 underline">assessments</a>.</p>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-2 bg-gray-50 text-xs font-bold text-gray-500 uppercase">Skill Assessment</div>
                @php
                $assessments = [
                    'Data Analyst'   => ['Data Management in SQL (PostgreSQL)','Data Analysis in SQL (PostgreSQL)','Exploratory Analysis Theory','Statistical Experimentation Theory'],
                    'Data Scientist' => ['Machine Learning Fundamentals','Statistical Thinking','Python Programming','Feature Engineering'],
                    'Data Engineer'  => ['Data Pipeline Fundamentals','Cloud Infrastructure Basics','SQL for Data Engineering','Workflow Orchestration'],
                    'default'        => ['Skill Assessment 1','Skill Assessment 2','Skill Assessment 3'],
                ];
                $assList = $assessments[$cert->nama_peran] ?? $assessments['default'];
                @endphp
                @foreach($assList as $a)
                <div class="px-4 py-3 border-t border-gray-100">
                    <a href="{{ route('assessments') }}" class="text-sm text-blue-600 hover:underline">{{ $a }}</a>
                </div>
                @endforeach
            </div>
            @if($studyGuideFile)
            <a href="{{ url('study-guides/' . $studyGuideFile) }}" download class="btn-download">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Study Guide
            </a>
            @endif
        </div>
    </div>
    <div class="step-accordion">
        <div class="step-header" onclick="toggleStep('step2')">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background:#05192D">2</span>
                <span class="font-semibold text-gray-900">Register for Certification</span>
            </div>
            <svg id="step2-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
        </div>
        <div id="step2-body" class="step-body hidden">
            <p class="text-sm text-gray-600 mb-4">DataCamp's Associate {{ $cert->nama_peran }} Certification is awarded to individuals who successfully complete <strong>one timed exam</strong> and <strong>one practical exam.</strong> After registering you'll have 30 days to complete your Certification.</p>
            <div class="flex items-center gap-4 flex-wrap">
                @if($cert->url)
                <a href="{{ $cert->url }}" target="_blank" class="px-5 py-2.5 rounded-lg text-sm font-semibold" style="background:#03EF62;color:#05192D">Register for Certification</a>
                @endif
                <span class="text-sm text-gray-500">Join the {{ number_format(rand(10000,20000)) }} people who have been DataCamp Certified</span>
            </div>
        </div>
    </div>
    <div class="step-accordion">
        <div class="step-header" onclick="toggleStep('step3')">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background:#05192D">3</span>
                <span class="font-semibold text-gray-900">Timed Exam</span>
            </div>
            <svg id="step3-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
        </div>
        <div id="step3-body" class="step-body hidden">
            <p class="text-sm text-gray-600 mb-4">Once you start the exam, you'll have <strong>two hours</strong> and <strong>two attempts</strong> to complete each exam before your certification process resets.</p>
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg mb-4">
                <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center text-white text-xs font-bold shrink-0">DA101</div>
                <div>
                    <p class="text-sm text-gray-700 mb-2">The timed exam assesses your proficiency in <strong>data management, exploratory analysis</strong>, and <strong>statistical experimentation</strong>.</p>
                    @php $timedSkills = ['Data Analyst' => ['Perform data extraction, joining and aggregation tasks','Perform cleaning tasks to prepare data for analysis','Assess data quality and perform validation tasks','Calculate metrics to report characteristics of data','Read and analyze data visualizations'],'default' => ['Demonstrate proficiency in core technical skills','Apply analytical methods to real scenarios','Show problem-solving capabilities']]; @endphp
                    <ul class="space-y-1">
                        @foreach(($timedSkills[$cert->nama_peran] ?? $timedSkills['default']) as $s)
                        <li class="text-xs text-gray-600 flex items-start gap-1.5"><span class="text-gray-400 mt-0.5">•</span>{{ $s }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="step-accordion">
        <div class="step-header" onclick="toggleStep('step4')">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full text-white text-xs font-bold flex items-center justify-center" style="background:#05192D">4</span>
                <span class="font-semibold text-gray-900">Practical Exam</span>
            </div>
            <svg id="step4-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>
        </div>
        <div id="step4-body" class="step-body hidden">
            <p class="text-sm text-gray-600 mb-4">This is the final step. You'll have <strong>two attempts</strong> to complete the exam.</p>
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg mb-4">
                <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center text-white text-xs font-bold shrink-0">DA601P</div>
                <div>
                    <p class="text-sm text-gray-700 mb-2">The practical exam assesses your skills in <strong>data management</strong> and <strong>exploratory analysis</strong>.</p>
                    @php $practicalSkills = ['Data Analyst' => ['Perform standard data extraction, joining and aggregation','Assess data quality and perform validation tasks','Perform standard cleaning tasks to prepare data','Calculate metrics to report characteristics of data'],'default' => ['Complete a real-world data project','Demonstrate end-to-end data skills','Present findings and insights']]; @endphp
                    <ul class="space-y-1">
                        @foreach(($practicalSkills[$cert->nama_peran] ?? $practicalSkills['default']) as $s)
                        <li class="text-xs text-gray-600 flex items-start gap-1.5"><span class="text-gray-400 mt-0.5">•</span>{{ $s }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-2 bg-gray-50 text-xs font-bold text-gray-500">Sample practical exams</div>
                <div class="px-4 py-3 flex items-center justify-between border-t border-gray-100">
                    <span class="text-sm text-gray-700">{{ $cert->nama_peran }} Associate Sample Practical Exam</span>
                    <a href="{{ $cert->url ?? '#' }}" target="_blank" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Open Project</a>
                </div>
                <div class="px-4 py-3 flex items-center justify-between border-t border-gray-100">
                    <span class="text-sm text-gray-700">{{ $cert->nama_peran }} Associate Guided Sample Exam</span>
                    <a href="{{ $cert->url ?? '#' }}" target="_blank" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">Open Project</a>
                </div>
                <div class="px-4 py-3 flex items-center justify-between border-t border-gray-100">
                    <span class="text-sm text-gray-700">Common Exam Mistakes</span>
                    <a href="{{ route('certification.faq') }}" class="text-xs px-3 py-1.5 rounded border border-gray-300 hover:bg-gray-50">View</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Why get certified --}}
<div class="max-w-5xl px-8 pb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Why get certified with DataCamp</h2>
    <div class="grid grid-cols-3 gap-6 text-center">
        <div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">💼</div>
            <p class="font-semibold text-gray-900 text-sm mb-1">Industry-Recognized</p>
            <p class="text-xs text-gray-500">Our certifications are built in partnership with industry leaders</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">🏆</div>
            <p class="font-semibold text-gray-900 text-sm mb-1">Differentiate Yourself</p>
            <p class="text-xs text-gray-500">Stand out in an increasingly competitive job market</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">🚀</div>
            <p class="font-semibold text-gray-900 text-sm mb-1">Data and AI Experts</p>
            <p class="text-xs text-gray-500">Master critical skills designed by experts in data and AI education</p>
        </div>
    </div>
</div>

{{-- Certification levels table --}}
<div class="max-w-5xl px-8 pb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Certification levels</h2>
    <div class="card overflow-hidden">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr style="background:#05192D">
                    <th class="text-left p-4 text-gray-400 font-normal w-32"></th>
                    <th class="p-4 text-center text-white font-semibold border-l border-white/10">{{ $cert->nama_peran }} Associate</th>
                    <th class="p-4 text-center text-white font-semibold border-l border-white/10">{{ $cert->nama_peran }}</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-100">
                    <td class="p-4 text-gray-500 font-medium">Level</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center gap-1 text-blue-700 text-xs font-medium">📊 Basic</span></td>
                    <td class="p-4 text-center border-l border-gray-100"><span class="inline-flex items-center gap-1 text-purple-700 text-xs font-medium">📊 Intermediate</span></td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="p-4 text-gray-500 font-medium">Structure</td>
                    <td class="p-4 text-center text-xs text-gray-600">1 timed exam<br>1 practical exam</td>
                    <td class="p-4 text-center text-xs text-gray-600 border-l border-gray-100">2 timed exams<br>1 practical exam<br>1 recorded presentation</td>
                </tr>
                <tr>
                    <td class="p-4 text-gray-500 font-medium">Technology</td>
                    <td class="p-4 text-center font-bold text-gray-900">SQL</td>
                    <td class="p-4 text-center font-bold text-gray-900 border-l border-gray-100">SQL + Python/R</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Testimonials --}}
<div class="max-w-5xl px-8 pb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-5">Testimonials</h2>
    <div class="grid grid-cols-2 gap-5">
        <div class="card p-6">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-purple-200 flex items-center justify-center font-bold text-purple-700 shrink-0">A</div>
                <div><p class="text-xs font-semibold text-gray-900">Anna Konovalova</p><p class="text-xs text-gray-400">Certified Member</p></div>
            </div>
            <p class="text-sm text-gray-600 italic">"I accepted an offer for my dream Data Analyst job and am officially starting next week!! DataCamp Certification, along with their projects, were key factors in helping me find success"</p>
        </div>
        <div class="card p-6">
            <div class="flex items-start gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center font-bold text-blue-700 shrink-0">N</div>
                <div><p class="text-xs font-semibold text-gray-900">Nicolas Foss</p><p class="text-xs text-gray-400">Data Analyst</p></div>
            </div>
            <p class="text-sm text-gray-600 italic">"I got a great job working as an Epidemiologist and have DataCamp to thank. Working on my R skills and the Certification process helped make me a more attractive candidate."</p>
        </div>
    </div>
</div>

{{-- FAQ inline --}}
<div class="max-w-5xl px-8 pb-12">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-900">Frequently Asked Questions</h2>
        <a href="{{ route('certification.faq') }}" class="text-sm font-semibold text-blue-600 hover:underline">View all</a>
    </div>
    @php
    $inlineFaqs = [
        ['q' => 'How long do I have to complete the certification?','a' => 'After registering, you have 30 days to complete all exams in the certification process.'],
        ['q' => 'How many attempts do I get?','a' => 'You get two attempts for each exam before your certification process resets.'],
        ['q' => 'Is the certificate shareable?','a' => 'Yes, you will receive a shareable certificate that you can add to LinkedIn and your resume.'],
        ['q' => 'What happens if I fail?','a' => 'If you fail an exam, you can retake it. After two failed attempts, you\'ll need to re-register.'],
        ['q' => 'What is the exam format?','a' => 'The certification consists of a timed theory exam and a practical exam using real-world data scenarios.'],
    ];
    @endphp
    @foreach($inlineFaqs as $i => $faq)
    <div class="faq-item">
        <div class="faq-header" onclick="toggleFaq('faq{{ $i }}')">
            <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
            <svg id="faq{{ $i }}-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="transition:transform 0.2s;flex-shrink:0"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div id="faq{{ $i }}-body" class="faq-body">
            <p class="text-sm text-gray-600">{{ $faq['a'] }}</p>
        </div>
    </div>
    @endforeach
    <div class="mt-4 text-center">
        <a href="{{ route('certification.faq') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
            View all FAQs
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    </div>
</div>

{{-- ===== TAMBAHKAN INI SETELAH PENUTUP </div> TESTIMONIALS DI CAREER CERT ===== --}}

{{-- FAQ inline --}}
<div class="max-w-5xl px-8 pb-12">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-900">Frequently Asked Questions</h2>
        <a href="{{ route('certification.faq') }}" class="text-sm font-semibold text-blue-600 hover:underline">View all</a>
    </div>
    @php
    $inlineFaqs = [
        ['q' => 'How long do I have to complete the certification?','a' => 'After registering, you have 30 days to complete all exams in the certification process.'],
        ['q' => 'How many attempts do I get?','a' => 'You get two attempts for each exam before your certification process resets.'],
        ['q' => 'Is the certificate shareable?','a' => 'Yes, you will receive a shareable certificate that you can add to LinkedIn and your resume.'],
        ['q' => 'What happens if I fail?','a' => 'If you fail an exam, you can retake it. After two failed attempts, you\'ll need to re-register.'],
        ['q' => 'What is the exam format?','a' => 'The certification consists of a timed theory exam and a practical exam using real-world data scenarios.'],
    ];
    @endphp
    @foreach($inlineFaqs as $i => $faq)
    <div class="faq-item">
        <div class="faq-header" onclick="toggleFaq('cfaq{{ $i }}')">
            <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
            <svg id="cfaq{{ $i }}-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="transition:transform 0.2s;flex-shrink:0"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div id="cfaq{{ $i }}-body" class="faq-body">
            <p class="text-sm text-gray-600">{{ $faq['a'] }}</p>
        </div>
    </div>
    @endforeach
    <div class="mt-4 text-center">
        <a href="{{ route('certification.faq') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
            View all FAQs
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    </div>
</div>

@elseif($section === 'cpe')
{{-- ==================== CPE ==================== --}}
<div class="p-8" style="background:#05192D">
    <h1 class="text-2xl font-bold text-white mb-2">CPE Credits</h1>
    <p class="text-sm text-gray-300">Earn recognized credits for your professional development and continuing education.</p>
</div>
<div class="p-8 max-w-5xl">
    <div class="rounded-xl p-6 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4" style="background:#05192D">
        <div class="text-white max-w-2xl">
            <p class="text-sm leading-relaxed mb-3">DataCamp is registered with the National Association of State Boards of Accountancy (NASBA) as a sponsor of continued professional education on the National Registry of CPE. Its NASBA-accredited CPE courses for data and finance professionals offer an excellent way to stay up-to-date with industry standards. <a href="#" class="text-green-400 underline ml-1">What is CPE?</a></p>
            <div class="flex items-start gap-3 text-xs text-gray-300 bg-white bg-opacity-10 rounded-lg px-4 py-3">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>You can earn CPE credits after completing the course and scoring 70% or higher in the corresponding assessment. Assessments must be taken within 1 year of starting the course.</span>
            </div>
            <div class="flex gap-8 mt-4 text-xs">
                <div><p class="text-gray-400">Field of study</p><p class="font-semibold text-white mt-0.5">Information Technology - Technical</p></div>
                <div><p class="text-gray-400">Instructional delivery method</p><p class="font-semibold text-white mt-0.5">QAS Self-Study</p></div>
            </div>
        </div>
        <div class="shrink-0"><div class="bg-white rounded-lg px-4 py-3 text-center" style="min-width:110px"><div class="text-xs font-bold text-gray-700 leading-tight">NATIONAL REGISTRY OF</div><div class="text-3xl font-black my-1" style="color:#1a3a6b">CPE</div><div class="text-xs font-bold text-gray-700">SPONSORS</div></div></div>
    </div>
    <div class="flex border-b border-gray-200 mb-6">
        <button onclick="switchCpeTab('assessments')" id="tab-assessments" class="px-4 py-2.5 text-sm font-semibold border-b-2 border-green-500 text-green-600 mr-4">CPE ASSESSMENTS</button>
        <button onclick="switchCpeTab('history')" id="tab-history" class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700">CREDIT HISTORY</button>
    </div>
    <div id="cpe-assessments">
        @php $filterTags = ['AI Agent Fundamentals','AI Business Fundamentals','AI Fundamentals','Alteryx Fundamentals','Associate AI Engineer for Data Scientists','Associate AI Engineer for Developers','Associate Data Analyst','Associate Data Engineer','Associate Data Scientist','Associate Python Developer','AWS Cloud Practitioner (CLF-C02)','Building APIs in Python','ChatGPT Fundamentals','Containerization and Virtualization','Data Analyst','Data Engineer','Data Literacy Professional','Excel Fundamentals','Finance Fundamentals','General','Google Sheets Fundamentals','Python Developer','Understanding Data Topics']; @endphp
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('certification.cpe') }}" class="px-3 py-1 rounded-full text-xs font-medium border transition {{ ($filter ?? 'all') === 'all' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">All</a>
            @foreach($filterTags as $tag)
            <a href="{{ route('certification.cpe') }}?filter={{ urlencode($tag) }}" class="px-3 py-1 rounded-full text-xs font-medium border transition {{ ($filter ?? 'all') === $tag ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">{{ $tag }}</a>
            @endforeach
        </div>
        @if(isset($cpeData) && $cpeData->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($cpeData as $c)
            @php
                $track = strtolower($c->bagian_dari_track ?? '');
                $iconMap = ['python'=>['bg'=>'#3776AB','text'=>'Py'],'sql'=>['bg'=>'#336791','text'=>'SQL'],'excel'=>['bg'=>'#217346','text'=>'XL'],'power bi'=>['bg'=>'#F2C811','text'=>'PBI'],'power-bi'=>['bg'=>'#F2C811','text'=>'PBI'],'chatgpt'=>['bg'=>'#10a37f','text'=>'AI'],'docker'=>['bg'=>'#2496ED','text'=>'Do'],'aws'=>['bg'=>'#FF9900','text'=>'AWS'],'tableau'=>['bg'=>'#E97627','text'=>'Tab'],'git'=>['bg'=>'#F05032','text'=>'Git'],'pytorch'=>['bg'=>'#EE4C2C','text'=>'PT'],'r'=>['bg'=>'#276DC3','text'=>'R']];
                $icon = ['bg'=>'#05192D','text'=>'DC'];
                foreach($iconMap as $key => $val) { if(str_contains($track, $key)) { $icon = $val; break; } }
            @endphp
            <div class="border border-gray-200 rounded-xl p-5 bg-white hover:shadow-md transition-shadow flex flex-col">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:{{ $icon['bg'] }}">{{ $icon['text'] }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $c->nama_course }}</p>
                        <p class="text-xs font-bold mt-1" style="color:#03EF62">{{ number_format((float)$c->cpe_credits, 1) }} CPE CREDITS</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-xs text-gray-400 mb-4">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    45 mins
                </div>
                <div class="flex gap-2 mt-auto">
                    <a href="{{ $c->slug ? route('course.detail', $c->slug) : '#' }}" class="flex-1 text-center text-xs font-medium py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">Start Course</a>
                    <button class="flex-1 text-xs font-semibold py-2 rounded-lg text-white transition hover:opacity-90" style="background:#05192D">Start Exam</button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-gray-400"><p class="text-sm">No CPE courses found for this filter.</p></div>
        @endif
    </div>
    <div id="cpe-history" class="hidden">
        <div class="border border-gray-200 rounded-xl p-10 text-center bg-white">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mx-auto mb-4"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">No credit history yet</h4>
            <p class="text-xs text-gray-400">Complete a CPE course and pass the assessment to earn credits.</p>
        </div>
    </div>
</div>
<script>
function switchCpeTab(tab) {
    document.getElementById('cpe-assessments').classList.toggle('hidden', tab !== 'assessments');
    document.getElementById('cpe-history').classList.toggle('hidden', tab !== 'history');
    document.getElementById('tab-assessments').className = 'px-4 py-2.5 text-sm font-semibold border-b-2 mr-4 ' + (tab === 'assessments' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700');
    document.getElementById('tab-history').className = 'px-4 py-2.5 text-sm font-semibold border-b-2 ' + (tab === 'history' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700');
}
</script>

@elseif($section === 'theory')
{{-- ==================== THEORY ==================== --}}
<div class="px-8 py-6" style="background:#05192D">
    <div class="flex items-center justify-between max-w-5xl">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">Theory Certifications</h1>
            <p class="text-sm text-gray-300">Theory Certifications assess foundational knowledge through standardized, timed exams designed to measure conceptual understanding.</p>
        </div>
        <div class="w-16 h-16 rounded-full flex items-center justify-center shrink-0" style="background:#f59e0b">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8zm-1-5h2v2h-2zm0-8h2v6h-2z"/></svg>
        </div>
    </div>
</div>
<div class="p-8 max-w-5xl">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Fundamentals</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @foreach($theoryCerts as $cert)
        <div class="border border-gray-200 rounded-xl p-6 bg-white hover:shadow-md transition-shadow flex flex-col">
            <div class="w-12 h-12 rounded-full mb-4 flex items-center justify-center" style="background: {{ $cert->slug === 'data-literacy' ? 'linear-gradient(135deg,#f97316,#ec4899)' : 'linear-gradient(135deg,#8b5cf6,#06b6d4)' }}">
                @if($cert->slug === 'data-literacy')
                <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.1h-15V5h15v14.1zm0-16.1h-15C3.12 3 2 4.12 2 5.5v13C2 19.88 3.12 21 4.5 21h15c1.38 0 2.5-1.12 2.5-2.5v-13C22 4.12 20.88 3 19.5 3z"/></svg>
                @else
                <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 17.93V18a1 1 0 0 0-2 0v1.93A8 8 0 0 1 4.07 13H6a1 1 0 0 0 0-2H4.07A8 8 0 0 1 11 4.07V6a1 1 0 0 0 2 0V4.07A8 8 0 0 1 19.93 11H18a1 1 0 0 0 0 2h1.93A8 8 0 0 1 13 19.93z"/></svg>
                @endif
            </div>
            <h3 class="font-bold text-gray-900 mb-2">{{ $cert->nama }}</h3>
            <p class="text-sm text-gray-500 mb-4 flex-1 leading-relaxed">{{ $cert->deskripsi }}</p>
            <div class="flex items-center gap-1 text-xs text-gray-400 mb-4">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ $cert->panduan }}
            </div>
            @if($cert->url)
            <a href="{{ $cert->url }}" target="_blank" class="text-sm font-medium mb-3 block" style="color:#05192D">Prepare for certification</a>
            @endif
            <button class="w-full py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90" style="background:#05192D">Start Exam</button>
        </div>
        @endforeach
        <div class="border border-gray-200 rounded-xl p-6 bg-white flex flex-col items-center justify-center text-center">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" class="mb-3"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            <h3 class="font-bold text-gray-700 mb-1">More to come</h3>
            <p class="text-xs text-gray-400">Keep watching this space, we are constantly working on new certifications</p>
        </div>
    </div>
    <h2 class="text-lg font-bold text-gray-900 mb-4">Achieved Certifications</h2>
    <div class="border-2 border-dashed border-gray-200 rounded-xl p-10 text-center">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" class="mx-auto mb-3"><circle cx="12" cy="8" r="4"/><path d="M8 14l-4 8h16l-4-8"/></svg>
        <h4 class="text-sm font-semibold text-gray-700 mb-1">You have no achieved Certifications yet</h4>
    </div>
</div>

@elseif($section === 'history')
<div class="p-8" style="background:#05192D">
    <h1 class="text-2xl font-bold text-white mb-2">Certification History</h1>
    <p class="text-sm text-gray-300">View your past certification attempts and earned credentials.</p>
</div>
<div class="p-8 max-w-4xl">
    <div class="card p-10 text-center">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mx-auto mb-4"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        <h4 class="text-sm font-semibold text-gray-700 mb-1">No certification history yet</h4>
        <p class="text-xs text-gray-400 mb-4">Once you attempt a certification exam, your history will appear here.</p>
        <a href="{{ route('certification.career.analyst') }}" class="text-sm font-medium px-4 py-2 rounded-lg text-white inline-block" style="background:#05192D">Start a Certification</a>
    </div>
</div>

@elseif($section === 'faq')
<div class="p-8" style="background:#05192D">
    <h1 class="text-2xl font-bold text-white mb-2">Frequently Asked Questions</h1>
    <p class="text-sm text-gray-300">Everything you need to know about DataCamp certifications.</p>
</div>
<div class="p-8 max-w-4xl">
    @if(isset($faqs) && $faqs->count() > 0)
        @foreach($faqs->groupBy('seksi') as $seksi => $items)
        <h3 class="font-bold text-gray-900 mb-3 mt-6 first:mt-0">{{ $seksi }}</h3>
        @foreach($items as $i => $f)
        <div class="faq-item">
            <div class="faq-header" onclick="toggleFaq('dbfaq{{ $f->id }}')">
                <span class="text-sm font-medium text-gray-900">{{ $f->pertanyaan }}</span>
                <svg id="dbfaq{{ $f->id }}-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="transition:transform 0.2s;flex-shrink:0"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div id="dbfaq{{ $f->id }}-body" class="faq-body">
                <p class="text-sm text-gray-600">{{ $f->jawaban }}</p>
            </div>
        </div>
        @endforeach
        @endforeach
    @else
    <div class="space-y-2">
        @foreach([['How long do I have to complete the certification?','After registering, you have 30 days to complete all exams in the certification process.'],['How many attempts do I get?','You get two attempts for each exam before your certification process resets.'],['Is the certificate shareable?','Yes, you will receive a shareable certificate that you can add to LinkedIn and your resume.'],['What happens if I fail?','If you fail an exam, you can retake it. After two failed attempts, you\'ll need to re-register.'],['What is the exam format?','The certification consists of a timed theory exam and a practical exam using real-world data scenarios.'],['Are certifications free?','Certifications require a DataCamp Premium subscription. Some free courses may help you prepare.'],['How do I prepare for the certification?','We recommend completing the relevant career or skill tracks on DataCamp before attempting the certification.'],['Can I retake if I pass?','Once you pass a certification, you can choose to retake it at any time to renew your credential.']] as $i => $q)
        <div class="faq-item">
            <div class="faq-header" onclick="toggleFaq('pfaq{{ $i }}')">
                <span class="text-sm font-medium text-gray-900">{{ $q[0] }}</span>
                <svg id="pfaq{{ $i }}-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="transition:transform 0.2s;flex-shrink:0"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div id="pfaq{{ $i }}-body" class="faq-body">
                <p class="text-sm text-gray-600">{{ $q[1] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@elseif($section === 'feedback')
<div class="p-8" style="background:#05192D">
    <h1 class="text-2xl font-bold text-white mb-2">Give Feedback</h1>
    <p class="text-sm text-gray-300">Help us improve the certification experience.</p>
</div>
<div class="p-8 max-w-2xl">
    @if(session('feedback_success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">✓ Thank you for your feedback!</div>
    @endif
    <div class="card p-6">
        <form method="POST" action="{{ route('feedback.submit') }}">
            @csrf
            <input type="hidden" name="halaman" value="certification">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Feedback type</label>
                <select name="tipe" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="bug">Bug report</option>
                    <option value="feature">Feature request</option>
                    <option value="content">Content feedback</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Your feedback</label>
                <textarea name="isi_feedback" rows="5" required placeholder="Tell us what you think..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 resize-none"></textarea>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white" style="background:#05192D">Submit Feedback</button>
        </form>
    </div>
</div>

@else
<div class="p-8 text-center text-gray-400">Select a section from the sidebar.</div>
@endif

</main>
</div>

<script>
function toggleSub(id) {
    const el = document.getElementById(id+'-sub');
    const ch = document.getElementById(id+'-chevron');
    el.classList.toggle('hidden');
    ch.style.transform = el.classList.contains('hidden') ? '' : 'rotate(180deg)';
}
function toggleStep(id) {
    const body = document.getElementById(id+'-body');
    const icon = document.getElementById(id+'-icon');
    body.classList.toggle('hidden');
    icon.style.transform = body.classList.contains('hidden') ? '' : 'rotate(180deg)';
}
function toggleFaq(id) {
    const body = document.getElementById(id+'-body');
    const icon = document.getElementById(id+'-icon');
    body.classList.toggle('open');
    if(icon) icon.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : '';
}
window.addEventListener('DOMContentLoaded', function() {
    const s1 = document.getElementById('step1-body');
    const i1 = document.getElementById('step1-icon');
    if(s1) s1.classList.remove('hidden');
    if(i1) i1.style.transform = 'rotate(180deg)';
});
</script>
</body>
</html>