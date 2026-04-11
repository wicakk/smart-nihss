@extends('layouts.app')
@section('title','Hasil NIHSS')
@section('page-title','Hasil Penilaian NIHSS')
@section('breadcrumb', $assessment->patient->name . ' · ' . $assessment->assessed_at->format('d M Y, H:i'))

@section('content')
<div class="space-y-5">

{{-- ── Result Header ─────────────────────────────────────────────── --}}
@php
$svBorder = ['normal'=>'border-emerald-400','mild'=>'border-blue-400','moderate'=>'border-amber-400','severe'=>'border-red-400','very_severe'=>'border-purple-500'];
$svBg     = ['normal'=>'text-emerald-500','mild'=>'text-blue-500','moderate'=>'text-amber-500','severe'=>'text-red-500','very_severe'=>'text-purple-500'];
$sv = $assessment->severity;
@endphp
<div class="card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-5">

        {{-- SVG score ring --}}
        <div class="flex justify-center flex-shrink-0">
            <div class="relative w-28 h-28">
                <svg class="w-28 h-28 -rotate-90" viewBox="0 0 112 112">
                    <circle cx="56" cy="56" r="48" fill="none" stroke="#f3f4f6" stroke-width="8" class="dark:stroke-gray-700"/>
                    <circle cx="56" cy="56" r="48" fill="none"
                            stroke="{{ $assessment->severity_color }}" stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="{{ round($assessment->total_score / 42 * 301) }} 301"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-black text-gray-900 dark:text-white leading-none">{{ $assessment->total_score }}</span>
                    <span class="text-xs text-gray-400">/ 42</span>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="badge badge-{{ $sv==='very_severe'?'very-severe':$sv }} text-sm px-3 py-0.5">{{ $assessment->severity_label }}</span>
                @if($assessment->is_complete)
                    <span class="badge" style="background:#d1fae5;color:#065f46">✓ Selesai</span>
                @endif
            </div>
            <div class="grid sm:grid-cols-2 gap-x-6 gap-y-1.5 text-sm">
                <div class="flex gap-2"><span class="text-gray-400 w-20 flex-shrink-0">Pasien</span><span class="font-semibold text-gray-800 dark:text-gray-200">{{ $assessment->patient->name }}</span></div>
                <div class="flex gap-2"><span class="text-gray-400 w-20 flex-shrink-0">No. RM</span><span class="font-mono text-gray-600 dark:text-gray-300">{{ $assessment->patient->medical_record_number }}</span></div>
                <div class="flex gap-2"><span class="text-gray-400 w-20 flex-shrink-0">Tanggal</span><span class="text-gray-700 dark:text-gray-300">{{ $assessment->assessed_at->format('d M Y, H:i') }}</span></div>
                @if($assessment->assessor)<div class="flex gap-2"><span class="text-gray-400 w-20 flex-shrink-0">Pemeriksa</span><span class="text-gray-700 dark:text-gray-300">{{ $assessment->assessor->name }}</span></div>@endif
                @if($assessment->clinical_notes)<div class="flex gap-2 sm:col-span-2"><span class="text-gray-400 w-20 flex-shrink-0">Catatan</span><span class="text-gray-700 dark:text-gray-300">{{ $assessment->clinical_notes }}</span></div>@endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('patients.show', $assessment->patient) }}" class="btn btn-secondary">← Pasien</a>
            <a href="{{ route('patients.assessments.create', $assessment->patient) }}" class="btn btn-primary">Periksa Lagi</a>
        </div>
    </div>

    {{-- Score scale --}}
    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
        <div class="flex rounded-full overflow-hidden h-3 mb-1">
            @foreach([[1/42,'#10b981'],[4/42,'#3b82f6'],[11/42,'#f59e0b'],[5/42,'#ef4444'],[22/42,'#7c3aed']] as [$flex,$color])
            <div class="h-full" style="flex:{{ $flex }};background:{{ $color }}"></div>
            @endforeach
        </div>
        <div class="relative">
            <div class="flex justify-between text-[10px] text-gray-400">
                <span>0</span><span>4</span><span>15</span><span>20</span><span>42</span>
            </div>
            <div class="absolute top-0 w-3 h-3 rounded-full border-2 border-white dark:border-gray-800 shadow -translate-x-1/2 -translate-y-0.5"
                 style="left:{{ min($assessment->total_score/42*100,100) }}%;background:{{ $assessment->severity_color }}"></div>
        </div>
    </div>
</div>

{{-- ── Answers breakdown ──────────────────────────────────────────── --}}
@foreach($sections as $section)
    @php $sAnswers = $assessment->answers->filter(fn($a)=>$a->question->section_id===$section->id); @endphp
    @if($sAnswers->isEmpty()) @continue @endif
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 px-5 py-3 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $section->name }}</h3>
            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">+{{ $sAnswers->sum('score') }} poin</span>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
            @foreach($section->questions as $question)
                @php $ans = $assessment->answers->firstWhere('question_id',$question->id); @endphp
                <div class="px-5 py-4">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ $question->question_text }}</p>
                    <div class="space-y-2">
                        @foreach($question->options as $opt)
                            @php $sel = $ans && $ans->option_id === $opt->id; @endphp
                            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl
                                {{ $sel ? 'bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700' : '' }}">
                                <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                            {{ $sel ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300 dark:border-gray-600' }}">
                                    @if($sel)<div class="w-1.5 h-1.5 rounded-full bg-white"></div>@endif
                                </div>
                                <span class="flex-1 text-sm {{ $sel ? 'font-semibold text-indigo-800 dark:text-indigo-200' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $opt->option_text }}
                                </span>
                                <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                    {{ $sel ? ($opt->score===0?'bg-emerald-500 text-white':($opt->score<=2?'bg-amber-500 text-white':'bg-red-500 text-white')) : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }}">
                                    {{ $opt->score }}
                                </span>
                            </div>
                        @endforeach
                        @if(!$ans)
                            <p class="text-xs text-amber-600 dark:text-amber-400 italic px-3">Tidak dijawab</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

{{-- Delete --}}
<div class="flex justify-end">
    <form method="POST" action="{{ route('assessments.destroy', $assessment) }}"
          onsubmit="return confirm('Hapus pemeriksaan ini secara permanen?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus Pemeriksaan
        </button>
    </form>
</div>

</div>
@endsection
