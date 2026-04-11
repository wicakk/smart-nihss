<!DOCTYPE html>
<html lang="id" x-data="nihssApp()" :class="{ 'dark': darkMode }">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMART NIHSS — RSUP Fatmawati</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
            mono: ['IBM Plex Mono', 'monospace'],
          }
        }
      }
    }
  </script>
  <style>
    [x-cloak] { display: none !important; }
    html { scroll-behavior: smooth; }

    .opt-label {
      transition: border-color 0.15s, background-color 0.15s;
      cursor: pointer;
    }
    .opt-label:hover { border-color: #3b82f6; }
    .opt-label.selected {
      border-color: #3b82f6;
      background-color: rgba(59,130,246,0.06);
    }
    .dark .opt-label { border-color: #2d3748; background-color: #1e2433; color: #cbd5e0; }
    .dark .opt-label:hover { border-color: #4a6fa5; background-color: #252d3d; }
    .dark .opt-label.selected { border-color: #3b82f6; background-color: rgba(59,130,246,0.12); color: #93c5fd; }

    .card-error {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239,68,68,0.15);
      animation: shakeOnce 0.35s ease;
    }
    .dark .card-error {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239,68,68,0.2);
    }
    @keyframes shakeOnce {
      0%   { transform: translateX(0); }
      20%  { transform: translateX(-5px); }
      40%  { transform: translateX(5px); }
      60%  { transform: translateX(-4px); }
      80%  { transform: translateX(4px); }
      100% { transform: translateX(0); }
    }
    .error-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 11px;
      font-weight: 700;
      color: #ef4444;
      background: rgba(239,68,68,0.1);
      border-radius: 6px;
      padding: 2px 8px;
    }
    .dark .error-badge { background: rgba(239,68,68,0.18); }

    .score-bar-track { background-color: #e2e8f0; }
    .dark .score-bar-track { background-color: #1e2433; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.2s ease both; }

    @keyframes scorePopIn {
      0%   { transform: scale(1); }
      40%  { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    .score-pop { animation: scorePopIn 0.3s cubic-bezier(0.34,1.56,0.64,1); }

    .dark .card-bg    { background-color: #161b22; border-color: #21262d; }
    .dark .header-bg  { background-color: #1c2230; border-color: #21262d; }
    .dark .nav-bg     { background-color: #0d1117; border-color: #21262d; }
    .dark .fixed-bg   { background-color: #0d1117; border-color: #21262d; }

    .card-bg   { background-color: #ffffff; border-color: #e2e8f0; }
    .header-bg { background-color: #f8fafc; border-color: #e9ecef; }
    .nav-bg    { background-color: #ffffff; border-color: #e2e8f0; }
    .fixed-bg  { background-color: #ffffff; border-color: #e2e8f0; }

    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to   { transform: translateY(0);    opacity: 1; }
    }
    .slide-up { animation: slideUp 0.25s ease both; }

    .dark body { background-color: #0d1117; color: #e6edf3; }
    body { background-color: #f0f4f8; color: #1a202c; transition: background-color 0.25s, color 0.25s; }
  </style>
</head>

<body class="min-h-screen font-sans antialiased">

<nav class="nav-bg border-b sticky top-0 z-50 transition-colors duration-300">
  <div class="max-w-xl mx-auto px-4 h-14 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
      </div>
      <div>
        <p class="text-sm font-bold leading-tight" :class="darkMode ? 'text-white' : 'text-gray-900'">SMART NIHSS</p>
        <p class="text-xs leading-tight" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">RSUP Fatmawati</p>
      </div>
    </div>
    <button @click="darkMode = !darkMode"
      class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all"
      :class="darkMode ? 'border-gray-700 text-gray-400 hover:bg-gray-800 hover:text-gray-200' : 'border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800'">
      <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
      <svg x-show="darkMode"  class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    </button>
  </div>
</nav>

<main class="max-w-xl mx-auto px-4 pt-4 pb-52 space-y-3">

  <div class="card-bg border rounded-2xl px-4 py-3 flex items-center gap-2.5 transition-colors duration-300">
    <svg class="w-4 h-4 flex-shrink-0" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    <span class="text-xs font-bold font-mono" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="currentDatetime"></span>
    <span class="text-xs ml-auto font-mono" :class="darkMode ? 'text-gray-600' : 'text-gray-300'">WIB</span>
  </div>

  <template x-for="(section, si) in questions" :key="si">
    <div class="card-bg border rounded-2xl overflow-hidden transition-all duration-300 fade-up"
      :style="`animation-delay:${si * 30}ms`"
      :class="[showErrors && answers[section.id] === undefined ? 'card-error' : '']"
      :id="'section-' + section.id">

      <div class="header-bg border-b px-4 py-3 flex items-center gap-2.5">
        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white text-xs font-bold font-mono flex items-center justify-center flex-shrink-0" x-text="section.code"></span>
        <span class="text-sm font-extrabold flex-1" x-text="section.label"></span>
        <span x-show="showErrors && answers[section.id] === undefined" class="error-badge">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
          Wajib diisi
        </span>
      </div>

      <div class="p-4 space-y-2">
        <p x-show="section.instruction" class="text-xs font-bold leading-relaxed mb-1" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="section.instruction"></p>
        <template x-for="(opt, oi) in section.options" :key="oi">
          <label class="opt-label flex items-center gap-3 px-3 py-3 rounded-xl border"
            :class="[answers[section.id] === oi ? 'selected' : '', darkMode ? 'border-gray-700' : 'border-gray-200']"
            @click="setAnswer(section.id, oi)">
            <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all"
              :class="answers[section.id] === oi ? 'border-blue-500 bg-blue-500' : (darkMode ? 'border-gray-600' : 'border-gray-300')">
              <div x-show="answers[section.id] === oi" class="w-2 h-2 rounded-full bg-white"></div>
            </div>
            <div class="flex-1 flex items-center justify-between gap-2">
              <span class="text-sm leading-snug" x-text="opt.label"></span>
              <span class="text-xs font-mono font-semibold flex-shrink-0 transition-colors"
                :class="answers[section.id] === oi ? 'text-blue-500' : (darkMode ? 'text-gray-600' : 'text-gray-300')"
                x-text="'Skor ' + opt.score"></span>
            </div>
          </label>
        </template>
      </div>
    </div>
  </template>

  <div x-show="history.length > 0" class="pt-2 space-y-3">
    <div class="flex items-center gap-2">
      <div class="h-px flex-1" :class="darkMode ? 'bg-gray-800' : 'bg-gray-200'"></div>
      <p class="text-xs font-semibold font-mono uppercase tracking-widest" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"
        x-text="history.length + ' Pemeriksaan Tersimpan'"></p>
      <div class="h-px flex-1" :class="darkMode ? 'bg-gray-800' : 'bg-gray-200'"></div>
    </div>

    <template x-for="(item, idx) in history.slice().reverse()" :key="idx">
      <div class="card-bg border rounded-2xl px-4 py-4 flex items-center justify-between gap-3 transition-colors duration-300 fade-up">
        <div>
          <div class="flex items-baseline gap-1.5">
            <span class="text-3xl font-bold font-mono leading-none"
              :style="`color: ${getCategoryColor(item.category)}`"
              x-text="item.total"></span>
            <span class="text-sm" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">/42</span>
          </div>
          <p class="text-xs mt-1.5" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="item.datetime"></p>
        </div>
        <div class="flex flex-col items-end gap-2">
          <span class="px-2.5 py-1 rounded-full text-xs font-semibold" :class="getCategoryBadge(item.category)" x-text="item.category"></span>
          <button @click="showDetail(item)"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all"
            :class="darkMode ? 'border-gray-700 text-gray-400 hover:border-blue-500 hover:text-blue-400' : 'border-gray-200 text-gray-500 hover:border-blue-400 hover:text-blue-600'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
            Lihat Detail
          </button>
        </div>
      </div>
    </template>
  </div>

</main>

<div class="fixed bottom-0 left-0 right-0 z-40">
  <div class="max-w-xl mx-auto">
    <div class="fixed-bg border-t px-4 pt-3 pb-4 transition-colors duration-300 bg-blue-500 my-3 rounded-2xl shadow-lg">
      <div class="flex items-center justify-between mb-2.5">
        <div>
          <p class="text-xs font-semibold uppercase tracking-widest font-mono mb-1" :class="darkMode ? 'text-gray-600' : 'text-gray-200'">Total Skor NIHSS =</p>
          <div class="flex items-baseline gap-1.5">
            <span class="text-4xl font-bold font-mono leading-none transition-colors duration-300 score-pop" id="scoreNum"
              :class="scoreColor" x-text="totalScore"></span>
            <span class="text-sm" :class="darkMode ? 'text-gray-600' : 'text-gray-200'">/42</span>
          </div>
        </div>
        <div class="text-right space-y-1.5">
          <span class="block px-3 py-1.5 rounded-full text-xs font-semibold" :class="categoryBadge" x-text="categoryLabel"></span>
          <p class="text-xs font-mono" :class="darkMode ? 'text-gray-600' : 'text-gray-200'" x-text="answeredCount + '/15 item terjawab'"></p>
        </div>
      </div>
      <div class="score-bar-track rounded-full h-1.5 mb-3 overflow-hidden">
        <div class="h-full rounded-full transition-all duration-500" :class="barColor" :style="'width:' + Math.min((totalScore/42)*100,100) + '%'"></div>
      </div>
      <div class="flex gap-2.5">
        <button @click="resetAll()"
          class="flex-1 py-2.5 rounded-xl border text-sm font-semibold transition-all"
          :class="darkMode ? 'border-gray-700 text-gray-400 hover:bg-gray-800 hover:text-gray-200' : 'border-gray-200 text-gray-200 hover:bg-gray-50 hover:text-gray-700'">
          Reset
        </button>
        <button @click="trySave()"
          class="flex-[2] py-2.5 rounded-xl text-sm font-semibold transition-all"
          :class="answeredCount >= 15
            ? 'bg-blue-600 hover:bg-blue-700 text-white'
            : (darkMode ? 'bg-gray-800 text-gray-400 hover:bg-gray-700' : 'bg-white/20 text-white hover:bg-white/30')">
          <span x-show="answeredCount < 15">Simpan — <span x-text="15 - answeredCount"></span> item belum diisi</span>
          <span x-show="answeredCount >= 15">Simpan Hasil</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- DETAIL MODAL -->
<div x-show="showModal" x-cloak
  class="fixed inset-0 z-50 flex items-end justify-center"
  style="background:rgba(0,0,0,0.55)"
  @click.self="showModal = false">

  <div class="card-bg border-t rounded-t-3xl w-full max-w-xl max-h-[88vh] overflow-y-auto slide-up transition-colors duration-300">
    <div class="sticky top-0 z-10 flex items-start justify-between px-5 py-4 border-b transition-colors duration-300"
      :class="darkMode ? 'bg-gray-900 border-gray-800' : 'bg-white border-gray-100'">
      <div>
        <p class="font-bold text-sm" x-text="selectedItem?.datetime"></p>
        <div class="flex items-center gap-2 mt-1">
          <span class="font-mono font-bold text-blue-500 text-sm" x-text="'Skor ' + selectedItem?.total"></span>
          <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
            :class="selectedItem ? getCategoryBadge(selectedItem.category) : ''"
            x-text="selectedItem?.category"></span>
        </div>
      </div>
      <button @click="showModal = false"
        class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors"
        :class="darkMode ? 'text-gray-500 hover:bg-gray-800' : 'text-gray-400 hover:bg-gray-100'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="px-5 py-4 space-y-4">
      <div class="rounded-2xl p-4 transition-colors" :class="darkMode ? 'bg-gray-800/50' : 'bg-gray-50'">
        <p class="text-xs font-semibold font-mono uppercase tracking-widest mb-3" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Profil Skor per Domain</p>
        <div style="position:relative;height:200px">
          <canvas id="detailChart" role="img" aria-label="Grafik skor NIHSS per domain"></canvas>
        </div>
      </div>

      <div class="rounded-2xl overflow-hidden border transition-colors" :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
        <div class="px-4 py-3 border-b" :class="darkMode ? 'bg-gray-800/50 border-gray-800' : 'bg-gray-50 border-gray-100'">
          <p class="text-xs font-semibold font-mono uppercase tracking-widest" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Klasifikasi Keparahan</p>
        </div>
        <table class="w-full">
          <thead>
            <tr class="border-b" :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
              <th class="px-4 py-2 text-left text-xs font-semibold" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Kategori</th>
              <th class="px-4 py-2 text-left text-xs font-semibold" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Rentang</th>
              <th class="px-4 py-2 text-left text-xs font-semibold" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Prognosis</th>
              <th class="px-4 py-2 text-right text-xs font-semibold" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Status</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="row in severityRows" :key="row.label">
              <tr class="border-b last:border-0" :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
                <td class="px-4 py-2.5 text-xs font-bold" :style="`color:${row.color}`" x-text="row.label"></td>
                <td class="px-4 py-2.5 text-xs font-mono" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="row.range"></td>
                <td class="px-4 py-2.5 text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="row.prognosis"></td>
                <td class="px-4 py-2.5 text-right">
                  <span x-show="selectedItem && selectedItem.category === row.label"
                    class="text-xs font-bold" :style="`color:${row.color}`">▶ Pasien ini</span>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <div>
        <p class="text-xs font-semibold font-mono uppercase tracking-widest mb-3" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Rincian 15 Item</p>
        <div class="space-y-0">
          <template x-for="(detail, di) in selectedItem?.details" :key="detail.code">
            <div class="flex items-start justify-between py-3 border-b last:border-0"
              :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
              <div class="flex-1 pr-4">
                <span class="text-xs font-mono font-bold text-blue-500" x-text="detail.code"></span>
                <p class="text-sm font-semibold mt-0.5" x-text="detail.label"></p>
                <p class="text-xs mt-0.5 leading-snug" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="detail.answer"></p>
              </div>
              <span class="text-base font-bold font-mono flex-shrink-0 mt-0.5"
                :class="detail.score === 0 ? 'text-green-500' : (detail.score >= 3 ? 'text-red-500' : 'text-orange-500')"
                x-text="detail.score"></span>
            </div>
          </template>
        </div>
      </div>

      <!-- Tombol unduh PDF -->
      <button @click="downloadPDF()" :disabled="pdfLoading"
        class="w-full py-3 rounded-2xl text-sm font-bold transition-all flex items-center justify-center gap-2"
        :class="pdfLoading ? 'bg-blue-400 cursor-wait text-white' : 'bg-blue-600 hover:bg-blue-700 text-white'">
        <svg x-show="!pdfLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <svg x-show="pdfLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
        <span x-text="pdfLoading ? 'Membuat PDF...' : 'Unduh Laporan PDF'"></span>
      </button>
    </div>
  </div>
</div>

<script>
function nihssApp() {
  return {
    darkMode: false,
    answers: {},
    history: [],
    showModal: false,
    selectedItem: null,
    currentDatetime: '',
    chartInstance: null,
    showErrors: false,
    pdfLoading: false,

    severityRows: [
      { label: 'Ringan',      range: '0 - 4',   prognosis: 'Baik',         color: '#22c55e' },
      { label: 'Sedang',      range: '5 - 14',  prognosis: 'Sedang',       color: '#eab308' },
      { label: 'Berat',       range: '15 - 24', prognosis: 'Kurang baik',  color: '#f97316' },
      { label: 'Sangat Berat',range: '25 - 42', prognosis: 'Buruk',        color: '#ef4444' },
    ],

    questions: [
      { id:'1a', code:'1a', label:'Tingkat Kesadaran', instruction:'Nilai kesadaran pasien secara umum.',
        options:[{score:0,label:'Sadar penuh'},{score:1,label:'Somnolen'},{score:2,label:'Stupor'},{score:3,label:'Koma'}] },
      { id:'1b', code:'1b', label:'Menjawab Pertanyaan', instruction:'Tanyakan bulan dan usia pasien.',
        options:[{score:0,label:'Benar semua (2 pertanyaan)'},{score:1,label:'1 benar / ETT / disartria'},{score:2,label:'Salah semua / afasia / stupor / koma'}] },
      { id:'1c', code:'1c', label:'Mengikuti Perintah', instruction:'Berikan 2 perintah sederhana.',
        options:[{score:0,label:'Mampu melakukan 2 perintah'},{score:1,label:'Mampu melakukan 1 perintah'},{score:2,label:'Tidak mampu melakukan perintah'}] },
      { id:'2', code:'2', label:'Gaze : gerakan mata konyugat horizontal', instruction:'',
        options:[{score:0,label:'Normal'},{score:1,label:'Abnormal pada 1 mata'},{score:2,label:'Deviasi konjugat kuat atau paresis konjugat pada 2 mata'}] },
      { id:'3', code:'3', label:'Visual : lapang pandang pada tes konfrontasi', instruction:'Tes konfrontasi lapang pandang.',
        options:[{score:0,label:'Tidak ada gangguan'},{score:1,label:'Kuadrianopsia'},{score:2,label:'Hemianopia total'},{score:3,label:'Hemianopia bilateral / buta kortikal'}] },
      { id:'4', code:'4', label:'Paresis wajah', instruction:'Anjurkan pasien menyeringai atau mengangkat alis dan menutup mata.',
        options:[{score:0,label:'Normal'},{score:1,label:'Paresis wajah ringan'},{score:2,label:'Paresis wajah parsial'},{score:3,label:'Paresis wajah total'}] },
      { id:'5a', code:'5a', label:'Motorik Lengan Kiri', instruction:'Anjurkan pasien mengangkat lengan hingga 45 derajat bila tidur berbaring atau 90 derajat bila posisi duduk.',
        options:[{score:0,label:'Mampu mengangkat lengan minimal 10 detik'},{score:1,label:'Lengan terjatuh sebelum 10 detik'},{score:2,label:'Tidak mampu mengangkat secara penuh'},{score:3,label:'Tidak mampu mengangkat, hanya bergeser'},{score:4,label:'Tidak ada gerakan'}] },
      { id:'5b', code:'5b', label:'Motorik Lengan Kanan', instruction:'Anjurkan pasien mengangkat lengan hingga 45 derajat bila tidur berbaring atau 90 derajat bila posisi duduk.',
        options:[{score:0,label:'Mampu mengangkat lengan minimal 10 detik'},{score:1,label:'Lengan terjatuh sebelum 10 detik'},{score:2,label:'Tidak mampu mengangkat secara penuh'},{score:3,label:'Tidak mampu mengangkat, hanya bergeser'},{score:4,label:'Tidak ada gerakan'}] },
      { id:'6a', code:'6a', label:'Motorik Tungkai Kiri', instruction:'Anjurkan pasien tidur terlentang dan mengangkat tungkai 30 derajat.',
        options:[{score:0,label:'Mampu mengangkat tungkai 30 derajat minimal 5 detik'},{score:1,label:'Tungkai jatuh pada akhir detik ke-5 secara perlahan'},{score:2,label:'Tungkai jatuh sebelum 5 detik'},{score:3,label:'Tidak mampu melawan gravitasi'},{score:4,label:'Tidak ada gerakan'}] },
      { id:'6b', code:'6b', label:'Motorik Tungkai Kanan', instruction:'Anjurkan pasien tidur terlentang dan mengangkat tungkai 30 derajat.',
        options:[{score:0,label:'Mampu mengangkat tungkai 30 derajat minimal 5 detik'},{score:1,label:'Tungkai jatuh pada akhir detik ke-5 secara perlahan'},{score:2,label:'Tungkai jatuh sebelum 5 detik'},{score:3,label:'Tidak mampu melawan gravitasi'},{score:4,label:'Tidak ada gerakan'}] },
      { id:'7', code:'7', label:'Ataksia Anggota Badan', instruction:'Menggunakan test unjuk jari hidung.',
        options:[{score:0,label:'Tidak ada ataksia'},{score:1,label:'Ataksia pada satu ekstremitas'},{score:2,label:'Ataksia pada dua atau lebih ekstremitas'}] },
      { id:'8', code:'8', label:'Sensorik', instruction:'Lakukan tes pada seluruh tubuh; tungkai. Lengan, badan, dan wajah. Pasien afasia diberi nilai 1. Pasien stupor atau koma diberi nilai 2',
        options:[{score:0,label:'Normal'},{score:1,label:'Gangguan sensori ringan-sedang'},{score:2,label:'Gangguan sensori berat atau total'}] },
      { id:'9', code:'9', label:'Kemampuan berbahasa', instruction:'Anjurkan pasien untuk menjelaskan suatu gambar atau membaca suatu tulisan. Bila pasien mengalami kebutaan, letakan suatu benda ditangan pasien dan anjurkan untuk menjelaskan benda tersebut.',
        options:[{score:0,label:'Normal'},{score:1,label:'Afasia ringan hingga sedang'},{score:2,label:'Afasia berat'},{score:3,label:'Mute, afasia global, koma'}] },
      { id:'10', code:'10', label:'Disartria', instruction:'',
        options:[{score:0,label:'Normal'},{score:1,label:'Disartria ringan'},{score:2,label:'Disartria berat'}] },
      { id:'11', code:'11', label:'Neglect / Inatensi', instruction:'',
        options:[{score:0,label:'Tidak ada neglect'},{score:1,label:'Tidak ada atensi pada salah satu modalitas'},{score:2,label:'Tidak ada atensi pada lebih dari satu modalitas'}] },
    ],

    init() {
      this.loadHistory()
      this.updateDatetime()
      setInterval(() => this.updateDatetime(), 1000)
      this.$watch('darkMode', () => {
        document.documentElement.classList.toggle('dark', this.darkMode)
      })
      document.documentElement.classList.toggle('dark', this.darkMode)
    },

    updateDatetime() {
      const now = new Date()
      this.currentDatetime = now.toLocaleString('id-ID', {
        timeZone: 'Asia/Jakarta', weekday: 'long', day: 'numeric',
        month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
      })
    },

    setAnswer(id, optIdx) {
      this.answers = { ...this.answers, [id]: optIdx }
      if (this.showErrors && this.answeredCount === 15) this.showErrors = false
    },

    get totalScore() {
      let total = 0
      for (const q of this.questions) {
        const idx = this.answers[q.id]
        if (idx !== undefined) total += q.options[idx].score
      }
      return total
    },

    get answeredCount() { return Object.keys(this.answers).length },

    get categoryLabel() {
      const s = this.totalScore
      if (this.answeredCount === 0) return 'Belum Diisi'
      if (s < 5)   return 'Ringan'
      if (s <= 14) return 'Sedang'
      if (s <= 24) return 'Berat'
      return 'Sangat Berat'
    },

    get scoreColor() {
      const s = this.totalScore
      if (this.answeredCount === 0) return this.darkMode ? 'text-gray-600' : 'text-gray-300'
      if (s < 5)   return 'text-green-500'
      if (s <= 14) return 'text-yellow-500'
      if (s <= 24) return 'text-orange-500'
      return 'text-red-500'
    },

    get categoryBadge() { return this.getCategoryBadge(this.categoryLabel) },

    get barColor() {
      const s = this.totalScore
      if (s < 5)   return 'bg-green-500'
      if (s <= 14) return 'bg-yellow-400'
      if (s <= 24) return 'bg-orange-500'
      return 'bg-red-500'
    },

    getCategoryBadge(cat) {
      if (cat === 'Ringan')      return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
      if (cat === 'Sedang')      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
      if (cat === 'Berat')       return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400'
      if (cat === 'Sangat Berat')return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
      return this.darkMode ? 'bg-gray-800 text-gray-500' : 'bg-gray-100 text-gray-400'
    },

    getCategoryColor(cat) {
      if (cat === 'Ringan')       return '#16a34a'
      if (cat === 'Sedang')       return '#ca8a04'
      if (cat === 'Berat')        return '#ea580c'
      if (cat === 'Sangat Berat') return '#dc2626'
      return '#94a3b8'
    },

    trySave() {
      if (this.answeredCount >= 15) { this.saveResult(); return }
      this.showErrors = true
      this.$nextTick(() => {
        const firstEmpty = this.questions.find(q => this.answers[q.id] === undefined)
        if (firstEmpty) {
          const el = document.getElementById('section-' + firstEmpty.id)
          if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' })
        }
      })
    },

    saveResult() {
      const now = new Date()
      const datetime = now.toLocaleString('id-ID', {
        timeZone: 'Asia/Jakarta', day: 'numeric', month: 'long',
        year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false
      }) + ' WIB'
      const details = this.questions.map(q => ({
        code: q.code, label: q.label,
        answer: q.options[this.answers[q.id]].label,
        score: q.options[this.answers[q.id]].score,
        maxScore: q.options[q.options.length - 1].score,
      }))
      this.history.push({ datetime, total: this.totalScore, category: this.categoryLabel, details })
      this.saveHistory()
      this.answers = {}
      this.showErrors = false
      this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }) })
    },

    resetAll() { this.answers = {}; this.showErrors = false },

    showDetail(item) {
      this.selectedItem = item
      this.showModal = true
      this.$nextTick(() => { this.renderChart(item) })
    },

    renderChart(item) {
      if (this.chartInstance) { this.chartInstance.destroy(); this.chartInstance = null }
      const ctx = document.getElementById('detailChart')
      if (!ctx) return
      const isDk = this.darkMode
      const gc = isDk ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)'
      const lc = isDk ? '#4b5563' : '#9ca3af'
      const scores = item.details.map(d => d.score)
      const maxes  = item.details.map(d => d.maxScore || 4)
      const colors = scores.map((s, i) =>
        s === 0 ? '#22c55e' : s === maxes[i] ? '#ef4444' : s >= 2 ? '#f97316' : '#3b82f6'
      )
      this.chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: item.details.map(d => d.code),
          datasets: [{ data: scores, backgroundColor: colors, borderRadius: 5, borderSkipped: false }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => 'Skor: ' + c.raw + ' / ' + maxes[c.dataIndex] } } },
          scales: {
            x: { ticks: { color: lc, font: { size: 10, family: 'IBM Plex Mono' } }, grid: { color: gc } },
            y: { min: 0, max: 4, ticks: { color: lc, font: { size: 10 }, stepSize: 1 }, grid: { color: gc } }
          }
        }
      })
    },

    // ══════════════════════════════════════
    // GENERATE PDF dengan jsPDF
    // ══════════════════════════════════════
    async downloadPDF() {
      const item = this.selectedItem
      if (!item) return

      this.pdfLoading = true
      await this.$nextTick()

      try {
        const { jsPDF } = window.jspdf
        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' })

        const pageW = 210
        const pageH = 297
        const marginL = 18
        const marginR = 18
        const contentW = pageW - marginL - marginR
        let y = 0

        // ── Warna berdasarkan kategori ──
        const catColors = {
          'Ringan':      [22, 163, 74],
          'Sedang':      [202, 138, 4],
          'Berat':       [234, 88, 12],
          'Sangat Berat':[220, 38, 38],
        }
        const catRgb = catColors[item.category] || [100, 116, 139]

        // ══ HEADER BIRU ══
        doc.setFillColor(37, 99, 235)
        doc.rect(0, 0, pageW, 38, 'F')

        // Logo kotak putih
        doc.setFillColor(255, 255, 255)
        doc.roundedRect(marginL, 10, 18, 18, 3, 3, 'F')
        doc.setFillColor(37, 99, 235)
        doc.setFontSize(8)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(37, 99, 235)
        doc.text('NIHSS', marginL + 9, 21, { align: 'center' })

        // Judul
        doc.setTextColor(255, 255, 255)
        doc.setFontSize(15)
        doc.setFont('helvetica', 'bold')
        doc.text('SMART NIHSS', marginL + 22, 18)
        doc.setFontSize(8)
        doc.setFont('helvetica', 'normal')
        doc.setTextColor(186, 210, 255)
        doc.text('RSUP Fatmawati', marginL + 22, 25)
        doc.text('Laporan Pemeriksaan Stroke', marginL + 22, 31)

        // Tanggal di kanan atas
        doc.setFontSize(7.5)
        doc.setTextColor(186, 210, 255)
        doc.text(item.datetime, pageW - marginR, 25, { align: 'right' })

        y = 50

        // ══ KOTAK SKOR UTAMA ══
        doc.setFillColor(248, 250, 252)
        doc.setDrawColor(226, 232, 240)
        doc.setLineWidth(0.3)
        doc.roundedRect(marginL, y, contentW, 28, 4, 4, 'FD')

        // Skor besar
        doc.setFontSize(32)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(...catRgb)
        doc.text(String(item.total), marginL + 22, y + 20, { align: 'center' })
        doc.setFontSize(10)
        doc.setTextColor(148, 163, 184)
        doc.text('/42', marginL + 34, y + 20)

        // Badge kategori
        const badgeX = marginL + 55
        const badgeY = y + 8
        doc.setFillColor(...catRgb.map(c => Math.min(255, c + 200)))
        doc.roundedRect(badgeX, badgeY, 38, 8, 2, 2, 'F')
        doc.setFontSize(8)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(...catRgb)
        doc.text(item.category, badgeX + 19, badgeY + 5.5, { align: 'center' })

        // Label skor
        doc.setFontSize(7)
        doc.setFont('helvetica', 'normal')
        doc.setTextColor(100, 116, 139)
        doc.text('Total Skor NIHSS', marginL + 22, y + 26, { align: 'center' })

        // Progress bar
        const barX = badgeX
        const barY = y + 18
        const barW = contentW - (badgeX - marginL) - 8
        doc.setFillColor(226, 232, 240)
        doc.roundedRect(barX, barY, barW, 3, 1.5, 1.5, 'F')
        const fillW = Math.min((item.total / 42) * barW, barW)
        doc.setFillColor(...catRgb)
        doc.roundedRect(barX, barY, fillW, 3, 1.5, 1.5, 'F')

        y += 36

        // ══ KLASIFIKASI KEPARAHAN ══
        doc.setFontSize(7.5)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(100, 116, 139)
        doc.text('KLASIFIKASI KEPARAHAN', marginL, y)
        y += 5

        const sevData = [
          { label: 'Ringan',       range: '0 - 4',   prognosis: 'Baik',        rgb: [22, 163, 74]  },
          { label: 'Sedang',       range: '5 - 14',  prognosis: 'Sedang',      rgb: [202, 138, 4]  },
          { label: 'Berat',        range: '15 - 24', prognosis: 'Kurang baik', rgb: [234, 88, 12]  },
          { label: 'Sangat Berat', range: '25 - 42', prognosis: 'Buruk',       rgb: [220, 38, 38]  },
        ]

        // Header tabel
        doc.setFillColor(241, 245, 249)
        doc.rect(marginL, y, contentW, 6, 'F')
        doc.setFontSize(7)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(100, 116, 139)
        doc.text('Kategori', marginL + 3, y + 4.2)
        doc.text('Rentang', marginL + 48, y + 4.2)
        doc.text('Prognosis', marginL + 80, y + 4.2)
        doc.text('Status', pageW - marginR - 3, y + 4.2, { align: 'right' })
        y += 6

        sevData.forEach(row => {
          const isActive = item.category === row.label
          if (isActive) {
            doc.setFillColor(...row.rgb.map(c => Math.min(255, c + 215)))
            doc.rect(marginL, y, contentW, 7, 'F')
          }
          doc.setDrawColor(226, 232, 240)
          doc.setLineWidth(0.2)
          doc.line(marginL, y + 7, marginL + contentW, y + 7)

          doc.setFontSize(7.5)
          doc.setFont('helvetica', 'bold')
          doc.setTextColor(...row.rgb)
          doc.text(row.label, marginL + 3, y + 4.8)

          doc.setFont('helvetica', 'normal')
          doc.setTextColor(71, 85, 105)
          doc.text(row.range, marginL + 48, y + 4.8)
          doc.text(row.prognosis, marginL + 80, y + 4.8)

          if (isActive) {
            doc.setFont('helvetica', 'bold')
            doc.setTextColor(...row.rgb)
            doc.text('Pasien ini', pageW - marginR - 3, y + 4.8, { align: 'right' })
          }
          y += 7
        })

        y += 8

        // ══ RINCIAN 15 ITEM ══
        doc.setFontSize(7.5)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(100, 116, 139)
        doc.text('RINCIAN 15 ITEM PEMERIKSAAN', marginL, y)
        y += 5

        // Header tabel detail
        doc.setFillColor(241, 245, 249)
        doc.rect(marginL, y, contentW, 6, 'F')
        doc.setFontSize(7)
        doc.setFont('helvetica', 'bold')
        doc.setTextColor(100, 116, 139)
        doc.text('Kode', marginL + 3, y + 4.2)
        doc.text('Domain', marginL + 18, y + 4.2)
        doc.text('Jawaban', marginL + 85, y + 4.2)
        doc.text('Skor', pageW - marginR - 3, y + 4.2, { align: 'right' })
        y += 6

        item.details.forEach((d, i) => {
          // Cek halaman baru
          if (y > pageH - 30) {
            doc.addPage()
            y = 20
          }

          const rowH = 9
          if (i % 2 === 0) {
            doc.setFillColor(248, 250, 252)
            doc.rect(marginL, y, contentW, rowH, 'F')
          }
          doc.setDrawColor(226, 232, 240)
          doc.setLineWidth(0.15)
          doc.line(marginL, y + rowH, marginL + contentW, y + rowH)

          // Kode (biru)
          doc.setFontSize(7)
          doc.setFont('helvetica', 'bold')
          doc.setTextColor(37, 99, 235)
          doc.text(d.code, marginL + 3, y + 5.8)

          // Label domain
          doc.setFont('helvetica', 'bold')
          doc.setTextColor(30, 41, 59)
          const labelTxt = doc.splitTextToSize(d.label, 62)
          doc.text(labelTxt[0], marginL + 18, y + 5.8)

          // Jawaban
          doc.setFont('helvetica', 'normal')
          doc.setTextColor(71, 85, 105)
          const ansTxt = doc.splitTextToSize(d.answer, 56)
          doc.text(ansTxt[0], marginL + 85, y + 5.8)

          // Skor (warna)
          const scoreRgb = d.score === 0 ? [22, 163, 74] : d.score >= 3 ? [220, 38, 38] : [234, 88, 12]
          doc.setFont('helvetica', 'bold')
          doc.setFontSize(8)
          doc.setTextColor(...scoreRgb)
          doc.text(String(d.score), pageW - marginR - 3, y + 5.8, { align: 'right' })

          y += rowH
        })

        y += 10

        // ══ FOOTER ══
        if (y > pageH - 20) { doc.addPage(); y = 20 }
        doc.setDrawColor(226, 232, 240)
        doc.setLineWidth(0.3)
        doc.line(marginL, y, pageW - marginR, y)
        y += 5
        doc.setFontSize(7)
        doc.setFont('helvetica', 'normal')
        doc.setTextColor(148, 163, 184)
        doc.text('Dibuat otomatis oleh SMART NIHSS - RSUP Fatmawati', marginL, y)
        doc.text('Halaman 1', pageW - marginR, y, { align: 'right' })

        // Simpan
        doc.save('NIHSS_Laporan_' + Date.now() + '.pdf')

      } catch (err) {
        console.error('PDF error:', err)
        alert('Gagal membuat PDF. Silakan coba lagi.')
      } finally {
        this.pdfLoading = false
      }
    },

    saveHistory() {
      try { localStorage.setItem('nihss_v4', JSON.stringify(this.history)) } catch(e) {}
    },
    loadHistory() {
      try {
        const s = localStorage.getItem('nihss_v4')
        if (s) this.history = JSON.parse(s)
      } catch(e) { this.history = [] }
    },
  }
}
</script>
</body>
</html>