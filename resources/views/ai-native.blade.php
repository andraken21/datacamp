<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Native - DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#0f1729; }
    </style>
</head>
<body class="text-white min-h-screen">
<x-navbar />

{{-- Hero --}}
<div class="min-h-screen flex items-center" style="background:linear-gradient(135deg,#0f1729 0%,#1a1f3a 50%,#0f1729 100%)">
    <div class="max-w-7xl mx-auto px-8 py-20 grid grid-cols-2 gap-16 items-center w-full">
        <div>
            <div class="flex items-center gap-2 mb-6">
                <span class="text-purple-400 text-lg">✦</span>
                <span class="text-sm font-semibold text-purple-400 uppercase tracking-widest">AI Native</span>
            </div>
            <h1 class="text-5xl font-bold leading-tight mb-6">
                Meet your personal<br>
                <span style="color:#03EF62">AI learning engine</span>
            </h1>
            <p class="text-lg text-gray-300 mb-4 leading-relaxed">The interactive, hands-on experience you know, love, and expect from DataCamp—elevated.</p>
            <p class="text-gray-400 mb-8 leading-relaxed">In the new DataCamp AI-native experience, you'll learn faster and smarter with courses built uniquely for you; your pace, your role, your knowledge, and your goals.</p>
            <div class="flex gap-4">
                <a href="{{ route('courses') }}" class="px-6 py-3 rounded-lg font-semibold text-sm" style="background:#03EF62;color:#05192D">Start Learning Free</a>
                <a href="#" class="px-6 py-3 rounded-lg font-semibold text-sm border border-white/20 hover:border-white/40 text-white">See How It Works</a>
            </div>
        </div>

        {{-- Mock chat UI --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden text-gray-800">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                <span class="ml-2 text-xs text-gray-400">Introduction to AI for Work / Generative AI</span>
            </div>
            <div class="p-5 space-y-3 text-sm">
                <p class="text-gray-700">These three factors converging created the perfect conditions for the AI breakthrough we're experiencing today.</p>
                <p class="text-gray-500 text-xs">Any questions about this?</p>
                <div class="bg-gray-50 rounded-lg p-3 mt-4">
                    <p class="text-gray-600 text-xs mb-2">what is a GPU exactly?</p>
                    <div class="flex justify-end">
                        <button class="text-xs px-3 py-1 rounded-lg text-white font-medium" style="background:#3b82f6">Send ↵</button>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400 pt-2">
                    <span class="px-2 py-1 rounded bg-gray-100">No questions</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Explore Section --}}
<div class="py-20 px-8" style="background:#131929">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-white mb-12">Explore AI-native curriculum, built just for you</h2>
        @php
            $ainativeCourses = [
                ['type'=>'TRACK','title'=>'AI Engineering with LangChain','level'=>'Intermediate','duration'=>'36 hr','desc'=>'From prompt engineering to agentic systems—develop the complete skill set to build AI applications that scale, with an AI tutor by your side.'],
                ['type'=>'COURSE','title'=>'Introduction to AI for Work','level'=>'Basic','duration'=>'2 hr - 3 hr','desc'=>'Build your AI foundation with hands-on, AI-native learning that adapts to your pace. Explore how AI works, and learn how to use it effectively and responsibly.'],
                ['type'=>'COURSE','title'=>'Introduction to SQL','level'=>'Basic','duration'=>'30 min - 1 hr','desc'=>'Learn SQL faster with the DataCamp AI-native experience. Practice querying and organizing data in real databases, with lessons that adjust to your pace.'],
                ['type'=>'COURSE','title'=>'LLM Application Fundamentals with LangChain','level'=>'Intermediate','duration'=>'2 hr - 4 hr','desc'=>'Learn to build conversational LLM applications — with reliable structured output, persistent conversation history, and real-time streaming.'],
                ['type'=>'COURSE','title'=>'Prompt Engineering with LangChain','level'=>'Intermediate','duration'=>'1 hr - 3 hr','desc'=>'Learn to write effective prompts and systematically improve them — applying techniques, structural patterns, and optimization strategies.'],
                ['type'=>'COURSE','title'=>'Intermediate SQL','level'=>'Intermediate','duration'=>'4 hr - 6 hr','desc'=>'Accompanied at every step with hands-on practice queries, this course teaches you everything you need to know to analyze data using your own SQL code today!'],
            ];
        @endphp
        <div class="grid grid-cols-3 gap-4">
            @foreach($ainativeCourses as $course)
            <div class="rounded-xl p-5 border border-white/10 hover:border-purple-400/40 transition-colors" style="background:#1a2540">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center gap-1">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    {{ $course['type'] }}
                </p>
                <h3 class="text-base font-bold text-white mb-2">{{ $course['title'] }}</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                        <span class="text-xs text-gray-400">{{ $course['level'] }}</span>
                    </div>
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        {{ $course['duration'] }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed mb-4">{{ $course['desc'] }}</p>
                <div class="flex items-center justify-between">
                    <a href="#" class="text-sm text-gray-400 hover:text-white">See Details →</a>
                    <a href="#" class="px-4 py-1.5 rounded-lg text-xs font-semibold" style="background:#03EF62;color:#05192D">
                        {{ $course['type'] === 'TRACK' ? 'Start Track' : 'Start Course' }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Why Section --}}
