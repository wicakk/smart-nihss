@extends('layouts.app')
@section('title','Form Builder')
@section('page-title','Form Builder NIHSS')
@section('breadcrumb','Kelola pertanyaan dan opsi formulir secara dinamis')

@section('content')
<div x-data="{modal:null}" class="space-y-5">

{{-- Toolbar --}}
<div class="card px-5 py-4 flex items-center justify-between gap-3">
    <div class="text-sm text-gray-600 dark:text-gray-400">
        <strong class="text-gray-800 dark:text-gray-200">{{ $sections->count() }}</strong> seksi ·
        <strong class="text-gray-800 dark:text-gray-200">{{ $sections->sum(fn($s)=>$s->allQuestions->count()) }}</strong> pertanyaan
    </div>
    <button @click="modal='new-section'" class="btn btn-primary">+ Tambah Seksi</button>
</div>

{{-- ═══ NEW SECTION MODAL ═══ --}}
<div x-show="modal==='new-section'" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
     @click.self="modal=null">
    <div class="card p-6 w-full max-w-md shadow-2xl">
        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Seksi Baru</h3>
        <form method="POST" action="{{ route('admin.form-builder.sections.store') }}" class="space-y-4">
            @csrf
            <div><label class="form-label">Kode *</label><input type="text" name="code" class="form-input" placeholder="cth. 1a" required></div>
            <div><label class="form-label">Nama Seksi *</label><input type="text" name="name" class="form-input" placeholder="cth. 1a. Tingkat Kesadaran" required></div>
            <div><label class="form-label">Deskripsi</label><textarea name="description" rows="2" class="form-input resize-none"></textarea></div>
            <div><label class="form-label">Urutan</label><input type="number" name="order_number" value="{{ $sections->count()+1 }}" class="form-input" required></div>
            <div class="flex justify-end gap-2 pt-1">
                <button type="button" @click="modal=null" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ SECTIONS LIST ═══ --}}
