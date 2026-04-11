@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')
<div class="space-y-6">

{{-- ── Stats ──────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @php
    $cards = [
        ['label'=>'Total Pasien',     'value'=>$stats['total_patients'],    'bg'=>'bg-indigo-50 dark:bg-indigo-900/20',  'ico'=>'text-indigo-600 dark:text-indigo-400', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['label'=>'Pasien Aktif',     'value'=>$stats['active_patients'],   'bg'=>'bg-emerald-50 dark:bg-emerald-900/20','ico'=>'text-emerald-600 dark:text-emerald-400','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Total Pemeriksaan','value'=>$stats['total_assessments'], 'bg'=>'bg-violet-50 dark:bg-violet-900/20',  'ico'=>'text-violet-600 dark:text-violet-400', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label'=>'Periksa Hari Ini', 'value'=>$stats['today_assessments'],'bg'=>'bg-amber-50 dark:bg-amber-900/20',    'ico'=>'text-amber-600 dark:text-amber-400',   'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="card p-5 flex items-center gap-4">
        <div class="flex-shrink-0 w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center">
            <svg class="w-5 h-5 {{ $c['ico'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $c['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white leading-none">{{ number_format($c['value']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $c['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Charts ──────────────────────────────────────────────────────── --}}
<div class="grid lg:grid-cols-3 gap-5">

    {{-- Severity donut --}}
    <div class="card p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Distribusi Keparahan</h3>
        @php
        $severityMeta = [
            'normal'     =>['Normal','#10b981'],
            'mild'       =>['Ringan','#3b82f6'],
            'moderate'   =>['Sedang','#f59e0b'],
            'severe'     =>['Berat','#ef4444'],
            'very_severe'=>['Sangat Berat','#7c3aed'],
        ];
        $total = array_sum($severityDist);
        @endphp
        <div class="relative h-44 mb-4"><canvas id="severityChart"></canvas></div>
        <div class="space-y-1.5">
            @foreach($severityMeta as $key=>[$label,$color])
            @if(!empty($severityDist[$key]))
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{$color}}"></span>
                    <span class="text-gray-600 dark:text-gray-400">{{$label}}</span>
                </div>
                <span class="font-semibold text-gray-800 dark:text-gray-200">
                    {{$severityDist[$key]}}
                    <span class="text-gray-400 font-normal">({{$total>0?round($severityDist[$key]/$total*100):0}}%)</span>
                </span>
            </div>
            @endif
            @endforeach
            @if($total===0)
                <p class="text-xs text-gray-400 text-center py-3">Belum ada data pemeriksaan</p>
            @endif
        </div>
    </div>

    {{-- Trend chart --}}
    <div class="card p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tren Pemeriksaan (6 Bulan)</h3>
            <span class="text-xs px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium">
                Avg: {{$stats['avg_score']}} / 42
            </span>
        </div>
        <div class="relative h-52"><canvas id="trendChart"></canvas></div>
    </div>
</div>

{{-- ── Recent + Quick Actions ──────────────────────────────────────── --}}
<div class="grid lg:grid-cols-3 gap-5">

    {{-- Recent assessments --}}
    <div class="card lg:col-span-2 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pemeriksaan Terbaru</h3>
            <a href="{{ route('patients.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat semua →</a>
        </div>
        @forelse($recentAssessments as $a)
        <a href="{{ route('assessments.show', $a) }}"
           class="flex items-center gap-4 px-5 py-3.5 border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group last:border-0">
            <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">{{ strtoupper(substr($a->patient->name,0,2)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">{{ $a->patient->name }}</p>
                <p class="text-xs text-gray-400">{{ $a->assessed_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="text-xl font-extrabold text-gray-800 dark:text-gray-200">{{ $a->total_score }}</span>
                <span class="badge badge-{{ $a->severity === 'very_severe' ? 'very-severe' : $a->severity }}">{{ $a->severity_label }}</span>
            </div>
        </a>
        @empty
        <div class="px-5 py-12 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
            <p class="text-sm">Belum ada pemeriksaan</p>
        </div>
        @endforelse
    </div>

    {{-- Quick actions + severity guide --}}
    <div class="space-y-4">
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('patients.create') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">Tambah Pasien Baru</p>
                        <p class="text-xs text-indigo-500 dark:text-indigo-400">Daftarkan pasien</p>
                    </div>
                </a>
                <a href="{{ route('patients.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Cari Pasien</p>
                        <p class="text-xs text-gray-400">Temukan & periksa</p>
                    </div>
                </a>
                <a href="{{ route('admin.form-builder.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Form Builder</p>
                        <p class="text-xs text-gray-400">Kelola formulir NIHSS</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="card p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Panduan Skor NIHSS</h3>
            <div class="space-y-2">
                @foreach([['0','Normal','badge-normal'],['1 – 4','Ringan','badge-mild'],['5 – 15','Sedang','badge-moderate'],['16 – 20','Berat','badge-severe'],['21 – 42','Sangat Berat','badge-very-severe']] as [$r,$l,$cls])
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $r }}</span>
                    <span class="badge {{ $cls }}">{{ $l }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dark = document.documentElement.classList.contains('dark');
    const grid = dark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.05)';
    const txt  = dark ? '#9ca3af' : '#6b7280';

    // Severity donut
    const svCtx = document.getElementById('severityChart');
    if (svCtx) {
        const raw    = @json($severityDist);
        const keys   = ['normal','mild','moderate','severe','very_severe'];
        const labels = ['Normal','Ringan','Sedang','Berat','Sangat Berat'];
        const colors = ['#10b981','#3b82f6','#f59e0b','#ef4444','#7c3aed'];
        const idx    = keys.map((k,i)=>raw[k]>0?i:-1).filter(i=>i>=0);
        if (idx.length) {
            new Chart(svCtx,{
                type:'doughnut',
                data:{
                    labels: idx.map(i=>labels[i]),
                    datasets:[{data:idx.map(i=>raw[keys[i]]||0),backgroundColor:idx.map(i=>colors[i]),borderWidth:3,borderColor:dark?'#1f2937':'#fff'}]
                },
                options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>` ${c.label}: ${c.parsed} kasus`}}}}
            });
        }
    }

    // Trend
    const tCtx = document.getElementById('trendChart');
    if (tCtx) {
        const t = @json($monthlyTrend);
        const months = t.map(x=>{const[y,m]=x.month.split('-');return new Date(y,m-1).toLocaleString('id-ID',{month:'short',year:'2-digit'});});
        new Chart(tCtx,{
            type:'bar',
            data:{
                labels:months,
                datasets:[
                    {label:'Pemeriksaan',data:t.map(x=>x.count),backgroundColor:'rgba(99,102,241,.15)',borderColor:'#6366f1',borderWidth:2,borderRadius:6,yAxisID:'y'},
                    {label:'Rata-rata Skor',type:'line',data:t.map(x=>x.avg_score),borderColor:'#f59e0b',backgroundColor:'rgba(245,158,11,.1)',borderWidth:2,pointRadius:4,pointBackgroundColor:'#f59e0b',tension:.4,fill:true,yAxisID:'y1'}
                ]
            },
            options:{
                responsive:true,maintainAspectRatio:false,
                plugins:{legend:{labels:{color:txt,font:{size:11}}}},
                scales:{
                    x:{grid:{color:grid},ticks:{color:txt}},
                    y:{grid:{color:grid},ticks:{color:txt},title:{display:true,text:'Pemeriksaan',color:txt,font:{size:10}}},
                    y1:{position:'right',grid:{drawOnChartArea:false},ticks:{color:txt},title:{display:true,text:'Avg Skor',color:txt,font:{size:10}}}
                }
            }
        });
    }
});
</script>
@endpush
