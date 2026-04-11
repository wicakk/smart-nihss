<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') – NIHSS Stroke Assessment</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',
                            400:'#818cf8',500:'#6366f1',600:'#4f46e5',
                            700:'#4338ca',800:'#3730a3',900:'#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .form-input {
            width: 100%; border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: #fff; color: #111827;
            padding: 11px 14px; font-size: 14px;
            transition: border-color .15s, box-shadow .15s;
            outline: none; font-family: inherit;
        }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .form-input::placeholder { color: #9ca3af; }
        .dark .form-input { background: #374151; border-color: #4b5563; color: #f9fafb; }
        .dark .form-input:focus { border-color: #818cf8; }
        .form-input.error { border-color: #f87171; }
        .form-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
        .dark .form-label { color: #d1d5db; }
    </style>
</head>

<body class="font-sans antialiased h-full bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 dark:from-gray-950 dark:via-indigo-950 dark:to-gray-950">

    {{-- Background decoration --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/3"></div>

        {{-- Grid pattern --}}
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-sm">

            {{-- Logo / Brand --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl
                            bg-gradient-to-br from-indigo-500 to-indigo-700
                            shadow-xl shadow-indigo-500/30 mb-5">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">NIHSS</h1>
                <p class="text-sm text-indigo-300/80 mt-1">Stroke Assessment System</p>
            </div>

            {{-- Card --}}
            <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur rounded-2xl shadow-2xl shadow-black/30 p-7 border border-white/10">
                @yield('content')
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-white/30 mt-6">
                © {{ date('Y') }} NIHSS Stroke Assessment · Hak Cipta Dilindungi
            </p>
        </div>
    </div>

</body>
</html>
