@extends('layouts.app')
@section('title','Formulir NIHSS')
@section('page-title','Penilaian NIHSS')
@section('breadcrumb', $patient->name . ' · ' . $patient->medical_record_number)

@section('content')
<div class="flex gap-5 items-start" x-data="nihssForm()">

{{-- ── Questions Column ──────────────────────────────────────────── --}}
<form id="nihssForm" method="POST" action="{{ route('patients.assessments.store', $patient) }}"
      class="flex-1 min-w-0 space-y-5 pb-24 lg:pb-0">
    @csrf

    {{-- Meta info --}}
    <div class="card p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Informasi Pemeriksaan</h3>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Pasien</label>
                <div class="form-input bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 cursor-default">
                    {{ $patient->name }} ({{ $patient->medical_record_number }})
                </div>
            </div>
            <div>
                <label class="form-label">Waktu Pemeriksaan <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="assessed_at"
                       value="{{ now()->format('Y-m-d\TH:i') }}"
                       class="form-input {{ $errors->has('assessed_at') ? 'error' : '' }}" required>
                @error('assessed_at')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Catatan Klinis</label>
                <textarea name="clinical_notes" rows="2" class="form-input resize-none"
                          placeholder="Catatan tambahan (opsional)"></textarea>
            </div>
        </div>
    </div>

    {{-- Dynamic sections --}}
    @foreach($sections as $section)
    <div class="card overflow-hidden">
        <div class="section-header">
            <h3 class="font-semibold text-indigo-800 dark:text-indigo-300 text-sm">{{ $section->name }}</h3>
            @if($section->description)
                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5 leading-relaxed">{{ $section->description }}</p>
            @endif
        </div>
        <div class="p-5 space-y-6">
            @foreach($section->questions as $question)
            <div>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                    {{ $question->question_text }}
                    @if($question->is_required)<span class="text-red-400 ml-0.5">*</span>@endif
                </p>
                @if($question->instruction)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 leading-relaxed bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/30 rounded-lg px-3 py-2">
                        <span class="font-semibold text-amber-700 dark:text-amber-400">Instruksi: </span>{{ $question->instruction }}
                    </p>
                @endif
                <div class="space-y-2">
                    @foreach($question->options as $opt)
                    <label class="option-card"
                           :class="{ 'selected': answers['{{ $question->id }}'] === '{{ $opt->id }}' }"
                           @click="select({{ $question->id }}, {{ $opt->id }}, {{ $opt->score }})">
                        <input type="radio"
                               name="answers[{{ $question->id }}]"
                               value="{{ $opt->id }}"
                               :checked="answers['{{ $question->id }}'] === '{{ $opt->id }}'"
                               class="mt-0.5 accent-indigo-600 flex-shrink-0 w-4 h-4">
                        <span class="flex-1 text-sm text-gray-700 dark:text-gray-300 leading-snug">{{ $opt->option_text }}</span>
                        <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $opt->score === 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : ($opt->score <= 2 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300') }}">
                            {{ $opt->score }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error("answers.{$question->id}")
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Submit --}}
    <div class="card p-5 flex items-center justify-between">
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary">← Kembali</a>
        <button type="submit" class="btn btn-success px-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Penilaian
        </button>
    </div>
</form>

