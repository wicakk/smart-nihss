@extends('layouts.app')
@section('title', $patient->name)
@section('page-title', $patient->name)
@section('breadcrumb', 'RM: ' . $patient->medical_record_number . ' · ' . $patient->gender_label . ', ' . $patient->age . ' tahun')

@section('content')
<div class="space-y-5">

{{-- ── Header ────────────────────────────────────────────────────── --}}
<div class="card p-5">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-700 flex items-center justify-center flex-shrink-0 shadow-lg">
            <span class="text-2xl font-extrabold text-white">{{ strtoupper(substr($patient->name,0,2)) }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $patient->name }}</h2>
                @php $sc=['active'=>'badge-normal','discharged'=>'badge-mild','deceased'=>'badge-severe']; @endphp
                <span class="badge {{ $sc[$patient->status]??'' }}">{{ $patient->status_label }}</span>
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                <span>📋 {{ $patient->medical_record_number }}</span>
                <span>{{ $patient->gender_label }}, {{ $patient->age }} tahun</span>
                @if($patient->diagnosis)<span>🏥 {{ $patient->diagnosis }}</span>@endif
                @if($patient->admission_date)<span>📅 Masuk: {{ $patient->admission_date->format('d M Y') }}</span>@endif
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('patients.assessments.create', $patient) }}" class="btn btn-success">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Periksa
            </a>
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-secondary">Edit</a>
        </div>
    </div>
</div>

{{-- ── Statistics ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
    @php
    $st = $statistics;
    $trendLabel = match($st['trend']){'improving'=>'↓ Membaik','worsening'=>'↑ Memburuk','stable'=>'→ Stabil',default=>'–'};
    $trendColor = match($st['trend']){'improving'=>'text-emerald-600 dark:text-emerald-400','worsening'=>'text-red-600 dark:text-red-400','stable'=>'text-blue-600 dark:text-blue-400',default=>'text-gray-400'};
    @endphp
    @foreach([
        ['Pemeriksaan', $st['count'].'x',        'text-indigo-600 dark:text-indigo-400'],
        ['Skor Terakhir',$st['latest']??'–',     'text-violet-600 dark:text-violet-400'],
        ['Rata-rata',    $st['avg']??'–',         'text-blue-600 dark:text-blue-400'],
        ['Minimum',      $st['min']??'–',         'text-emerald-600 dark:text-emerald-400'],
        ['Maksimum',     $st['max']??'–',         'text-amber-600 dark:text-amber-400'],
    ] as [$lbl,$val,$cls])
    <div class="card px-4 py-3 text-center">
        <p class="text-xl font-extrabold {{ $cls }} leading-tight">{{ $val }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $lbl }}</p>
    </div>
    @endforeach
    <div class="card px-4 py-3 text-center">
        <p class="text-base font-extrabold {{ $trendColor }} leading-tight">{{ $trendLabel }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Tren</p>
    </div>
</div>

{{-- ── Chart + Latest ──────────────────────────────────────────── --}}
<div class="grid lg:grid-cols-5 gap-5">
    <div class="card p-5 lg:col-span-3">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Perkembangan Skor NIHSS</h3>
        @if($chartData->isEmpty())
            <div class="h-48 flex items-center justify-center text-gray-400 text-sm">Belum ada data pemeriksaan</div>
        @else
            <div class="relative h-52"><canvas id="progressChart"></canvas></div>
        @endif
    </div>
    <div class="card p-5 lg:col-span-2">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Pemeriksaan Terakhir</h3>
        @php $latest = $patient->assessments->first(); @endphp
        @if($latest)
            @php $svColor=['normal'=>'border-emerald-400','mild'=>'border-blue-400','moderate'=>'border-amber-400','severe'=>'border-red-400','very_severe'=>'border-purple-400']; @endphp
            <div class="flex flex-col items-center py-4 text-center">
                <div class="w-24 h-24 rounded-full border-4 {{ $svColor[$latest->severity]??'border-gray-300' }} flex items-center justify-center mb-3">
                    <div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white leading-none">{{ $latest->total_score }}</p>
                        <p class="text-xs text-gray-400">/ 42</p>
                    </div>
                </div>
                @php $sv=$latest->severity; @endphp
                <span class="badge badge-{{ $sv==='very_severe'?'very-severe':$sv }} text-sm px-3 py-0.5">{{ $latest->severity_label }}</span>
                <p class="text-xs text-gray-400 mt-2">{{ $latest->assessed_at->format('d M Y, H:i') }}</p>
                <a href="{{ route('assessments.show', $latest) }}" class="btn btn-secondary text-xs py-1.5 mt-3">Lihat Detail →</a>
            </div>
        @else
            <div class="py-10 text-center text-gray-400 text-sm">Belum ada pemeriksaan</div>
        @endif
    </div>
</div>

{{-- ── History Table ──────────────────────────────────────────── --}}
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Riwayat Pemeriksaan</h3>
        <a href="{{ route('patients.assessments.create', $patient) }}" class="btn btn-primary" style="padding:6px 14px;font-size:12px">+ Baru</a>
    </div>
    @if($patient->assessments->isEmpty())
        <div class="p-10 text-center text-gray-400 text-sm">Belum ada pemeriksaan untuk pasien ini</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full tbl">
            <thead>
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th class="text-center">Total Skor</th>
                    <th class="text-center">Kategori</th>
                    <th class="hidden md:table-cell">Catatan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->assessments as $a)
                <tr>
                    <td>
                        <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm">{{ $a->assessed_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $a->assessed_at->format('H:i') }} WIB</p>
                    </td>
                    <td class="text-center">
                        <span class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $a->total_score }}</span>
                    </td>
                    <td class="text-center">
                        @php $sv=$a->severity; @endphp
                        <span class="badge badge-{{ $sv==='very_severe'?'very-severe':$sv }}">{{ $a->severity_label }}</span>
                    </td>
                    <td class="hidden md:table-cell">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $a->clinical_notes ? \Str::limit($a->clinical_notes,60) : '–' }}</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('assessments.show',$a) }}"
                               class="p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-gray-400 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('assessments.destroy',$a) }}" onsubmit="return confirm('Hapus pemeriksaan ini?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('progressChart');
    if (!ctx) return;
    const dark = document.documentElement.classList.contains('dark');
    const grid = dark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
    const txt  = dark ? '#9ca3af' : '#6b7280';
    const data = @json($chartData);
    if (!data.length) return;
    const svColors = {'Normal':'#10b981','Ringan':'#3b82f6','Sedang':'#f59e0b','Berat':'#ef4444','Sangat Berat':'#7c3aed'};
    new Chart(ctx,{
        type:'line',
        data:{
            labels: data.map(d=>d.date),
            datasets:[{
                label:'Skor NIHSS', data:data.map(d=>d.score),
                borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,.08)',
                borderWidth:2.5, tension:.35, fill:true,
                pointRadius:6, pointBorderColor:'#fff', pointBorderWidth:2,
                pointBackgroundColor: data.map(d=>svColors[d.severity]||'#6366f1'),
            }]
        },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{legend:{display:false}, tooltip:{callbacks:{label:c=>` Skor: ${c.parsed.y} (${data[c.dataIndex].severity})`}}},
            scales:{
                x:{grid:{color:grid},ticks:{color:txt,font:{size:10},maxRotation:35}},
                y:{grid:{color:grid},ticks:{color:txt},min:0,max:42,title:{display:true,text:'Skor NIHSS',color:txt,font:{size:10}}}
            }
        }
    });
});
</script>
@endpush
