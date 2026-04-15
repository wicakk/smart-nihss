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

    .opt-label-tdn {
      transition: border-color 0.15s, background-color 0.15s;
      cursor: pointer;
      border-style: dashed !important;
    }
    .opt-label-tdn:hover { border-color: #3b82f6 !important; background-color: rgba(59,130,246,0.04); }
    .opt-label-tdn.selected-tdn {
      border-color: #3b82f6 !important;
      background-color: rgba(59,130,246,0.07) !important;
    }
    .dark .opt-label-tdn { border-color: #1e3a5f !important; background-color: #0f172a !important; color: #93c5fd; }
    .dark .opt-label-tdn:hover { border-color: #2563eb !important; background-color: rgba(37,99,235,0.1) !important; }
    .dark .opt-label-tdn.selected-tdn { border-color: #3b82f6 !important; background-color: rgba(59,130,246,0.12) !important; color: #bfdbfe; }

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

    .van-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.04em;
      border-radius: 8px;
      padding: 3px 8px;
      margin-right: 4px;
    }
    .van-badge-V,
    .van-badge-A,
    .van-badge-N  { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .dark .van-badge-V,
    .dark .van-badge-A,
    .dark .van-badge-N { background: #1e3a5f; color: #93c5fd; border-color: #1d4ed8; }

    .van-indikasi-box {
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 11px;
      line-height: 1.6;
      margin-top: 8px;
    }
    .van-indikasi-V,
    .van-indikasi-A,
    .van-indikasi-N  { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
    .dark .van-indikasi-V,
    .dark .van-indikasi-A,
    .dark .van-indikasi-N { background: #1e3a5f; border-color: #1d4ed8; color: #93c5fd; }

    .motorik-info-box {
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 11px;
      line-height: 1.6;
      margin-top: 8px;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      color: #1e40af;
    }
    .dark .motorik-info-box {
      background: #1e3a5f;
      border-color: #1d4ed8;
      color: #93c5fd;
    }

    .motorik-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 10px;
      font-weight: 700;
      border-radius: 6px;
      padding: 2px 8px;
      margin-right: 4px;
      background: #eff6ff;
      color: #1e40af;
      border: 1px solid #bfdbfe;
    }
    .dark .motorik-badge { background: #1e3a5f; color: #93c5fd; border-color: #1d4ed8; }

    @keyframes infoFadeIn {
      from { opacity: 0; transform: translateY(-4px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .info-appear { animation: infoFadeIn 0.2s ease both; }

    /* ── VAN History Box ── */
    .van-history-box {
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 11px;
      line-height: 1.6;
      margin-top: 8px;
      border: 1px solid;
    }
    .van-history-lvo { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
    .van-history-neg { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
    .dark .van-history-lvo { background: #450a0a; border-color: #7f1d1d; color: #fca5a5; }
    .dark .van-history-neg { background: #052e16; border-color: #14532d; color: #86efac; }

    .lvo-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.05em;
      border-radius: 6px;
      padding: 2px 8px;
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fca5a5;
    }
    .dark .lvo-badge { background: #7f1d1d; color: #fecaca; border-color: #991b1b; }
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
    <div class="flex items-center gap-3">
      <button @click="darkMode = !darkMode"
        class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all"
        :class="darkMode ? 'border-gray-700 text-gray-400 hover:bg-gray-800 hover:text-gray-200' : 'border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800'">
        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        <svg x-show="darkMode"  class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      </button>
      <button class="h-9 w-20 rounded-md border border-slate-200 dark:border-[#30363d] bg-blue-500 dark:bg-[#1c2128] flex items-center justify-center text-white dark:text-slate-400 hover:border-blue-400 transition-colors">
          Beranda
      </button>
    </div>
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
      :class="[showErrors && answers[section.id] === undefined && !tdnAnswers[section.id] ? 'card-error' : '']"
      :id="'section-' + section.id">

      <div class="header-bg border-b px-4 py-3 flex items-center gap-2.5">
        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white text-xs font-bold font-mono flex items-center justify-center flex-shrink-0" x-text="section.code"></span>
        <span class="text-sm font-extrabold flex-1" x-text="section.label"></span>

        <template x-if="section.van">
          <span class="van-badge van-badge-V" x-text="section.van === 'V' ? 'V — Visual' : section.van === 'A' ? 'A — Aphasia' : 'N — Neglect'"></span>
        </template>

        <template x-if="section.motorik">
          <span class="motorik-badge" x-text="'Motorik ' + section.motorikSide"></span>
        </template>

        <span x-show="showErrors && answers[section.id] === undefined && !tdnAnswers[section.id]" class="error-badge">
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

        <div>
          <label class="opt-label-tdn flex items-center gap-3 px-3 py-2.5 rounded-xl border"
            :class="[
              tdnAnswers[section.id] === true ? 'selected-tdn' : '',
              darkMode ? 'border-blue-900' : 'border-blue-200'
            ]"
            @click="setTDN(section.id)">
            <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all"
              :class="tdnAnswers[section.id] === true
                ? 'border-blue-500 bg-blue-500'
                : (darkMode ? 'border-blue-900' : 'border-blue-300')">
              <svg x-show="tdnAnswers[section.id] === true" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="flex-1 flex items-center justify-between gap-2">
              <div class="flex items-center gap-1.5">
                <span class="text-sm leading-snug font-semibold"
                  :class="tdnAnswers[section.id] === true
                    ? (darkMode ? 'text-blue-300' : 'text-blue-700')
                    : (darkMode ? 'text-blue-400' : 'text-blue-500')">
                  Tidak Dapat Dinilai
                </span>
                <span class="text-xs font-mono px-1.5 py-0.5 rounded-md font-bold"
                  :class="darkMode ? 'bg-blue-900/40 text-blue-400' : 'bg-blue-100 text-blue-500'">
                  TDN
                </span>
              </div>
              <span class="text-xs font-mono font-semibold flex-shrink-0"
                :class="tdnAnswers[section.id] === true
                  ? (darkMode ? 'text-blue-400' : 'text-blue-500')
                  : (darkMode ? 'text-gray-600' : 'text-gray-300')">
                —
              </span>
            </div>
          </label>

          <div x-show="tdnAnswers[section.id] === true"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-2 rounded-xl border overflow-hidden"
            :class="darkMode ? 'border-blue-900/60' : 'border-blue-200'">
            <div class="flex items-center gap-2 px-3 py-2 border-b"
              :class="darkMode ? 'bg-blue-950/40 border-blue-900/60' : 'bg-blue-50 border-blue-200'">
              <svg class="w-3.5 h-3.5 flex-shrink-0" :class="darkMode ? 'text-blue-400' : 'text-blue-500'" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <p class="text-xs font-bold flex-1" :class="darkMode ? 'text-blue-300' : 'text-blue-700'">Alasan tidak dapat dinilai</p>
              <button @click.stop="setTDN(section.id)"
                class="text-xs font-bold flex items-center gap-1 px-2 py-0.5 rounded-lg transition-colors"
                :class="darkMode ? 'text-blue-500 hover:bg-blue-900/40 hover:text-blue-300' : 'text-blue-400 hover:bg-blue-100 hover:text-blue-700'">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Batalkan
              </button>
            </div>
            <div :class="darkMode ? 'bg-blue-950/20' : 'bg-white'">
              <textarea
                :id="'tdn-note-' + section.id"
                x-model="tdnNotes[section.id]"
                @click.stop @keydown.stop
                rows="3"
                placeholder="Tuliskan alasan di sini, misal: pasien tidak kooperatif, intubasi, trauma, sedasi, dsb..."
                class="w-full px-3 py-2.5 text-xs leading-relaxed resize-none outline-none bg-transparent placeholder-opacity-50 transition-colors"
                :class="darkMode ? 'text-blue-200 placeholder-blue-700 focus:placeholder-blue-600' : 'text-blue-900 placeholder-blue-300 focus:placeholder-blue-400'"
              ></textarea>
              <div class="px-3 pb-2 flex items-center justify-between">
                <p class="text-xs" :class="darkMode ? 'text-blue-700' : 'text-blue-300'">Skor tidak dihitung dalam total NIHSS</p>
                <span class="text-xs font-mono"
                  :class="(tdnNotes[section.id] || '').length > 200 ? 'text-red-400' : (darkMode ? 'text-blue-700' : 'text-blue-300')"
                  x-text="(tdnNotes[section.id] || '').length + '/250'"></span>
              </div>
            </div>
          </div>
        </div>

        <template x-if="section.van && answers[section.id] !== undefined && section.options[answers[section.id]].score >= 1">
          <div class="van-indikasi-box info-appear" :class="'van-indikasi-' + section.van">
            <div class="flex items-start gap-2">
              <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
              <div>
                <p class="font-bold mb-0.5" x-text="'Indikasi VAN — ' + (section.van === 'V' ? 'Visual' : section.van === 'A' ? 'Aphasia' : 'Neglect')"></p>
                <p x-text="section.vanIndikasi"></p>
              </div>
            </div>
          </div>
        </template>

        <template x-if="section.motorik && answers[section.id] !== undefined && section.options[answers[section.id]].score >= 1">
          <div class="motorik-info-box info-appear">
            <div class="flex items-start gap-2">
              <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
              </svg>
              <div>
                <p class="font-bold mb-0.5">Defisit Motorik Terdeteksi — <span x-text="section.motorikSide"></span></p>
                <p x-text="section.motorikNote"></p>
              </div>
            </div>
          </div>
        </template>

      </div>
    </div>
  </template>

  <!-- History -->
  <div x-show="history.length > 0" class="pt-2 space-y-3">
    <div class="flex items-center gap-2">
      <div class="h-px flex-1" :class="darkMode ? 'bg-gray-800' : 'bg-gray-200'"></div>
      <p class="text-xs font-semibold font-mono uppercase tracking-widest" :class="darkMode ? 'text-gray-600' : 'text-gray-400'"
        x-text="history.length + ' Pemeriksaan Tersimpan'"></p>
      <div class="h-px flex-1" :class="darkMode ? 'bg-gray-800' : 'bg-gray-200'"></div>
    </div>
    <template x-for="(item, idx) in history.slice().reverse()" :key="idx">
      <div class="card-bg border rounded-2xl px-4 py-4 transition-colors duration-300 fade-up">

        <!-- ══ ROW ATAS: skor + datetime kiri | tombol Lihat Detail kanan ══ -->
        <!-- PERBAIKAN: hapus badge kategori, skor pakai String() agar angka 0 tetap muncul -->
        <div class="flex items-center justify-between gap-3">
          <div>
            <div class="flex items-baseline gap-1.5">
              <!-- FIX: gunakan String(item.total) agar nilai 0 tetap tampil -->
              <span class="text-3xl font-bold font-mono leading-none"
                :style="`color: ${getCategoryColor(item.category)}`"
                x-text="String(item.total)"></span>
              <span class="text-sm" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">/42</span>
            </div>
            <p class="text-xs mt-1" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="item.datetime"></p>
          </div>

          <!-- Tombol Lihat Detail saja, tanpa badge kategori -->
          <button @click="showDetail(item)"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all flex-shrink-0"
            :class="darkMode ? 'border-gray-700 text-gray-400 hover:border-blue-500 hover:text-blue-400' : 'border-gray-200 text-gray-500 hover:border-blue-400 hover:text-blue-600'">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
            Lihat Detail
          </button>
        </div>

        <!-- ── VAN + LVO Info Box di History ── -->
        <template x-if="item.vanPositif && item.vanPositif.length > 0">
          <div class="van-history-box van-history-lvo mt-3 info-appear">
            <div class="flex items-start gap-2">
              <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                  <span class="font-bold text-xs">VAN Score Positif</span>
                  <span class="lvo-badge">⚠ Curiga LVO</span>
                  <template x-for="v in item.vanPositif" :key="v">
                    <span class="text-xs font-bold font-mono px-1.5 py-0.5 rounded-md"
                      :class="darkMode ? 'bg-red-900/40 text-red-300' : 'bg-red-100 text-red-700'"
                      x-text="v === 'V' ? 'V-Visual' : v === 'A' ? 'A-Aphasia' : 'N-Neglect'"></span>
                  </template>
                </div>
                <p class="text-xs leading-relaxed">
                  Komponen VAN positif pada pemeriksaan ini mengindikasikan kemungkinan <strong>Large Vessel Occlusion (LVO)</strong>. Pertimbangkan evaluasi lanjutan dan tindakan segera.
                </p>
              </div>
            </div>
          </div>
        </template>

        <!-- Jika semua VAN negatif -->
        <template x-if="item.vanPositif && item.vanPositif.length === 0">
          <div class="van-history-box van-history-neg mt-3">
            <div class="flex items-center gap-2">
              <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <p class="text-xs font-semibold">VAN Score Negatif — Tidak ada indikasi LVO pada pemeriksaan ini</p>
            </div>
          </div>
        </template>

      </div>
    </template>
  </div>

</main>

<!-- Fixed Bottom Bar -->
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
          <p class="text-xs font-mono" :class="darkMode ? 'text-gray-600' : 'text-gray-200'" x-text="answeredCount + '/15 item terjawab'"></p>
          <p x-show="tdnCount > 0" class="text-xs font-mono" :class="darkMode ? 'text-blue-400' : 'text-blue-200'" x-text="tdnCount + ' item TDN'"></p>
          <!-- VAN live indicator -->
          <div class="flex items-center gap-1 justify-end" x-show="vanSummary.length > 0">
            <template x-for="v in vanSummary" :key="v.key">
              <span class="text-xs font-bold font-mono px-1.5 py-0.5 rounded-md"
                :class="v.positive
                  ? 'bg-red-500/20 text-red-100'
                  : 'bg-white/10 text-white/50'"
                x-text="v.key + (v.positive ? '+' : '–')"></span>
            </template>
          </div>
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
        <div class="flex items-center gap-2 mt-1 flex-wrap">
          <span class="font-mono font-bold text-blue-500 text-sm" x-text="'Skor ' + selectedItem?.total"></span>
          <!-- Badge kategori tetap ada di modal detail untuk referensi klinis -->
          <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
            :class="selectedItem ? getCategoryBadge(selectedItem.category) : ''"
            x-text="selectedItem?.category"></span>
          <span x-show="selectedItem && selectedItem.tdnCount > 0"
            class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
            x-text="selectedItem?.tdnCount + ' TDN'"></span>
          <template x-if="selectedItem && selectedItem.vanPositif && selectedItem.vanPositif.length > 0">
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
              ⚠ Curiga LVO
            </span>
          </template>
          <template x-if="selectedItem && selectedItem.vanPositif && selectedItem.vanPositif.length > 0">
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400"
              x-text="'VAN: ' + selectedItem.vanPositif.join(', ')"></span>
          </template>
        </div>
      </div>
      <button @click="showModal = false"
        class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors"
        :class="darkMode ? 'text-gray-500 hover:bg-gray-800' : 'text-gray-400 hover:bg-gray-100'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="px-5 py-4 space-y-4">

      <!-- VAN LVO alert di modal -->
      <template x-if="selectedItem && selectedItem.vanPositif && selectedItem.vanPositif.length > 0">
        <div class="rounded-2xl p-4 border flex items-start gap-3"
          :class="darkMode ? 'bg-red-950/30 border-red-900/50' : 'bg-red-50 border-red-200'">
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5" :class="darkMode ? 'text-red-400' : 'text-red-600'" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
          </svg>
          <div>
            <p class="font-bold text-sm mb-1" :class="darkMode ? 'text-red-300' : 'text-red-800'">⚠ Curiga Large Vessel Occlusion (LVO)</p>
            <p class="text-xs leading-relaxed" :class="darkMode ? 'text-red-400' : 'text-red-700'">
              Komponen VAN positif: <span class="font-bold" x-text="selectedItem.vanPositif.map(v => v === 'V' ? 'Visual' : v === 'A' ? 'Aphasia' : 'Neglect').join(', ')"></span>.
              VAN Score positif pada pemeriksaan ini mengindikasikan kemungkinan oklusi pembuluh darah besar. Evaluasi lanjutan dan tindakan segera direkomendasikan.
            </p>
          </div>
        </div>
      </template>

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
            <div class="py-3 border-b last:border-0"
              :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
              <div class="flex items-start justify-between">
                <div class="flex-1 pr-4">
                  <div class="flex items-center gap-1.5 mb-0.5">
                    <span class="text-xs font-mono font-bold text-blue-500" x-text="detail.code"></span>
                    <template x-if="detail.van">
                      <span class="van-badge van-badge-V text-xs" x-text="detail.van"></span>
                    </template>
                    <template x-if="detail.motorik">
                      <span class="motorik-badge">Motorik</span>
                    </template>
                  </div>
                  <p class="text-sm font-semibold" x-text="detail.label"></p>
                  <p x-show="!detail.isTDN" class="text-xs mt-0.5 leading-snug"
                    :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                    x-text="detail.answer"></p>
                  <div x-show="detail.isTDN" class="mt-1 space-y-0.5">
                    <span class="text-xs font-bold" :class="darkMode ? 'text-blue-400' : 'text-blue-600'">Tidak Dapat Dinilai</span>
                    <p x-show="detail.tdnNote" class="text-xs leading-snug italic"
                      :class="darkMode ? 'text-blue-500' : 'text-blue-500'"
                      x-text="'\u201c' + detail.tdnNote + '\u201d'"></p>
                    <p x-show="!detail.tdnNote" class="text-xs leading-snug" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Tidak ada keterangan</p>
                  </div>
                </div>
                <div x-show="detail.isTDN" class="flex-shrink-0 mt-0.5">
                  <span class="text-xs font-bold font-mono px-1.5 py-0.5 rounded-md"
                    :class="darkMode ? 'bg-blue-900/40 text-blue-400' : 'bg-blue-100 text-blue-600'">TDN</span>
                </div>
                <span x-show="!detail.isTDN" class="text-base font-bold font-mono flex-shrink-0 mt-0.5"
                  :class="detail.score === 0 ? 'text-green-500' : (detail.score >= 3 ? 'text-red-500' : 'text-orange-500')"
                  x-text="detail.score"></span>
              </div>

              <template x-if="detail.van && !detail.isTDN && detail.score >= 1">
                <div class="mt-2 rounded-lg px-3 py-2 text-xs leading-relaxed"
                  :class="darkMode ? 'bg-blue-950/40 border border-blue-900/50 text-blue-300' : 'bg-blue-50 border border-blue-200 text-blue-700'">
                  <div class="flex items-start gap-1.5">
                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                      <span class="font-bold" x-text="'VAN ' + detail.van + ' (' + (detail.van === 'V' ? 'Visual' : detail.van === 'A' ? 'Aphasia' : 'Neglect') + ') — Positif'"></span>
                      <span x-text="' ' + getVanKeterangan(detail.van)"></span>
                    </div>
                  </div>
                </div>
              </template>

              <template x-if="detail.motorik && !detail.isTDN && detail.score >= 1">
                <div class="mt-2 rounded-lg px-3 py-2 text-xs leading-relaxed"
                  :class="darkMode ? 'bg-blue-950/40 border border-blue-900/50 text-blue-300' : 'bg-blue-50 border border-blue-200 text-blue-700'">
                  <div class="flex items-start gap-1.5">
                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                      <span class="font-bold">Defisit Motorik Terdeteksi — </span>
                      <span x-text="'Skor ' + detail.score + ': ' + getMotorKeterangan(detail.score)"></span>
                    </div>
                  </div>
                </div>
              </template>

            </div>
          </template>
        </div>
      </div>

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
    tdnAnswers: {},
    tdnNotes: {},
    history: [],
    showModal: false,
    selectedItem: null,
    currentDatetime: '',
    chartInstance: null,
    showErrors: false,
    pdfLoading: false,

    severityRows: [
      { label: 'Ringan',       range: '0 - 4',   prognosis: 'Baik',         color: '#22c55e' },
      { label: 'Sedang',       range: '5 - 14',  prognosis: 'Sedang',       color: '#eab308' },
      { label: 'Berat',        range: '15 - 24', prognosis: 'Kurang baik',  color: '#f97316' },
      { label: 'Sangat Berat', range: '25 - 42', prognosis: 'Buruk',        color: '#ef4444' },
    ],

    questions: [
      {
        id:'1a', code:'1a', label:'Tingkat Kesadaran',
        instruction:'Nilai kesadaran pasien secara umum.',
        options:[
          {score:0,label:'Sadar penuh'},
          {score:1,label:'Somnolen — dapat dibangunkan dengan stimulus minimal, menjawab & mengikuti perintah'},
          {score:2,label:'Stupor — perlu stimulasi kuat, hanya bergerak atau mengedipkan mata'},
          {score:3,label:'Koma — hanya refleks motorik atau otonom, tidak responsif'}
        ]
      },
      {
        id:'1b', code:'1b', label:'Menjawab Pertanyaan',
        instruction:'Tanyakan bulan saat ini dan usia pasien. Nilai hanya jawaban pertama.',
        options:[
          {score:0,label:'Menjawab benar 2 pertanyaan'},
          {score:1,label:'Menjawab benar 1 pertanyaan, atau pasien dengan ETT / disartria'},
          {score:2,label:'Tidak mampu menjawab keduanya — afasia, stupor, atau tidak kooperatif'}
        ]
      },
      {
        id:'1c', code:'1c', label:'Mengikuti Perintah',
        instruction:'Minta pasien membuka & menutup mata, kemudian mengepalkan & membuka tangan.',
        options:[
          {score:0,label:'Mampu melakukan 2 perintah dengan benar'},
          {score:1,label:'Mampu melakukan 1 perintah dengan benar'},
          {score:2,label:'Tidak mampu melakukan perintah sama sekali'}
        ]
      },
      {
        id:'2', code:'2', label:'Gaze — Gerakan Mata Konjugat Horizontal',
        instruction:'Nilai gerakan mata horizontal secara volunter atau refleks okulosefal.',
        options:[
          {score:0,label:'Normal'},
          {score:1,label:'Paresis parsial — gaze abnormal pada satu atau dua mata, tidak ada deviasi paksa'},
          {score:2,label:'Deviasi konjugat paksa atau paresis konjugat total yang tidak dapat dilawan'}
        ]
      },
      {
        id:'3', code:'3', label:'Visual — Lapang Pandang (Tes Konfrontasi)',
        instruction:'Nilai tiap kuadran visual. Jika pasien buta satu mata, nilai mata yang sehat.',
        van: 'V',
        vanIndikasi: 'Skor ≥ 1 pada item ini berkontribusi pada komponen V (Visual) dalam VAN Score. Gangguan lapang pandang seperti hemianopsia menunjukkan kemungkinan oklusi pembuluh darah besar (LVO).',
        options:[
          {score:0,label:'Tidak ada gangguan lapang pandang'},
          {score:1,label:'Kuadrantanopsia — kehilangan sebagian lapang pandang'},
          {score:2,label:'Hemianopsia total — kehilangan lapang pandang setengah sisi'},
          {score:3,label:'Hemianopsia bilateral atau buta kortikal total'}
        ]
      },
      {
        id:'4', code:'4', label:'Paresis Wajah',
        instruction:'Anjurkan pasien menyeringai atau mengangkat alis dan menutup mata kuat-kuat.',
        options:[
          {score:0,label:'Gerakan wajah normal dan simetris'},
          {score:1,label:'Paresis ringan — lipatan nasolabial sedikit datar, asimetri saat senyum'},
          {score:2,label:'Paresis parsial — kelumpuhan total atau hampir total wajah bawah'},
          {score:3,label:'Paresis total — tidak ada gerakan wajah atas dan bawah (sentral atau perifer)'}
        ]
      },
      {
        id:'5a', code:'5a', label:'Motorik Lengan Kiri',
        instruction:'Posisi berbaring: angkat 45°. Posisi duduk: angkat 90°. Hitung 10 detik.',
        motorik: true, motorikSide: 'Lengan Kiri',
        motorikNote: 'Terdapat drift atau kelemahan lengan kiri. Dokumentasikan dan pertimbangkan evaluasi lanjutan.',
        options:[
          {score:0,label:'Mampu menahan posisi penuh selama 10 detik — tidak ada drift'},
          {score:1,label:'Drift — lengan bertahan, namun jatuh sebelum 10 detik; tidak menyentuh tempat tidur'},
          {score:2,label:'Berupaya melawan gravitasi namun tidak mampu menahan posisi penuh'},
          {score:3,label:'Tidak ada upaya melawan gravitasi — lengan langsung jatuh; hanya bergeser di tempat tidur'},
          {score:4,label:'Tidak ada gerakan sama sekali'}
        ]
      },
      {
        id:'5b', code:'5b', label:'Motorik Lengan Kanan',
        instruction:'Posisi berbaring: angkat 45°. Posisi duduk: angkat 90°. Hitung 10 detik.',
        motorik: true, motorikSide: 'Lengan Kanan',
        motorikNote: 'Terdapat drift atau kelemahan lengan kanan. Dokumentasikan dan pertimbangkan evaluasi lanjutan.',
        options:[
          {score:0,label:'Mampu menahan posisi penuh selama 10 detik — tidak ada drift'},
          {score:1,label:'Drift — lengan bertahan, namun jatuh sebelum 10 detik; tidak menyentuh tempat tidur'},
          {score:2,label:'Berupaya melawan gravitasi namun tidak mampu menahan posisi penuh'},
          {score:3,label:'Tidak ada upaya melawan gravitasi — lengan langsung jatuh; hanya bergeser di tempat tidur'},
          {score:4,label:'Tidak ada gerakan sama sekali'}
        ]
      },
      {
        id:'6a', code:'6a', label:'Motorik Tungkai Kiri',
        instruction:'Pasien terlentang, angkat tungkai 30°. Hitung 5 detik.',
        motorik: true, motorikSide: 'Tungkai Kiri',
        motorikNote: 'Terdapat drift atau kelemahan tungkai kiri. Dokumentasikan dan pertimbangkan evaluasi lanjutan.',
        options:[
          {score:0,label:'Mampu menahan 30° selama minimal 5 detik — tidak ada drift'},
          {score:1,label:'Tungkai jatuh perlahan sebelum akhir 5 detik; tidak menyentuh tempat tidur'},
          {score:2,label:'Tungkai jatuh ke tempat tidur dalam 5 detik, namun ada upaya melawan gravitasi'},
          {score:3,label:'Tidak mampu melawan gravitasi — tungkai langsung jatuh ke tempat tidur'},
          {score:4,label:'Tidak ada gerakan sama sekali'}
        ]
      },
      {
        id:'6b', code:'6b', label:'Motorik Tungkai Kanan',
        instruction:'Pasien terlentang, angkat tungkai 30°. Hitung 5 detik.',
        motorik: true, motorikSide: 'Tungkai Kanan',
        motorikNote: 'Terdapat drift atau kelemahan tungkai kanan. Dokumentasikan dan pertimbangkan evaluasi lanjutan.',
        options:[
          {score:0,label:'Mampu menahan 30° selama minimal 5 detik — tidak ada drift'},
          {score:1,label:'Tungkai jatuh perlahan sebelum akhir 5 detik; tidak menyentuh tempat tidur'},
          {score:2,label:'Tungkai jatuh ke tempat tidur dalam 5 detik, namun ada upaya melawan gravitasi'},
          {score:3,label:'Tidak mampu melawan gravitasi — tungkai langsung jatuh ke tempat tidur'},
          {score:4,label:'Tidak ada gerakan sama sekali'}
        ]
      },
      {
        id:'7', code:'7', label:'Ataksia Anggota Badan',
        instruction:'Lakukan tes jari-hidung-jari dan tumit-lutut.',
        options:[
          {score:0,label:'Tidak ada ataksia'},
          {score:1,label:'Ataksia pada satu ekstremitas'},
          {score:2,label:'Ataksia pada dua ekstremitas atau lebih'}
        ]
      },
      {
        id:'8', code:'8', label:'Sensorik',
        instruction:'Uji dengan jarum pada tungkai, lengan, badan, dan wajah.',
        options:[
          {score:0,label:'Normal — tidak ada gangguan sensorik'},
          {score:1,label:'Gangguan sensorik ringan-sedang — kurang peka terhadap nyeri tapi sadar bahwa disentuh'},
          {score:2,label:'Gangguan sensorik berat atau total — tidak sadar tersentuh pada wajah, lengan, dan tungkai'}
        ]
      },
      {
        id:'9', code:'9', label:'Kemampuan Berbahasa (Afasia)',
        instruction:'Minta pasien mendeskripsikan gambar, membaca kalimat, dan menyebutkan benda.',
        van: 'A',
        vanIndikasi: 'Skor ≥ 1 pada item ini merupakan komponen A (Aphasia) dalam VAN Score. Afasia berat menunjukkan kemungkinan keterlibatan MCA dan LVO.',
        options:[
          {score:0,label:'Normal — tidak ada afasia, komunikasi normal'},
          {score:1,label:'Afasia ringan–sedang — kesulitan menemukan kata, ada parafasia; dapat berkomunikasi'},
          {score:2,label:'Afasia berat — komunikasi fragmentar; pendengar harus banyak menginterpretasi'},
          {score:3,label:'Mutisme atau afasia global — tidak ada komunikasi verbal yang berarti; koma'}
        ]
      },
      {
        id:'10', code:'10', label:'Disartria',
        instruction:'Minta pasien membaca atau mengulang kata-kata.',
        options:[
          {score:0,label:'Artikulasi normal'},
          {score:1,label:'Disartria ringan–sedang — pelo, namun masih dapat dipahami'},
          {score:2,label:'Disartria berat — bicara tidak dapat dipahami, atau pasien anartria / intubasi'}
        ]
      },
      {
        id:'11', code:'11', label:'Neglect / Inatensi',
        instruction:'Nilai menggunakan stimulasi ganda simultan (visual dan taktil).',
        van: 'N',
        vanIndikasi: 'Skor ≥ 1 pada item ini merupakan komponen N (Neglect) dalam VAN Score. Neglect menunjukkan keterlibatan hemisfer dan memperkuat kecurigaan LVO.',
        options:[
          {score:0,label:'Tidak ada neglect — atensi normal ke semua sisi'},
          {score:1,label:'Inatensi atau ekstinksi pada salah satu modalitas (visual, taktil, auditori, atau spasial)'},
          {score:2,label:'Hemi-inatensi berat atau neglect pada lebih dari satu modalitas — tidak mengenali tangan sendiri'}
        ]
      },
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
      if (this.tdnAnswers[id]) {
        const tdn = { ...this.tdnAnswers }
        delete tdn[id]
        this.tdnAnswers = tdn
      }
      if (this.showErrors && this.answeredCount === 15) this.showErrors = false
    },

    setTDN(id) {
      if (this.tdnAnswers[id]) {
        const tdn = { ...this.tdnAnswers }
        delete tdn[id]
        this.tdnAnswers = tdn
      } else {
        const ans = { ...this.answers }
        delete ans[id]
        this.answers = ans
        this.tdnAnswers = { ...this.tdnAnswers, [id]: true }
      }
    },

    getVanKeterangan(van) {
      if (van === 'V') return 'Gangguan lapang pandang terdeteksi. Komponen Visual VAN Score positif — menunjukkan kemungkinan LVO.'
      if (van === 'A') return 'Gangguan bahasa terdeteksi. Komponen Aphasia VAN Score positif — menunjukkan keterlibatan MCA dan kemungkinan LVO.'
      if (van === 'N') return 'Neglect/inatensi terdeteksi. Komponen Neglect VAN Score positif — menunjukkan keterlibatan hemisfer dan kemungkinan LVO.'
      return ''
    },

    getMotorKeterangan(score) {
      if (score === 1) return 'Drift ringan — ada kelemahan, namun masih ada kontrol parsial.'
      if (score === 2) return 'Kelemahan sedang — dapat melawan gravitasi namun tidak penuh.'
      if (score === 3) return 'Kelemahan berat — tidak mampu melawan gravitasi.'
      if (score === 4) return 'Plegia — tidak ada gerakan sama sekali.'
      return ''
    },

    get totalScore() {
      let total = 0
      for (const q of this.questions) {
        const idx = this.answers[q.id]
        if (idx !== undefined) total += q.options[idx].score
      }
      return total
    },

    get answeredCount() {
      let count = 0
      for (const q of this.questions) {
        if (this.answers[q.id] !== undefined || this.tdnAnswers[q.id] === true) count++
      }
      return count
    },

    get tdnCount() {
      return Object.keys(this.tdnAnswers).length
    },

    get vanSummary() {
      const vanItems = { V: '3', A: '9', N: '11' }
      return ['V','A','N'].map(key => {
        const qid = vanItems[key]
        const q = this.questions.find(q => q.id === qid)
        if (!q) return { key, positive: false }
        const idx = this.answers[qid]
        const score = idx !== undefined ? q.options[idx].score : 0
        return { key, positive: score >= 1 }
      })
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
      if (cat === 'Ringan')       return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
      if (cat === 'Sedang')       return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
      if (cat === 'Berat')        return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400'
      if (cat === 'Sangat Berat') return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
      return this.darkMode ? 'bg-gray-800 text-gray-500' : 'bg-gray-100 text-gray-400'
    },

    getCategoryColor(cat) {
      if (cat === 'Ringan')       return '#16a34a'
      if (cat === 'Sedang')       return '#ca8a04'
      if (cat === 'Berat')        return '#ea580c'
      if (cat === 'Sangat Berat') return '#dc2626'
      return '#94a3b8'
    },

    getCategoryFromScore(score) {
      if (score < 5)   return 'Ringan'
      if (score <= 14) return 'Sedang'
      if (score <= 24) return 'Berat'
      return 'Sangat Berat'
    },

    trySave() {
      if (this.answeredCount >= 15) { this.saveResult(); return }
      this.showErrors = true
      this.$nextTick(() => {
        const firstEmpty = this.questions.find(q => this.answers[q.id] === undefined && !this.tdnAnswers[q.id])
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

      const details = this.questions.map(q => {
        const isTDN = this.tdnAnswers[q.id] === true
        if (isTDN) {
          const note = (this.tdnNotes[q.id] || '').trim()
          return {
            code: q.code, label: q.label,
            answer: note ? 'TDN — ' + note : 'Tidak Dapat Dinilai (TDN)',
            tdnNote: note, score: null,
            maxScore: q.options[q.options.length - 1].score,
            isTDN: true,
            van: q.van || null,
            motorik: q.motorik || false,
          }
        }
        return {
          code: q.code, label: q.label,
          answer: q.options[this.answers[q.id]].label,
          score: q.options[this.answers[q.id]].score,
          maxScore: q.options[q.options.length - 1].score,
          isTDN: false,
          van: q.van || null,
          motorik: q.motorik || false,
        }
      })

      const tdnCountSaved = details.filter(d => d.isTDN).length
      const total = this.totalScore
      const category = this.getCategoryFromScore(total)

      const vanMap = { V: '3', A: '9', N: '11' }
      const vanPositif = []
      for (const [key, qid] of Object.entries(vanMap)) {
        const d = details.find(d => d.code === qid)
        if (d && !d.isTDN && d.score >= 1) vanPositif.push(key)
      }

      this.history.push({ datetime, total, category, details, tdnCount: tdnCountSaved, vanPositif })
      this.saveHistory()
      this.answers = {}; this.tdnAnswers = {}; this.tdnNotes = {}; this.showErrors = false
      this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }) })
    },

    resetAll() { this.answers = {}; this.tdnAnswers = {}; this.tdnNotes = {}; this.showErrors = false },

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
      const scores = item.details.map(d => d.isTDN ? 0 : d.score)
      const maxes  = item.details.map(d => d.maxScore || 4)
      const colors = item.details.map((d, i) => {
        if (d.isTDN)   return '#3b82f6'
        if (d.van)     return '#3b82f6'
        if (d.motorik) return '#3b82f6'
        const s = d.score
        return s === 0 ? '#22c55e' : s === maxes[i] ? '#ef4444' : s >= 2 ? '#f97316' : '#3b82f6'
      })
      this.chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: item.details.map(d => d.code),
          datasets: [{ data: scores, backgroundColor: colors, borderRadius: 5, borderSkipped: false }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: c => {
                  const d = item.details[c.dataIndex]
                  if (d.isTDN) return 'Tidak Dapat Dinilai (TDN)'
                  let label = 'Skor: ' + c.raw + ' / ' + maxes[c.dataIndex]
                  if (d.van) label += '  [VAN: ' + d.van + ']'
                  if (d.motorik) label += '  [Motorik]'
                  return label
                }
              }
            }
          },
          scales: {
            x: { ticks: { color: lc, font: { size: 10, family: 'IBM Plex Mono' } }, grid: { color: gc } },
            y: { min: 0, max: 4, ticks: { color: lc, font: { size: 10 }, stepSize: 1 }, grid: { color: gc } }
          }
        }
      })
    },

    async downloadPDF() {
      const item = this.selectedItem
      if (!item) return
      this.pdfLoading = true
      await new Promise(r => setTimeout(r, 50))

      try {
        if (!window.jspdf) throw new Error('jsPDF belum dimuat')
        const { jsPDF } = window.jspdf
        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' })

        const pageW = 210, pageH = 297
        const mL = 15, mR = 15
        const cW = pageW - mL - mR
        let y = 0

        const blue   = [37, 99, 235]
        const lightB = [219, 234, 254]
        const gray   = [100, 116, 139]
        const dark   = [30, 41, 59]
        const red    = [220, 38, 38]
        const lightR = [254, 226, 226]
        const green  = [22, 163, 74]
        const orange = [234, 88, 12]

        const catColors = {
          'Ringan':       [22,163,74],
          'Sedang':       [202,138,4],
          'Berat':        [234,88,12],
          'Sangat Berat': [220,38,38]
        }
        const catRgb = catColors[item.category] || gray

        const checkNewPage = (need) => {
          if (y + need > pageH - 15) { doc.addPage(); y = 15 }
        }
        const fillRect = (x, fy, w, h, r, g, b) => {
          doc.setFillColor(r, g, b); doc.rect(x, fy, w, h, 'F')
        }
        const drawLine = (x1, fy, x2) => {
          doc.setDrawColor(226, 232, 240); doc.setLineWidth(0.2); doc.line(x1, fy, x2, fy)
        }

        fillRect(0, 0, pageW, 40, 37, 99, 235)
        doc.setFillColor(255,255,255)
        doc.roundedRect(mL, 11, 18, 18, 2, 2, 'F')
        doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(37,99,235)
        doc.text('NIHSS', mL+9, 22, { align:'center' })

        doc.setTextColor(255,255,255)
        doc.setFontSize(15); doc.setFont('helvetica','bold')
        doc.text('SMART NIHSS', mL+22, 19)
        doc.setFontSize(8); doc.setFont('helvetica','normal'); doc.setTextColor(186,210,255)
        doc.text('RSUP Fatmawati', mL+22, 25)
        doc.text('Laporan Pemeriksaan Stroke', mL+22, 31)
        doc.setFontSize(7.5); doc.setTextColor(186,210,255)
        doc.text(item.datetime, pageW-mR, 25, { align:'right' })
        y = 48

        doc.setFillColor(248,250,252); doc.setDrawColor(226,232,240); doc.setLineWidth(0.3)
        doc.roundedRect(mL, y, cW, 30, 4, 4, 'FD')

        doc.setFontSize(30); doc.setFont('helvetica','bold'); doc.setTextColor(...catRgb)
        doc.text(String(item.total), mL+20, y+20, { align:'center' })
        doc.setFontSize(10); doc.setFont('helvetica','normal'); doc.setTextColor(...gray)
        doc.text('/42', mL+32, y+20)
        doc.setFontSize(7); doc.setTextColor(...gray)
        doc.text('Total Skor NIHSS', mL+20, y+27, { align:'center' })

        let bx = mL+50, by = y+7
        const catLight = catRgb.map(c => Math.min(255, c+200))
        doc.setFillColor(...catLight); doc.roundedRect(bx, by, 38, 7, 2, 2, 'F')
        doc.setFontSize(8); doc.setFont('helvetica','bold'); doc.setTextColor(...catRgb)
        doc.text(item.category, bx+19, by+5, { align:'center' })
        bx += 42

        if (item.tdnCount > 0) {
          doc.setFillColor(...lightB); doc.roundedRect(bx, by, 26, 7, 2, 2, 'F')
          doc.setTextColor(...blue); doc.text(item.tdnCount+' TDN', bx+13, by+5, { align:'center' })
          bx += 30
        }

        if (item.vanPositif && item.vanPositif.length > 0) {
          doc.setFillColor(...lightR); doc.roundedRect(bx, by, 40, 7, 2, 2, 'F')
          doc.setTextColor(...red); doc.setFontSize(7.5)
          doc.text('⚠ Curiga LVO', bx+20, by+5, { align:'center' })
          by += 10; bx = mL+50
          doc.setFillColor(...lightR); doc.roundedRect(bx, by, 60, 7, 2, 2, 'F')
          doc.setTextColor(...red); doc.setFontSize(7)
          const vanLabels = item.vanPositif.map(v => v === 'V' ? 'Visual' : v === 'A' ? 'Aphasia' : 'Neglect').join(', ')
          doc.text('VAN+: ' + vanLabels, bx+30, by+5, { align:'center' })
        }

        const barX = mL+50, barY = y+24, barW = cW-50-8
        doc.setFillColor(226,232,240); doc.roundedRect(barX, barY, barW, 2.5, 1.25, 1.25, 'F')
        doc.setFillColor(...catRgb)
        doc.roundedRect(barX, barY, Math.min((item.total/42)*barW, barW), 2.5, 1.25, 1.25, 'F')
        y += 38

        if (item.vanPositif && item.vanPositif.length > 0) {
          checkNewPage(22)
          doc.setFillColor(...lightR); doc.setDrawColor(252,165,165); doc.setLineWidth(0.3)
          doc.roundedRect(mL, y, cW, 18, 3, 3, 'FD')
          doc.setFontSize(8); doc.setFont('helvetica','bold'); doc.setTextColor(...red)
          doc.text('⚠ Curiga Large Vessel Occlusion (LVO)', mL+5, y+6.5)
          doc.setFont('helvetica','normal'); doc.setFontSize(7); doc.setTextColor(153,27,27)
          const vanFull = item.vanPositif.map(v => v === 'V' ? 'Visual' : v === 'A' ? 'Aphasia' : 'Neglect').join(', ')
          const lvoText = 'Komponen VAN positif: ' + vanFull + '. Mengindikasikan kemungkinan oklusi pembuluh darah besar. Evaluasi lanjutan direkomendasikan.'
          const lvoLines = doc.splitTextToSize(lvoText, cW-10)
          doc.text(lvoLines, mL+5, y+12)
          y += 24
        }

        checkNewPage(12)
        doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(...gray)
        doc.text('KLASIFIKASI KEPARAHAN', mL, y); y += 5

        const sevData = [
          {label:'Ringan',      range:'0 - 4',   prog:'Baik',        rgb:[22,163,74]},
          {label:'Sedang',      range:'5 - 14',  prog:'Sedang',      rgb:[202,138,4]},
          {label:'Berat',       range:'15 - 24', prog:'Kurang baik', rgb:[234,88,12]},
          {label:'Sangat Berat',range:'25 - 42', prog:'Buruk',       rgb:[220,38,38]}
        ]
        fillRect(mL, y, cW, 6, 241,245,249)
        doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(...gray)
        doc.text('Kategori', mL+3, y+4.2)
        doc.text('Rentang', mL+48, y+4.2)
        doc.text('Prognosis', mL+80, y+4.2)
        doc.text('Status', pageW-mR-3, y+4.2, { align:'right' })
        y += 6

        sevData.forEach(row => {
          checkNewPage(8)
          const isA = item.category === row.label
          if (isA) { const lc = row.rgb.map(c=>Math.min(255,c+215)); fillRect(mL,y,cW,7,...lc) }
          drawLine(mL, y+7, mL+cW)
          doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(...row.rgb)
          doc.text(row.label, mL+3, y+4.8)
          doc.setFont('helvetica','normal'); doc.setTextColor(...dark)
          doc.text(row.range, mL+48, y+4.8)
          doc.text(row.prog, mL+80, y+4.8)
          if (isA) {
            doc.setFont('helvetica','bold'); doc.setTextColor(...row.rgb)
            doc.text('▶ Pasien ini', pageW-mR-3, y+4.8, { align:'right' })
          }
          y += 7
        })
        y += 8

        checkNewPage(12)
        doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(...gray)
        doc.text('RINCIAN 15 ITEM PEMERIKSAAN', mL, y); y += 5

        fillRect(mL, y, cW, 6, 241, 245, 249)
        doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(...gray)
        doc.text('Kode', mL+3, y+4.2)
        doc.text('Domain', mL+18, y+4.2)
        doc.text('Jawaban', mL+82, y+4.2)
        doc.text('Skor', pageW-mR-3, y+4.2, { align:'right' })
        y += 6

        item.details.forEach((d, i) => {
          const answerLines = d.isTDN
            ? (d.tdnNote ? doc.splitTextToSize('"' + d.tdnNote + '"', 55) : ['Tidak ada keterangan'])
            : doc.splitTextToSize(d.answer, 55)
          const hasExtra = (d.van && !d.isTDN && d.score >= 1) || (d.motorik && !d.isTDN && d.score >= 1)
          const baseH = Math.max(9, 5 + answerLines.length * 3.5)
          const rowH = baseH + (hasExtra ? 8 : 0)

          checkNewPage(rowH + 2)

          if (i % 2 === 0 && !d.isTDN) fillRect(mL, y, cW, rowH, 248, 250, 252)
          if (d.isTDN) fillRect(mL, y, cW, rowH, 239, 246, 255)
          drawLine(mL, y+rowH, mL+cW)

          doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(...blue)
          doc.text(d.code, mL+3, y+5.5)

          const labelLines = doc.splitTextToSize(d.label, 58)
          doc.setFont('helvetica','bold'); doc.setTextColor(...dark)
          doc.text(labelLines[0], mL+18, y+5.5)

          let badgeY2 = y + 9
          if (d.van) {
            doc.setFontSize(6); doc.setFont('helvetica','bold'); doc.setTextColor(...blue)
            doc.text('[VAN-'+d.van+']', mL+18, badgeY2); badgeY2 += 3.5
          }
          if (d.motorik) {
            doc.setFontSize(6); doc.setFont('helvetica','bold'); doc.setTextColor(...blue)
            doc.text('[Motorik]', mL+18, badgeY2)
          }

          if (d.isTDN) {
            doc.setFont('helvetica','bold'); doc.setFontSize(7); doc.setTextColor(...blue)
            doc.text('Tidak Dapat Dinilai', mL+82, y+5.5)
            if (d.tdnNote) {
              doc.setFont('helvetica','italic'); doc.setFontSize(6.5); doc.setTextColor(37,99,235)
              const noteLines = doc.splitTextToSize('"' + d.tdnNote + '"', 55)
              doc.text(noteLines.slice(0,2), mL+82, y+9.5)
            }
            doc.setFillColor(...lightB)
            doc.roundedRect(pageW-mR-13, y+2, 11, 5, 1, 1, 'F')
            doc.setFont('helvetica','bold'); doc.setFontSize(6.5); doc.setTextColor(...blue)
            doc.text('TDN', pageW-mR-7.5, y+5.8, { align:'center' })
          } else {
            doc.setFont('helvetica','normal'); doc.setFontSize(6.5); doc.setTextColor(...gray)
            doc.text(answerLines.slice(0,3), mL+82, y+5.5)

            const scColor = d.score === 0 ? green : d.score >= 3 ? red : d.score >= 2 ? orange : blue
            doc.setFont('helvetica','bold'); doc.setFontSize(10); doc.setTextColor(...scColor)
            doc.text(String(d.score), pageW-mR-3, y+6.5, { align:'right' })

            if (d.van && d.score >= 1) {
              const infoY = y + baseH
              fillRect(mL+2, infoY, cW-4, 6.5, 219, 234, 254)
              doc.setFont('helvetica','bold'); doc.setFontSize(6); doc.setTextColor(...blue)
              const vanTxt = 'VAN '+d.van+' Positif — '+this.getVanKeterangan(d.van).substring(0,80)
              doc.text(doc.splitTextToSize(vanTxt, cW-8)[0], mL+4, infoY+4.2)
            }
            if (d.motorik && d.score >= 1) {
              const infoY2 = y + baseH + ((d.van && d.score >= 1) ? 7 : 0)
              fillRect(mL+2, infoY2, cW-4, 6.5, 219, 234, 254)
              doc.setFont('helvetica','bold'); doc.setFontSize(6); doc.setTextColor(...blue)
              doc.text('Defisit Motorik — Skor '+d.score+': '+this.getMotorKeterangan(d.score), mL+4, infoY2+4.2)
            }
          }
          y += rowH
        })
        y += 10

        if (item.tdnCount > 0) {
          checkNewPage(18)
          doc.setFillColor(...lightB); doc.setDrawColor(147,197,253); doc.setLineWidth(0.3)
          doc.roundedRect(mL, y, cW, 14, 3, 3, 'FD')
          doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(...blue)
          doc.text('Catatan TDN:', mL+4, y+5.5)
          doc.setFont('helvetica','normal'); doc.setTextColor(30,64,175)
          doc.text(item.tdnCount+' item ditandai Tidak Dapat Dinilai (TDN) dan tidak dihitung dalam total skor.', mL+4, y+10.5)
          y += 18
        }

        if (item.vanPositif && item.vanPositif.length > 0) {
          checkNewPage(20)
          doc.setFillColor(...lightR); doc.setDrawColor(252,165,165); doc.setLineWidth(0.3)
          doc.roundedRect(mL, y, cW, 16, 3, 3, 'FD')
          doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(...red)
          doc.text('⚠ VAN Score Positif — Curiga LVO', mL+4, y+5.5)
          doc.setFont('helvetica','normal'); doc.setFontSize(6.5); doc.setTextColor(153,27,27)
          const vanFull = item.vanPositif.map(v => v === 'V' ? 'Visual' : v === 'A' ? 'Aphasia' : 'Neglect').join(', ')
          const lvoSummaryLines = doc.splitTextToSize('Komponen positif: ' + vanFull + '. VAN Score positif mengindikasikan kemungkinan oklusi pembuluh darah besar (LVO). Evaluasi lanjutan dan tindakan segera direkomendasikan.', cW-8)
          doc.text(lvoSummaryLines.slice(0,2), mL+4, y+10.5)
          y += 22
        }

        checkNewPage(10)
        drawLine(mL, y, pageW-mR); y += 5
        doc.setFontSize(7); doc.setFont('helvetica','normal'); doc.setTextColor(...gray)
        doc.text('Dibuat otomatis oleh SMART NIHSS — RSUP Fatmawati', mL, y)
        doc.text('Hal. 1', pageW-mR, y, { align:'right' })

        const ts = new Date().toISOString().slice(0,19).replace(/[T:]/g,'-')
        doc.save('NIHSS_Laporan_' + ts + '.pdf')

      } catch (err) {
        console.error('PDF error:', err)
        alert('Gagal membuat PDF: ' + err.message + '\nSilakan coba lagi atau refresh halaman.')
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