{{-- ── Sticky Score Panel (Desktop) ─────────────────────────────── --}}
<div class="hidden lg:block w-64 flex-shrink-0 sticky top-[72px]">
    <div class="card p-5 space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Skor Real-time</h3>

        {{-- Score ring --}}
        <div class="flex justify-center">
            <div class="relative w-24 h-24">
                <svg class="w-24 h-24 -rotate-90" viewBox="0 0 96 96">
                    <circle cx="48" cy="48" r="40" fill="none" stroke="#f3f4f6" stroke-width="8" class="dark:stroke-gray-700"/>
                    <circle cx="48" cy="48" r="40" fill="none"
                            :stroke="svColor" stroke-width="8" stroke-linecap="round"
                            :stroke-dasharray="`${Math.round(total/42*251)} 251`"
                            style="transition:stroke-dasharray .3s,stroke .3s"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none" x-text="total"></span>
                    <span class="text-xs text-gray-400">/ 42</span>
                </div>
            </div>
        </div>

        {{-- Severity label --}}
        <div class="text-center">
            <span class="badge text-sm px-3 py-0.5"
                  :class="`badge-${svKey}`" x-text="svLabel"></span>
        </div>

        {{-- Progress bar --}}
        <div class="space-y-1">
            <div class="flex justify-between text-xs text-gray-400">
                <span>0</span><span>21</span><span>42</span>
            </div>
            <div class="h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-300"
                     :style="`width:${Math.min(total/42*100,100)}%;background:${svColor}`"></div>
            </div>
        </div>

        <div class="text-center text-xs text-gray-400">
            <span class="font-semibold text-gray-700 dark:text-gray-200" x-text="answered"></span>
            / {{ $sections->sum(fn($s) => $s->questions->count()) }} dijawab
        </div>

        {{-- Score breakdown --}}
        <div class="border-t border-gray-100 dark:border-gray-700 pt-3 max-h-56 overflow-y-auto space-y-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">Rincian</p>
            @foreach($sections as $sec)
                @foreach($sec->questions as $q)
                <div class="flex justify-between items-center text-xs"
                     x-show="answers['{{ $q->id }}'] !== undefined">
                    <span class="text-gray-500 dark:text-gray-400 truncate max-w-[150px]"
                          title="{{ $q->question_text }}">
                        <span class="font-mono font-semibold text-indigo-500 dark:text-indigo-400">{{ $sec->code }}</span>
                        · {{ Str::limit($q->question_text, 18) }}
                    </span>
                    <span class="font-bold text-gray-700 dark:text-gray-300 ml-1" x-text="scores['{{ $q->id }}'] ?? 0"></span>
                </div>
                @endforeach
            @endforeach
            <p x-show="answered === 0" class="text-xs text-gray-400 text-center py-2">Pilih jawaban…</p>
        </div>

        {{-- Guide --}}
        <div class="border-t border-gray-100 dark:border-gray-700 pt-3 space-y-1.5">
            @foreach([['0','Normal','#10b981'],['1–4','Ringan','#3b82f6'],['5–15','Sedang','#f59e0b'],['16–20','Berat','#ef4444'],['21–42','Sangat Berat','#7c3aed']] as [$r,$l,$c])
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{$c}}"></span>
                    <span class="text-gray-500 dark:text-gray-400">{{$l}}</span>
                </div>
                <span class="font-mono text-gray-400">{{$r}}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Mobile floating bar ──────────────────────────────────────── --}}
<div class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-white/95 dark:bg-gray-900/95 backdrop-blur
            border-t border-gray-200 dark:border-gray-700 px-4 py-3 flex items-center gap-3">
    <div class="flex-1">
        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide">Total Skor</p>
        <p class="text-2xl font-black text-gray-900 dark:text-white leading-none" x-text="total + ' / 42'"></p>
    </div>
    <span class="badge text-sm" :class="`badge-${svKey}`" x-text="svLabel"></span>
    <div class="w-20 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all" :style="`width:${Math.min(total/42*100,100)}%;background:${svColor}`"></div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
function nihssForm() {
    return {
        answers: {},
        scores:  {},
        total:   0,
        answered: 0,
        svLabel: 'Normal',
        svKey:   'normal',
        svColor: '#10b981',

        select(qId, optId, score) {
            this.answers[qId] = String(optId);
            this.scores[qId]  = score;
            this.recalc();
        },

        recalc() {
            let t = 0, c = 0;
            for (const k in this.answers) {
                if (this.answers[k] !== undefined) { t += (this.scores[k] || 0); c++; }
            }
            this.total    = t;
            this.answered = c;
            if      (t === 0)  { this.svLabel='Normal';       this.svKey='normal';      this.svColor='#10b981'; }
            else if (t <= 4)   { this.svLabel='Ringan';       this.svKey='mild';        this.svColor='#3b82f6'; }
            else if (t <= 15)  { this.svLabel='Sedang';       this.svKey='moderate';    this.svColor='#f59e0b'; }
            else if (t <= 20)  { this.svLabel='Berat';        this.svKey='severe';      this.svColor='#ef4444'; }
            else               { this.svLabel='Sangat Berat'; this.svKey='very-severe'; this.svColor='#7c3aed'; }
        }
    }
}
</script>
@endpush