<div class="py-20 px-8 text-center" style="background:#0f1729">
    <div class="max-w-4xl mx-auto mb-12">
        <h2 class="text-3xl font-bold text-white mb-4">The future of learning has arrived</h2>
        <p class="text-gray-400 leading-relaxed">Other learning providers have AI assistants layered on top of static videos or exercises. This is learning built with AI as its core. DataCamp is the only platform that offers an AI-native, personal learning engine that feels like a great one-on-one teacher.</p>
    </div>
    <div class="grid grid-cols-4 gap-4 max-w-7xl mx-auto">
        @php
            $features = [
                ['title'=>'One destination, infinite routes','desc'=>'Each lesson is built around the same goals, but no two experiences are alike. Move quickly through what you know, and spend more time mastering what you don\'t.'],
                ['title'=>'Hyper relevant','desc'=>'DataCamp\'s AI learning engine adapts to your skill level and context, bringing examples and exercises that reflect your interests, background, and challenges.'],
                ['title'=>'Always up to date','desc'=>'The world changes fast—your learning should too. With AI-native content, every course stays aligned with today\'s tools, data, and trends.','highlight'=>true],
                ['title'=>'Feels human; powered by AI','desc'=>'No more robotic chatbots and static lessons. DataCamp can engage like a teacher who knows you, so one hour of learning takes you further.'],
            ];
        @endphp
        @foreach($features as $feature)
        <div class="rounded-xl p-5 text-left border {{ isset($feature['highlight']) ? 'border-white/30' : 'border-white/10' }}" style="background:{{ isset($feature['highlight']) ? '#1e2a45' : '#131929' }}">
            <h3 class="font-bold text-white mb-3 text-sm">{{ $feature['title'] }}</h3>
            <p class="text-xs text-gray-400 leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Testimonials --}}
<div class="py-20 px-8" style="background:#0f1729">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-white text-center mb-12">Learners already love it</h2>
        @php
            $testimonials = [
                ['quote'=>'"I found the explanations and examples relevant to my work. The hands-on, interactive experience made concepts stick, and the adaptive pace felt like having a virtual one-to-one mentor."','name'=>'Yi-Wei Ang','role'=>'Chief Product Officer at talabat'],
                ['quote'=>'"I LOVE this format. This is definitely my new preferred learning experience. It\'s super engaging and I feel like I learned great stuff in the course. The examples were customized to fit my background."','name'=>'','role'=>'Senior Analyst at a digital media and tech company'],
                ['quote'=>'"We love how DataCamp\'s new AI-native experience adapts the speed and relevance of every lesson."','name'=>'Fernando Ospina','role'=>'Head of Capability Strategy and Development for Data Insights at Philip Morris International'],
                ['quote'=>'"The DataCamp AI-native experience excels in tailoring content to individual users based on their role and knowledge level."','name'=>'','role'=>'Data and AI owner at a multinational retail company'],
                ['quote'=>'"This is the best training tool I have ever encountered. It consistently produced accurate answers with detailed explanations, which I found very impressive."','name'=>'Kamal Deep Patra','role'=>'Business Solutions Manager at Uniper Energy'],
                ['quote'=>'"It has one of the most thorough and best course materials out there. Also super structured, aided with diagrams and proper flows and code assignments."','name'=>'','role'=>'Software engineer at Careem'],
            ];
        @endphp
        <div class="grid grid-cols-3 gap-4">
            @foreach($testimonials as $t)
            <div class="rounded-xl p-5 border border-white/10" style="background:#131929">
                <p class="text-sm text-gray-300 leading-relaxed mb-4">{{ $t['quote'] }}</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        @if($t['name'])<p class="text-xs font-semibold text-white">{{ $t['name'] }}</p>@endif
                        <p class="text-xs text-gray-500">{{ $t['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>