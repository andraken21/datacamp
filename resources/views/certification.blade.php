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

            <div>
                <button onclick="toggleCert('career')" class="cert-sidebar-link w-full justify-between">
                    <div class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        Career
                    </div>
                    <svg id="career-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="{{ in_array($section, ['career-analyst','career-scientist','career-engineer']) ? 'rotate-180' : '' }}"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div id="career-sub" class="ml-4 mt-1 space-y-1 {{ in_array($section, ['career-analyst','career-scientist','career-engineer']) ? '' : 'hidden' }}">
                    <a href="{{ route('certification.career.analyst') }}" class="cert-sidebar-link text-xs {{ $section === 'career-analyst' ? 'active' : '' }}">Data Analyst</a>
                    <a href="{{ route('certification.career.scientist') }}" class="cert-sidebar-link text-xs {{ $section === 'career-scientist' ? 'active' : '' }}">Data Scientist</a>
                    <a href="{{ route('certification.career.engineer') }}" class="cert-sidebar-link text-xs {{ $section === 'career-engineer' ? 'active' : '' }}">Data Engineer</a>
                </div>
            </div>

            <div>
                <button onclick="toggleCert('tech')" class="cert-sidebar-link w-full justify-between">
                    <div class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83"/></svg>
                        Technology
                    </div>
                    <svg id="tech-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="{{ in_array($section, ['tech-powerbi','tech-tableau','tech-sql']) ? 'rotate-180' : '' }}"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div id="tech-sub" class="ml-4 mt-1 space-y-1 {{ in_array($section, ['tech-powerbi','tech-tableau','tech-sql']) ? '' : 'hidden' }}">
                    <a href="{{ route('certification.tech.powerbi') }}" class="cert-sidebar-link text-xs {{ $section === 'tech-powerbi' ? 'active' : '' }}">Power BI</a>
                    <a href="{{ route('certification.tech.tableau') }}" class="cert-sidebar-link text-xs {{ $section === 'tech-tableau' ? 'active' : '' }}">Tableau</a>
                    <a href="{{ route('certification.tech.sql') }}" class="cert-sidebar-link text-xs {{ $section === 'tech-sql' ? 'active' : '' }}">SQL</a>
                </div>
            </div>

            <a href="{{ route('certification.cpe') }}" class="cert-sidebar-link {{ $section === 'cpe' ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                CPE
            </a>
            <a href="{{ route('certification.theory') }}" class="cert-sidebar-link {{ $section === 'theory' ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Theory
            </a>
            <a href="{{ route('certification.history') }}" class="cert-sidebar-link {{ $section === 'history' ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                History
            </a>
        </div>

        <div class="mt-4 pt-4 px-3" style="border-top:1px solid rgba(255,255,255,0.08)">
            <a href="/faq" class="cert-sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                FAQ
            </a>
            <a href="/feedback" class="cert-sidebar-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Give feedback
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 overflow-y-auto">

        {{-- Hero --}}
        <div class="p-8 flex items-center justify-between" style="background:#05192D">
            <div class="max-w-xl">
                @if($section === 'index')
                    <h1 class="text-2xl font-bold text-white mb-3">Welcome!</h1>
                    <p class="text-sm text-gray-300 leading-relaxed mb-2">Ready to validate your skills? Earn certifications by passing structured assessments aligned to real roles. Choose from <strong class="text-white">career, technology,</strong> or <strong class="text-white">fundamentals certifications</strong> designed to validate applied skills.</p>
                    <p class="text-sm text-gray-400">Not ready to sit the exam yet? Follow a guided learning plan to prepare.</p>
                @elseif($section === 'career-analyst')
                    <h1 class="text-2xl font-bold text-white mb-3">Data Analyst Certification</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">A data analyst sits between business intelligence and data science. Prove you have what it takes to perform in this role.</p>
                @elseif($section === 'career-scientist')
                    <h1 class="text-2xl font-bold text-white mb-3">Data Scientist Certification</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">A data scientist collects, analyzes and interprets large amounts of data. Prove your expertise with this certification.</p>
                @elseif($section === 'career-engineer')
                    <h1 class="text-2xl font-bold text-white mb-3">Data Engineer Certification</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">A data engineer collects, stores, and pre-processes data for easy access within an organization. Validate your skills here.</p>
                @elseif($section === 'tech-powerbi')
                    <h1 class="text-2xl font-bold text-white mb-3">Power BI Certification</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">Prove your ability to prepare, model, visualize and analyze data using Microsoft Power BI.</p>
                @elseif($section === 'tech-tableau')
                    <h1 class="text-2xl font-bold text-white mb-3">Tableau Certification</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">Demonstrate your skills in connecting data, analyzing data, creating charts and publishing content in Tableau.</p>
                @elseif($section === 'tech-sql')
                    <h1 class="text-2xl font-bold text-white mb-3">SQL Certification</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">The SQL Associate certification measures entry-level skills in data management and exploratory analysis using SQL.</p>
                @elseif($section === 'cpe')
                    <h1 class="text-2xl font-bold text-white mb-3">CPE Credits</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">Earn recognized credits for your professional development and continuing education.</p>
                @elseif($section === 'theory')
                    <h1 class="text-2xl font-bold text-white mb-3">Theory Exam</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">Test your theoretical knowledge across data science concepts, statistics, and machine learning fundamentals.</p>
                @elseif($section === 'history')
                    <h1 class="text-2xl font-bold text-white mb-3">Certification History</h1>
                    <p class="text-sm text-gray-300 leading-relaxed">View your past certification attempts and earned credentials.</p>
                @endif
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

            {{-- INDEX: tampilkan semua overview --}}
            @if($section === 'index')
                <div class="card p-6 mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-2">Why DataCamp certifications?</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-2">DataCamp certifications are designed to reflect the skills employers hire for. Built using role-based competency frameworks and developed with industry input, each certification is earned through a structured, statistically rigorous assessment process that evaluates applied skills in realistic scenarios.</p>
                    <p class="text-sm font-semibold text-gray-800">Prove your skills in real-world scenarios, not just in controlled practice.</p>
                </div>
                <h2 class="text-xl font-bold text-gray-900 text-center mb-6">Our Certifications</h2>
                <div class="grid grid-cols-4 gap-4 mb-6">
                    @foreach([
                        ['icon'=>'⚡','color'=>'#06b6d4','bg'=>'#0e7490','title'=>'Technology Certification','desc'=>'Validate your skills in a technology'],
                        ['icon'=>'💼','color'=>'#22c55e','bg'=>'#15803d','title'=>'Career Certification','desc'=>'Prove that you can perform in a role'],
                        ['icon'=>'🧠','color'=>'#f59e0b','bg'=>'#b45309','title'=>'Fundamentals Certification','desc'=>'Showcase knowledge of key concepts'],
                        ['icon'=>'🎓','color'=>'#a855f7','bg'=>'#7e22ce','title'=>'CPE Credits','desc'=>'Earn recognized credits for your professional development'],
                    ] as $ct)
                    <div class="rounded-full aspect-square flex flex-col items-center justify-center text-center p-4 cursor-pointer hover:scale-105 transition-transform" style="background:{{ $ct['bg'] }}">
                        <span class="text-2xl mb-2">{{ $ct['icon'] }}</span>
                        <p class="text-xs font-bold leading-tight mb-1" style="color:{{ $ct['color'] }}">{{ $ct['title'] }}</p>
                        <p class="text-xs text-white/70 leading-tight">{{ $ct['desc'] }}</p>
                    </div>
                    @endforeach
                </div>

            {{-- CAREER ANALYST --}}
            @elseif($section === 'career-analyst')
                <div class="card p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-2">Data Analyst</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">A data analyst sits between business intelligence and data science. They provide vital information to business stakeholders by gathering data, analyzing it, and presenting insights in a clear manner.</p>
                    <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Get Started</button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['title'=>'Timed Exam','desc'=>'90-minute timed assessment covering data analysis skills and techniques.','icon'=>'⏱️'],
                        ['title'=>'Case Study','desc'=>'Real-world scenario where you analyze a dataset and present findings.','icon'=>'📊'],
                        ['title'=>'SQL Skills','desc'=>'Demonstrate your ability to query and manipulate data using SQL.','icon'=>'🗄️'],
                        ['title'=>'Python/R Skills','desc'=>'Show proficiency in data manipulation and visualization libraries.','icon'=>'🐍'],
                    ] as $item)
                    <div class="card p-5 flex gap-4">
                        <span class="text-2xl shrink-0">{{ $item['icon'] }}</span>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $item['title'] }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

            {{-- CAREER SCIENTIST --}}
            @elseif($section === 'career-scientist')
                <div class="card p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-2">Data Scientist</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">A data scientist is responsible for collecting, analyzing and interpreting large amounts of data to identify ways to help an organization improve operations and gain competitive advantage.</p>
                    <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Get Started</button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['title'=>'Machine Learning','desc'=>'Build and evaluate predictive models using supervised and unsupervised learning.','icon'=>'🤖'],
                        ['title'=>'Statistical Analysis','desc'=>'Apply statistical methods to interpret data and validate hypotheses.','icon'=>'📐'],
                        ['title'=>'Data Wrangling','desc'=>'Clean, transform and prepare raw data for analysis.','icon'=>'🔧'],
                        ['title'=>'Communication','desc'=>'Present findings clearly to technical and non-technical audiences.','icon'=>'📢'],
                    ] as $item)
                    <div class="card p-5 flex gap-4">
                        <span class="text-2xl shrink-0">{{ $item['icon'] }}</span>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $item['title'] }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

            {{-- CAREER ENGINEER --}}
            @elseif($section === 'career-engineer')
                <div class="card p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-2">Data Engineer</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">A data engineer collects, stores, and pre-processes data for easy access and use within an organization. They build and maintain the infrastructure that allows data to flow reliably.</p>
                    <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Get Started</button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['title'=>'Data Pipelines','desc'=>'Design and implement ETL/ELT pipelines for data ingestion and transformation.','icon'=>'⚙️'],
                        ['title'=>'Cloud Platforms','desc'=>'Work with AWS, Azure, or GCP to store and process large datasets.','icon'=>'☁️'],
                        ['title'=>'Database Design','desc'=>'Model and optimize relational and non-relational databases.','icon'=>'🗄️'],
                        ['title'=>'Orchestration','desc'=>'Schedule and monitor workflows using tools like Airflow or dbt.','icon'=>'🔄'],
                    ] as $item)
                    <div class="card p-5 flex gap-4">
                        <span class="text-2xl shrink-0">{{ $item['icon'] }}</span>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $item['title'] }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

            {{-- TECH POWERBI --}}
            @elseif($section === 'tech-powerbi')
                <div class="card p-6 mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-white text-xl">📊</div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Exam PL-300: Microsoft Power BI Data Analyst</h2>
                            <div class="flex gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded border border-gray-200 text-gray-500">ℹ️ Partner Certification</span>
                                <span class="text-xs px-2 py-0.5 rounded text-yellow-700" style="background:#fef3c7">⚡ 50% off Microsoft Exam</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">This exam measures your ability to accomplish the following technical tasks in Power BI: prepare the data; model the data; visualize and analyze the data; and deploy and maintain assets.</p>
                    <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Get Started</button>
                </div>

            {{-- TECH TABLEAU --}}
            @elseif($section === 'tech-tableau')
                <div class="card p-6 mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-white text-xl">📈</div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Tableau Certified Data Analyst</h2>
                            <div class="flex gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded border border-gray-200 text-gray-500">ℹ️ Prepare with DataCamp</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">This exam measures your ability to accomplish technical tasks in Tableau, mapped to the four exam domains: connecting data, analyzing data, creating charts and publishing content.</p>
                    <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Get Started</button>
                </div>

            {{-- TECH SQL --}}
            @elseif($section === 'tech-sql')
                <div class="card p-6 mb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-white text-sm font-bold">SQL</div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">SQL Associate Certification</h2>
                            <div class="flex gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded border border-gray-200 text-gray-500">ℹ️ Created by DataCamp</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">The SQL Associate certification measures entry-level skills in data management and exploratory analysis using SQL. It covers querying, filtering, aggregating, and joining data across multiple tables.</p>
                    <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Get Started</button>
                </div>

            {{-- CPE --}}
            @elseif($section === 'cpe')
                <div class="card p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-2xl shrink-0">🎓</div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 mb-2">Continuing Professional Education (CPE) Credits</h2>
                            <p class="text-sm text-gray-600 leading-relaxed mb-4">DataCamp certifications qualify for CPE credits recognized by major professional organizations. Build your credentials while advancing your data skills.</p>
                            <button class="text-sm font-medium px-6 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Learn More</button>
                        </div>
                    </div>
                </div>

            {{-- THEORY --}}
            @elseif($section === 'theory')
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['title'=>'Statistics & Probability','desc'=>'Covers descriptive statistics, probability distributions, hypothesis testing, and statistical inference.','icon'=>'📐'],
                        ['title'=>'Machine Learning Theory','desc'=>'Covers supervised and unsupervised learning algorithms, model evaluation, and feature engineering.','icon'=>'🤖'],
                        ['title'=>'Data Engineering Concepts','desc'=>'Covers data pipelines, ETL processes, data warehousing, and cloud infrastructure fundamentals.','icon'=>'⚙️'],
                        ['title'=>'Python & SQL Fundamentals','desc'=>'Covers core Python programming constructs and SQL querying for data analysis tasks.','icon'=>'💻'],
                    ] as $t)
                    <div class="card p-5 flex gap-4">
                        <span class="text-2xl shrink-0">{{ $t['icon'] }}</span>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $t['title'] }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed mb-3">{{ $t['desc'] }}</p>
                            <button class="text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700">Start Practice</button>
                        </div>
                    </div>
                    @endforeach
                </div>

            {{-- HISTORY --}}
            @elseif($section === 'history')
                <div class="card p-8 text-center">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mx-auto mb-4"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">No certification history yet</h4>
                    <p class="text-xs text-gray-400">Once you attempt a certification exam, your history will appear here.</p>
                    <button class="mt-4 text-sm font-medium px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">Start a Certification</button>
                </div>
            @endif

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