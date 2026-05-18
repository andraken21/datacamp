<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DataCamp Tutorials')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:8px 16px; border-radius:8px; font-size:14px; color:#444; cursor:pointer; text-decoration:none; }
        .sidebar-link:hover { background:#f0f0f0; }
        .sidebar-link.active { background:#e8f5e9; color:#1a7a3a; font-weight:500; }
        .sidebar-link svg { width:18px; height:18px; opacity:0.6; }
        .filter-btn { padding:6px 14px; border-radius:999px; font-size:13px; border:1px solid #e0e0e0; background:white; cursor:pointer; color:#444; text-decoration:none; display:inline-block; }
        .filter-btn.active { background:#05192D; color:white; border-color:#05192D; }
        .filter-btn:hover:not(.active) { background:#f0f0f0; }
    </style>
</head>
<body class="min-h-screen">

@if(session('success'))
<div class="fixed top-16 left-1/2 -translate-x-1/2 z-50 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3">
    ✅ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="fixed top-16 left-1/2 -translate-x-1/2 z-50 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg px-4 py-3">
    ❌ {{ session('error') }}
</div>
@endif

<x-navbar />

<div class="flex min-h-screen">
    <x-sidebar />
    <main class="flex-1">
        @yield('content')
    </main>
</div>

</body>
</html>