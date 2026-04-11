<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NIHSS') – Stroke Assessment</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50:'#eef2ff', 100:'#e0e7ff', 200:'#c7d2fe', 300:'#a5b4fc',
                            400:'#818cf8', 500:'#6366f1', 600:'#4f46e5',
                            700:'#4338ca', 800:'#3730a3', 900:'#312e81',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Dark mode init BEFORE render to avoid flash --}}
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak]{ display:none !important; }

        /* Scrollbar */
        ::-webkit-scrollbar{width:5px;height:5px}
        ::-webkit-scrollbar-track{background:transparent}
        ::-webkit-scrollbar-thumb{background:#c7d2fe;border-radius:99px}
        .dark ::-webkit-scrollbar-thumb{background:#4338ca}

        /* Badges */
        .badge{display:inline-flex;align-items:center;padding:2px 10px;border-radius:99px;font-size:11px;font-weight:600;line-height:1.8;white-space:nowrap}
        .badge-normal      {background:#d1fae5;color:#065f46}
        .badge-mild        {background:#dbeafe;color:#1e40af}
        .badge-moderate    {background:#fef3c7;color:#92400e}
        .badge-severe      {background:#fee2e2;color:#991b1b}
        .badge-very-severe {background:#ede9fe;color:#5b21b6}
        .dark .badge-normal      {background:rgba(16,185,129,.2);color:#6ee7b7}
        .dark .badge-mild        {background:rgba(59,130,246,.2);color:#93c5fd}
        .dark .badge-moderate    {background:rgba(245,158,11,.2);color:#fcd34d}
        .dark .badge-severe      {background:rgba(239,68,68,.2);color:#fca5a5}
        .dark .badge-very-severe {background:rgba(139,92,246,.2);color:#c4b5fd}

        /* Card */
        .card{background:#fff;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.07);border:1px solid #f3f4f6}
        .dark .card{background:#1f2937;border-color:#374151}

        /* Form inputs */
        .form-input{
            width:100%;border-radius:10px;border:1.5px solid #e5e7eb;
            background:#fff;color:#111827;padding:9px 14px;font-size:14px;
            transition:border-color .15s,box-shadow .15s;outline:none;font-family:inherit;
        }
        .form-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.15)}
        .form-input::placeholder{color:#9ca3af}
        .dark .form-input{background:#374151;border-color:#4b5563;color:#f9fafb}
        .dark .form-input:focus{border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,.2)}
        .form-input.error{border-color:#f87171}
        select.form-input{cursor:pointer}

        .form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
        .dark .form-label{color:#d1d5db}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;border:none;font-family:inherit;text-decoration:none;white-space:nowrap;line-height:1}
        .btn-primary{background:#4f46e5;color:#fff}
        .btn-primary:hover{background:#4338ca;box-shadow:0 4px 12px rgba(79,70,229,.35);transform:translateY(-1px)}
        .btn-secondary{background:#f3f4f6;color:#374151;border:1px solid #e5e7eb}
        .btn-secondary:hover{background:#e5e7eb}
        .dark .btn-secondary{background:#374151;color:#d1d5db;border-color:#4b5563}
        .dark .btn-secondary:hover{background:#4b5563}
        .btn-success{background:#059669;color:#fff}
        .btn-success:hover{background:#047857;box-shadow:0 4px 12px rgba(5,150,105,.3);transform:translateY(-1px)}
        .btn-danger{background:#fef2f2;color:#dc2626;border:1px solid #fecaca}
        .btn-danger:hover{background:#fee2e2}
        .dark .btn-danger{background:rgba(220,38,38,.15);color:#f87171;border-color:rgba(220,38,38,.3)}

        /* Nav links */
        .nav-link{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:10px;font-size:13.5px;font-weight:500;color:#6b7280;text-decoration:none;transition:all .15s}
        .nav-link:hover{background:#f9fafb;color:#111827}
        .nav-link.active{background:#eef2ff;color:#4f46e5;font-weight:600}
        .dark .nav-link{color:#9ca3af}
        .dark .nav-link:hover{background:#374151;color:#f3f4f6}
        .dark .nav-link.active{background:rgba(99,102,241,.15);color:#a5b4fc}

        /* Option cards */
        .option-card{display:flex;align-items:flex-start;gap:10px;padding:11px 14px;border-radius:12px;border:1.5px solid #e5e7eb;cursor:pointer;transition:all .15s;background:#fff}
        .option-card:hover{border-color:#a5b4fc;background:#fafafe}
        .option-card.selected{border-color:#4f46e5;background:#eef2ff}
        .dark .option-card{background:#1f2937;border-color:#374151}
        .dark .option-card:hover{border-color:#6366f1;background:#2d3748}
        .dark .option-card.selected{border-color:#818cf8;background:rgba(99,102,241,.1)}

        /* Section header in form */
        .section-header{background:linear-gradient(135deg,#eef2ff,#e0e7ff);padding:14px 20px;border-bottom:1px solid #c7d2fe}
        .dark .section-header{background:linear-gradient(135deg,rgba(79,70,229,.12),rgba(99,102,241,.06));border-color:rgba(99,102,241,.25)}

        /* Table */
        .tbl th{padding:10px 16px;text-align:left;font-size:11px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#6b7280;background:#f9fafb;border-bottom:1px solid #f3f4f6}
        .dark .tbl th{background:#111827;color:#6b7280;border-color:#374151}
        .tbl td{padding:13px 16px;color:#374151;vertical-align:middle;font-size:13.5px}
        .dark .tbl td{color:#d1d5db}
        .tbl tr{border-bottom:1px solid #f9fafb;transition:background .1s}
        .tbl tr:hover{background:#fafafa}
        .dark .tbl tr{border-color:#1f2937}
        .dark .tbl tr:hover{background:rgba(55,65,81,.4)}
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 h-full">

<div class="flex h-screen overflow-hidden" x-data="{open:false}">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-60 flex flex-col
                  bg-white dark:bg-gray-950 border-r border-gray-100 dark:border-gray-800
                  transform transition-transform duration-300 lg:static lg:translate-x-0 lg:shadow-none shadow-xl">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-800">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shadow flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 dark:text-white text-sm leading-tight">NIHSS</p>
                <p class="text-[11px] text-gray-400">Stroke Assessment</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('patients.index') }}"
               class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Data Pasien
            </a>

            <div class="pt-3 pb-1.5 px-3">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-600">Administrasi</p>
            </div>

            <a href="{{ route('admin.form-builder.index') }}"
               class="nav-link {{ request()->routeIs('admin.form-builder.*') ? 'active' : '' }}">
                <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Form Builder
            </a>
        </nav>

        {{-- User --}}
        <div class="p-3 border-t border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2.5 px-2 py-2 rounded-xl bg-gray-50 dark:bg-gray-800 mb-1.5">
                <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400
                               hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar dari Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div x-show="open" x-cloak @click="open=false"
         x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden">
    </div>

    {{-- ══════════════ MAIN ══════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 h-[52px] flex items-center gap-3 px-4 lg:px-6
                       bg-white/95 dark:bg-gray-900/95 backdrop-blur
                       border-b border-gray-100 dark:border-gray-800">

            <button @click="open=!open"
                    class="lg:hidden p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex-1 min-w-0">
                <h1 class="text-sm font-bold text-gray-800 dark:text-gray-100 truncate">@yield('page-title','Dashboard')</h1>
                @hasSection('breadcrumb')
                    <p class="text-[11px] text-gray-400 hidden sm:block truncate">@yield('breadcrumb')</p>
                @endif
            </div>

            {{-- Dark mode toggle (plain JS, no Alpine needed) --}}
            <button onclick="toggleDark()"
                    class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 text-gray-600 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="w-4 h-4 text-amber-400 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
        </header>

        {{-- Flash --}}
        @if(session('success'))
        <div id="flash" class="mx-4 lg:mx-6 mt-4 flex items-center gap-3
                               bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800
                               text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash').remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
        </div>
        <script>setTimeout(()=>{const el=document.getElementById('flash');if(el)el.remove()},5000)</script>
        @endif

        @if($errors->any())
        <div class="mx-4 lg:mx-6 mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                    text-red-800 dark:text-red-300 px-4 py-3 rounded-xl text-sm">
            <p class="font-semibold mb-1">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleDark(){
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', isDark);
    }
</script>

@stack('scripts')
</body>
</html>