@forelse($sections as $section)
<div class="card overflow-hidden">

    {{-- Section header --}}
    <div class="flex items-start gap-3 bg-gray-50 dark:bg-gray-700/50 px-5 py-3 border-b border-gray-100 dark:border-gray-700">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-mono text-xs bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded">{{ $section->code }}</span>
                <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ $section->name }}</span>
                @if(!$section->is_active)<span class="badge badge-severe">Nonaktif</span>@endif
            </div>
            @if($section->description)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $section->description }}</p>
            @endif
            <p class="text-xs text-gray-400 mt-0.5">{{ $section->allQuestions->count() }} pertanyaan</p>
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button @click="modal='edit-sec-{{ $section->id }}'"
                    class="p-1.5 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-400 hover:text-amber-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <form method="POST" action="{{ route('admin.form-builder.sections.destroy',$section) }}" onsubmit="return confirm('Hapus seksi ini?')">
                @csrf @method('DELETE')
                <button class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Edit section modal --}}
    <div x-show="modal==='edit-sec-{{ $section->id }}'" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="modal=null">
        <div class="card p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Edit Seksi</h3>
            <form method="POST" action="{{ route('admin.form-builder.sections.update',$section) }}" class="space-y-4">
                @csrf @method('PUT')
                <div><label class="form-label">Kode *</label><input type="text" name="code" value="{{ $section->code }}" class="form-input" required></div>
                <div><label class="form-label">Nama *</label><input type="text" name="name" value="{{ $section->name }}" class="form-input" required></div>
                <div><label class="form-label">Deskripsi</label><textarea name="description" rows="2" class="form-input resize-none">{{ $section->description }}</textarea></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="form-label">Urutan</label><input type="number" name="order_number" value="{{ $section->order_number }}" class="form-input" required></div>
                    <div class="flex items-end pb-1"><label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 dark:text-gray-400"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ $section->is_active?'checked':'' }} class="accent-indigo-600"> Aktif</label></div>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="modal=null" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Questions --}}
    <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
        @forelse($section->allQuestions as $q)
        <div class="px-5 py-4">
            <div class="flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $q->question_text }}</p>
                        @if(!$q->is_active)<span class="badge badge-severe">Nonaktif</span>@endif
                    </div>
                    @if($q->instruction)
                        <p class="text-xs text-gray-400 mb-2 line-clamp-1">📌 {{ $q->instruction }}</p>
                    @endif
                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        @foreach($q->allOptions as $opt)
                        <div class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-lg
                             {{ $opt->is_active ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' : 'bg-red-50 dark:bg-red-900/20 text-red-400 line-through' }}">
                            <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $opt->score }}</span>
                            <span>{{ Str::limit($opt->option_text, 28) }}</span>
                            <button type="button" @click="modal='edit-opt-{{ $opt->id }}'"
                                    class="text-gray-300 hover:text-indigo-500 transition-colors ml-0.5 font-bold">✎</button>
                        </div>
                        @endforeach
                        <button type="button" @click="modal='add-opt-{{ $q->id }}'"
                                class="inline-flex items-center text-xs px-2 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100 transition-colors">
                            + Opsi
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="modal='edit-q-{{ $q->id }}'"
                            class="p-1.5 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-400 hover:text-amber-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('admin.form-builder.questions.destroy',$q) }}" onsubmit="return confirm('Hapus pertanyaan ini?')">
                        @csrf @method('DELETE')
                        <button class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Edit question modal --}}
            <div x-show="modal==='edit-q-{{ $q->id }}'" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="modal=null">
                <div class="card p-6 w-full max-w-lg shadow-2xl">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Edit Pertanyaan</h3>
                    <form method="POST" action="{{ route('admin.form-builder.questions.update',$q) }}" class="space-y-4">
                        @csrf @method('PUT')
                        <div><label class="form-label">Pertanyaan *</label><input type="text" name="question_text" value="{{ $q->question_text }}" class="form-input" required></div>
                        <div><label class="form-label">Instruksi</label><textarea name="instruction" rows="2" class="form-input resize-none">{{ $q->instruction }}</textarea></div>
                        <div class="grid grid-cols-3 gap-3">
                            <div><label class="form-label">Urutan</label><input type="number" name="order_number" value="{{ $q->order_number }}" class="form-input" required></div>
                            <div class="flex items-end pb-1"><label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 dark:text-gray-400"><input type="hidden" name="is_required" value="0"><input type="checkbox" name="is_required" value="1" {{ $q->is_required?'checked':'' }} class="accent-indigo-600"> Wajib</label></div>
                            <div class="flex items-end pb-1"><label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 dark:text-gray-400"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ $q->is_active?'checked':'' }} class="accent-indigo-600"> Aktif</label></div>
                        </div>
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" @click="modal=null" class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Add option modal --}}
            <div x-show="modal==='add-opt-{{ $q->id }}'" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="modal=null">
                <div class="card p-6 w-full max-w-md shadow-2xl">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Opsi Jawaban</h3>
                    <form method="POST" action="{{ route('admin.form-builder.options.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="question_id" value="{{ $q->id }}">
                        <div><label class="form-label">Teks Opsi *</label><input type="text" name="option_text" class="form-input" placeholder="cth. 0 – Normal" required></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="form-label">Skor *</label><input type="number" name="score" class="form-input" min="0" max="99" required></div>
                            <div><label class="form-label">Urutan</label><input type="number" name="order_number" value="{{ $q->allOptions->count() }}" class="form-input"></div>
                        </div>
                        <div><label class="form-label">Deskripsi</label><input type="text" name="description" class="form-input" placeholder="Opsional"></div>
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" @click="modal=null" class="btn btn-secondary">Batal</button>
                            <button type="submit" class="btn btn-success">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Edit option modals --}}
            @foreach($q->allOptions as $opt)
            <div x-show="modal==='edit-opt-{{ $opt->id }}'" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="modal=null">
                <div class="card p-6 w-full max-w-md shadow-2xl">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Edit Opsi</h3>
                    <form method="POST" action="{{ route('admin.form-builder.options.update',$opt) }}" class="space-y-4">
                        @csrf @method('PUT')
                        <div><label class="form-label">Teks Opsi *</label><input type="text" name="option_text" value="{{ $opt->option_text }}" class="form-input" required></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="form-label">Skor *</label><input type="number" name="score" value="{{ $opt->score }}" class="form-input" min="0" max="99" required></div>
                            <div><label class="form-label">Urutan</label><input type="number" name="order_number" value="{{ $opt->order_number }}" class="form-input"></div>
                        </div>
                        <div><label class="form-label">Deskripsi</label><input type="text" name="description" value="{{ $opt->description }}" class="form-input"></div>
                        <div><label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 dark:text-gray-400"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ $opt->is_active?'checked':'' }} class="accent-indigo-600"> Aktif</label></div>
                        <div class="flex items-center justify-between pt-1">
                            <form method="POST" action="{{ route('admin.form-builder.options.destroy',$opt) }}" onsubmit="return confirm('Hapus opsi ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:7px 12px;font-size:12px">Hapus</button>
                            </form>
                            <div class="flex gap-2">
                                <button type="button" @click="modal=null" class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @empty
        <div class="px-5 py-4 text-sm text-gray-400">Belum ada pertanyaan.</div>
        @endforelse

        {{-- Add question --}}
        <div class="px-5 py-3 bg-gray-50/50 dark:bg-gray-800/30">
            <button @click="modal='add-q-{{ $section->id }}'"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                + Tambah Pertanyaan
            </button>
        </div>
    </div>

    {{-- Add question modal --}}
    <div x-show="modal==='add-q-{{ $section->id }}'" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="modal=null">
        <div class="card p-6 w-full max-w-lg shadow-2xl">
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Pertanyaan ke "{{ $section->name }}"</h3>
            <form method="POST" action="{{ route('admin.form-builder.questions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="section_id" value="{{ $section->id }}">
                <div><label class="form-label">Teks Pertanyaan *</label><input type="text" name="question_text" class="form-input" required></div>
                <div><label class="form-label">Instruksi Pengisian</label><textarea name="instruction" rows="2" class="form-input resize-none"></textarea></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="form-label">Urutan</label><input type="number" name="order_number" value="{{ $section->allQuestions->count() }}" class="form-input" required></div>
                    <div class="flex items-end pb-1"><label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 dark:text-gray-400"><input type="hidden" name="is_required" value="0"><input type="checkbox" name="is_required" value="1" checked class="accent-indigo-600"> Wajib</label></div>
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="modal=null" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

</div>
@empty
<div class="card p-12 text-center text-gray-400">
    <p class="mb-3 text-sm">Belum ada seksi formulir. Mulai dengan menambahkan seksi pertama.</p>
    <button @click="modal='new-section'" class="btn btn-primary inline-flex">+ Tambah Seksi</button>
</div>
@endforelse

</div>
@endsection
