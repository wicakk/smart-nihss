@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Selamat Datang</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
</div>

{{-- Session Status --}}
@if (session('status'))
    <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800
                text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl text-sm">
        {{ session('status') }}
    </div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                text-red-700 dark:text-red-300 px-4 py-3 rounded-xl text-sm">
        <p class="font-semibold mb-1">Login gagal:</p>
        <ul class="list-disc list-inside space-y-0.5 text-xs">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    {{-- Email --}}
    <div>
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email"
               value="{{ old('email') }}"
               class="form-input {{ $errors->has('email') ? 'error' : '' }}"
               placeholder="dokter@rumahsakit.id"
               autocomplete="username" autofocus required>
    </div>

    {{-- Password --}}
    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="form-label" style="margin-bottom:0">Password</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                    Lupa password?
                </a>
            @endif
        </div>
        <div class="relative">
            <input id="password" type="password" name="password"
                   class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                   placeholder="••••••••"
                   autocomplete="current-password" required>
            <button type="button" onclick="togglePw()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Remember me --}}
    <div class="flex items-center gap-2">
        <input type="checkbox" id="remember" name="remember"
               class="w-4 h-4 rounded border-gray-300 accent-indigo-600 cursor-pointer"
               {{ old('remember') ? 'checked' : '' }}>
        <label for="remember" class="text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
            Ingat saya
        </label>
    </div>

    {{-- Submit --}}
    <button type="submit"
            class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700
                   text-white font-semibold text-sm transition-all duration-150
                   shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40
                   hover:-translate-y-0.5 active:translate-y-0 focus:outline-none
                   focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        Masuk ke Sistem
    </button>
</form>

{{-- Demo credentials hint --}}
<div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
    <p class="text-xs text-gray-400 dark:text-gray-500 text-center mb-2">Akun demo:</p>
    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl px-4 py-3 text-xs font-mono text-gray-600 dark:text-gray-400 space-y-1">
        <div class="flex justify-between">
            <span class="text-gray-400">Email</span>
            <span class="font-semibold text-gray-700 dark:text-gray-300">admin@nihss.id</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Password</span>
            <span class="font-semibold text-gray-700 dark:text-gray-300">password</span>
        </div>
    </div>
</div>

<script>
    function togglePw() {
        const inp = document.getElementById('password');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }
</script>

@endsection
