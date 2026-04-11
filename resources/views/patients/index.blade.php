@extends('layouts.app')
@section('title','Data Pasien')
@section('page-title','Data Pasien')
@section('breadcrumb','Daftar seluruh pasien terdaftar')

@section('content')
<div class="space-y-5">

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    @foreach([
        ['Total',         $stats['total'],            'bg-gray-100 dark:bg-gray-700',    'bg-gray-400'],
        ['Aktif',         $stats['active'],            'bg-emerald-50 dark:bg-emerald-900/20','bg-emerald-400'],
        ['Pulang',        $stats['discharged'],        'bg-blue-50 dark:bg-blue-900/20',  'bg-blue-400'],
        ['Periksa Hari Ini',$stats['assessments_today'],'bg-violet-50 dark:bg-violet-900/20','bg-violet-400'],
    ] as [$lbl,$val,$bg,$bar])
    <div class="card px-4 py-3 flex items-center gap-3">
        <div class="w-1.5 h-10 rounded-full {{ $bar }} flex-shrink-0"></div>
        <div>
            <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-none">{{ $val }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $lbl }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="card px-4 py-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
    <form method="GET" class="flex flex-1 gap-2">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-input pl-9" placeholder="Nama, no. rekam medis, diagnosa…">
        </div>
        <select name="status" class="form-input w-36">
            <option value="">Semua</option>
            <option value="active"     {{ request('status')==='active'    ?'selected':'' }}>Aktif</option>
            <option value="discharged" {{ request('status')==='discharged'?'selected':'' }}>Pulang</option>
            <option value="deceased"   {{ request('status')==='deceased'  ?'selected':'' }}>Meninggal</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>
    <a href="{{ route('patients.create') }}" class="btn btn-primary flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Pasien
    </a>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th>Pasien</th>
                    <th class="hidden sm:table-cell">No. RM</th>
                    <th class="hidden md:table-cell">Diagnosa</th>
                    <th class="text-center">Skor Terakhir</th>
                    <th class="text-center hidden lg:table-cell">Pemeriksaan</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $p)
                <tr>
                    <td>
                        <a href="{{ route('patients.show',$p) }}" class="flex items-center gap-3 group">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">{{ strtoupper(substr($p->name,0,2)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $p->name }}</p>
                                <p class="text-xs text-gray-400">{{ $p->gender_label }}, {{ $p->age }} tahun</p>
                            </div>
                        </a>
                    </td>
                    <td class="hidden sm:table-cell">
                        <span class="font-mono text-xs text-gray-500 dark:text-gray-400">{{ $p->medical_record_number }}</span>
                    </td>
                    <td class="hidden md:table-cell">
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ $p->diagnosis ?? '–' }}</span>
                    </td>
                    <td class="text-center">
                        @if($p->latestAssessment)
                            <p class="text-lg font-extrabold text-gray-800 dark:text-gray-200 leading-none">{{ $p->latestAssessment->total_score }}</p>
                            @php $sv = $p->latestAssessment->severity; @endphp
                            <span class="badge badge-{{ $sv==='very_severe'?'very-severe':$sv }}">{{ $p->latestAssessment->severity_label }}</span>
                        @else
                            <span class="text-gray-300 dark:text-gray-600 text-sm">–</span>
                        @endif
                    </td>
                    <td class="text-center hidden lg:table-cell">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-bold text-gray-600 dark:text-gray-300">
                            {{ $p->assessments_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php $sc=['active'=>'badge-normal','discharged'=>'badge-mild','deceased'=>'badge-severe']; @endphp
                        <span class="badge {{ $sc[$p->status]??'' }}">{{ $p->status_label }}</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('patients.show',$p) }}"
                               class="p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-400 hover:text-indigo-600 transition-colors" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('patients.assessments.create',$p) }}"
                               class="p-1.5 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-gray-400 hover:text-emerald-600 transition-colors" title="Periksa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </a>
                            <a href="{{ route('patients.edit',$p) }}"
                               class="p-1.5 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-400 hover:text-amber-600 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('patients.destroy',$p) }}" onsubmit="return confirm('Hapus pasien {{ addslashes($p->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="text-gray-400 space-y-2">
                            <svg class="w-12 h-12 mx-auto opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-medium">Tidak ada pasien ditemukan</p>
                            @if(!request('search'))
                                <a href="{{ route('patients.create') }}" class="btn btn-primary inline-flex">+ Tambah Pasien</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
        {{ $patients->links() }}
    </div>
    @endif
</div>

</div>
@endsection
