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

    /* Opsi "Tidak Dapat Dinilai" */
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

    /* VAN item header badge — pakai biru sesuai desain utama */
    .van-item-badge {
      background-color: rgba(59,130,246,0.1);
      color: #2563eb;
      font-family: 'IBM Plex Mono', monospace;
      font-size: 11px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 6px;
    }
    .dark .van-item-badge {
      background-color: rgba(59,130,246,0.15);
      color: #60a5fa;
    }
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
      <a href="{{ route('welcome-nihss') }}">
        <button class="h-9 w-20 rounded-md border border-slate-200 dark:border-[#30363d] bg-blue-500 dark:bg-[#1c2128] flex items-center justify-center text-white dark:text-slate-400 hover:border-blue-400 transition-colors">
          Beranda
        </button>
      </a>
    </div>
  </div>
</nav>

<main class="max-w-xl mx-auto px-4 pt-4 pb-52 space-y-3">

  <!-- Datetime -->
  <div class="card-bg border rounded-2xl px-4 py-3 flex items-center gap-2.5 transition-colors duration-300">
    <svg class="w-4 h-4 flex-shrink-0" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    <span class="text-xs font-bold font-mono" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="currentDatetime"></span>
    <span class="text-xs ml-auto font-mono" :class="darkMode ? 'text-gray-600' : 'text-gray-300'">WIB</span>
  </div>

  <!-- ══════════════════════════════════
       SKRINING VAN
  ══════════════════════════════════ -->
  <div class="card-bg border rounded-2xl overflow-hidden transition-all duration-300 fade-up">

    <div class="header-bg border-b px-4 py-3 flex items-center gap-2.5">
      <span class="w-6 h-6 rounded-lg bg-blue-600 text-white text-xs font-bold font-mono flex items-center justify-center flex-shrink-0">V</span>
      <div class="flex-1">
        <span class="text-sm font-extrabold" :class="darkMode ? 'text-white' : 'text-gray-900'">Skrining VAN</span>
        <span class="text-xs ml-2 font-mono" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Vision · Aphasia · Neglect</span>
      </div>
      <button @click="vanOpen = !vanOpen"
        class="flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg border transition-all"
        :class="darkMode ? 'border-gray-700 text-gray-400 hover:bg-gray-800' : 'border-gray-200 text-gray-500 hover:bg-gray-50'">
        <svg class="w-3 h-3 transition-transform duration-200" :class="vanOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        <span x-text="vanOpen ? 'Sembunyikan' : 'Tampilkan'"></span>
      </button>
    </div>

    <div x-show="vanOpen"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 -translate-y-1"
      x-transition:enter-end="opacity-100 translate-y-0">

      <div class="p-4 space-y-3">

        <p class="text-xs leading-relaxed" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">
          Skrining cepat pra-NIHSS untuk mendeteksi <strong>Large Vessel Occlusion (LVO)</strong>. Satu item positif = VAN Positif, pertimbangkan aktivasi jalur intervensi segera.
        </p>

        <!-- 3 item VAN -->
        <template x-for="(v, vi) in vanItems" :key="v.id">
          <div class="rounded-xl border overflow-hidden" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">

            <!-- Header item -->
            <div class="flex items-center gap-2.5 px-3 py-2.5 border-b"
              :class="darkMode ? 'bg-gray-800/50 border-gray-700' : 'bg-gray-50 border-gray-200'">
              <span class="van-item-badge" x-text="v.id"></span>
              <span class="text-sm font-bold" :class="darkMode ? 'text-white' : 'text-gray-900'" x-text="v.title"></span>
            </div>

            <p class="text-xs px-3 pt-2 pb-1 leading-relaxed font-bold"
              :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
              x-text="v.instruction"></p>

            <div class="px-3 pb-3 space-y-1.5">
              <template x-for="(opt, oi) in v.opts" :key="oi">
                <label class="opt-label flex items-center gap-3 px-3 py-3 rounded-xl border"
                  :class="[vanAnswers[v.id] === oi ? 'selected' : '', darkMode ? 'border-gray-700' : 'border-gray-200']"
                  @click="setVAN(v.id, oi, opt.val)">
                  <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all"
                    :class="vanAnswers[v.id] === oi ? 'border-blue-500 bg-blue-500' : (darkMode ? 'border-gray-600' : 'border-gray-300')">
                    <div x-show="vanAnswers[v.id] === oi" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>
                  <div class="flex-1 flex items-center justify-between gap-2">
                    <span class="text-sm leading-snug" x-text="opt.label"></span>
                    <span class="text-xs font-mono font-semibold flex-shrink-0 transition-colors"
                      :class="vanAnswers[v.id] === oi ? 'text-blue-500' : (darkMode ? 'text-gray-600' : 'text-gray-300')"
                      x-text="opt.yn"></span>
                  </div>
                </label>
              </template>
            </div>
          </div>
        </template>

        <!-- Progress VAN -->
        <div x-show="vanAnsweredCount > 0" class="flex items-center gap-2.5">
          <span class="text-xs font-mono" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="vanAnsweredCount + '/3 dijawab'"></span>
          <div class="flex-1 h-1.5 rounded-full overflow-hidden" :class="darkMode ? 'bg-gray-800' : 'bg-gray-200'">
            <div class="h-full bg-blue-500 rounded-full transition-all duration-500" :style="'width:' + (vanAnsweredCount/3*100) + '%'"></div>
          </div>
          <button @click="resetVAN()"
            class="text-xs font-semibold transition-colors"
            :class="darkMode ? 'text-gray-600 hover:text-gray-400' : 'text-gray-300 hover:text-gray-500'">
            Reset VAN
          </button>
        </div>

        <!-- Hasil VAN -->
        <div x-show="vanAnsweredCount === 3"
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0 translate-y-1"
          x-transition:enter-end="opacity-100 translate-y-0"
          class="rounded-xl border px-4 py-3"
          :class="vanPositive
            ? (darkMode ? 'border-red-900 bg-red-950/30' : 'border-red-200 bg-red-50')
            : (darkMode ? 'border-blue-900 bg-blue-950/20' : 'border-blue-200 bg-blue-50')">

          <div class="flex items-start gap-2 mb-1">
            <!-- Ikon -->
            <svg x-show="vanPositive" class="w-4 h-4 flex-shrink-0 mt-0.5" :class="darkMode ? 'text-red-400' : 'text-red-600'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <svg x-show="!vanPositive" class="w-4 h-4 flex-shrink-0 mt-0.5" :class="darkMode ? 'text-blue-400' : 'text-blue-600'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-bold"
              :class="vanPositive ? (darkMode ? 'text-red-400' : 'text-red-700') : (darkMode ? 'text-blue-400' : 'text-blue-700')"
              x-text="vanPositive ? 'VAN Positif — Suspek Large Vessel Occlusion (LVO)' : 'VAN Negatif — Risiko LVO rendah'">
            </p>
          </div>

          <p class="text-xs leading-relaxed"
            :class="vanPositive ? (darkMode ? 'text-red-500' : 'text-red-600') : (darkMode ? 'text-blue-500' : 'text-blue-700')"
            x-text="vanPositive
              ? 'Ditemukan ' + vanPositiveCount + ' item positif (' + vanPositiveFlags + '). Pertimbangkan aktivasi jalur stroke segera: CT Angiografi & konsultasi intervensi.'
              : 'Tidak ditemukan tanda Vision, Aphasia, atau Neglect. Lanjutkan penilaian NIHSS penuh.'">
          </p>
        </div>

      </div>
    </div>
  </div>
  <!-- ══ END VAN ══ -->

  <!-- ══════════════════════════════════
       15 ITEM NIHSS
  ══════════════════════════════════ -->
  <template x-for="(section, si) in questions" :key="si">
    <div class="card-bg border rounded-2xl overflow-hidden transition-all duration-300 fade-up"
      :style="`animation-delay:${si * 30}ms`"
      :class="[showErrors && answers[section.id] === undefined && !tdnAnswers[section.id] ? 'card-error' : '']"
      :id="'section-' + section.id">

      <div class="header-bg border-b px-4 py-3 flex items-center gap-2.5">
        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white text-xs font-bold font-mono flex items-center justify-center flex-shrink-0" x-text="section.code"></span>
        <span class="text-sm font-extrabold flex-1" x-text="section.label"></span>
        <span x-show="showErrors && answers[section.id] === undefined && !tdnAnswers[section.id]" class="error-badge">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
          Wajib diisi
        </span>
      </div>

      <div class="p-4 space-y-2">
        <p x-show="section.instruction" class="text-xs font-bold leading-relaxed mb-1" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="section.instruction"></p>

        <!-- Opsi normal -->
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

        <!-- Opsi Tidak Dapat Dinilai (TDN) -->
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

          <!-- Input keterangan TDN -->
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
                @click.stop
                @keydown.stop
                rows="3"
                placeholder="Tuliskan alasan di sini, misal: pasien tidak kooperatif, intubasi, trauma, sedasi, dsb..."
                class="w-full px-3 py-2.5 text-xs leading-relaxed resize-none outline-none bg-transparent placeholder-opacity-50 transition-colors"
                :class="darkMode
                  ? 'text-blue-200 placeholder-blue-700 focus:placeholder-blue-600'
                  : 'text-blue-900 placeholder-blue-300 focus:placeholder-blue-400'"
              ></textarea>
              <div class="px-3 pb-2 flex items-center justify-between">
                <p class="text-xs" :class="darkMode ? 'text-blue-700' : 'text-blue-300'">Skor tidak dihitung dalam total NIHSS</p>
                <span class="text-xs font-mono"
                  :class="(tdnNotes[section.id] || '').length > 200
                    ? 'text-red-400'
                    : (darkMode ? 'text-blue-700' : 'text-blue-300')"
                  x-text="(tdnNotes[section.id] || '').length + '/250'">
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </template>

  <!-- Riwayat Pemeriksaan -->
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
          <!-- Badge VAN di riwayat -->
          <div class="flex items-center gap-1.5 mt-1">
            <span x-show="item.vanResult && item.vanResult.answered === 3"
              class="text-xs font-mono font-bold px-1.5 py-0.5 rounded-md"
              :class="item.vanResult && item.vanResult.positive
                ? (darkMode ? 'bg-red-900/40 text-red-400' : 'bg-red-100 text-red-600')
                : (darkMode ? 'bg-blue-900/30 text-blue-400' : 'bg-blue-100 text-blue-600')"
              x-text="item.vanResult && item.vanResult.positive ? 'VAN (+)' : 'VAN (−)'">
            </span>
          </div>
          <p class="text-xs mt-1" :class="darkMode ? 'text-gray-500' : 'text-gray-400'" x-text="item.datetime"></p>
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

<!-- Fixed Bottom Score Bar -->
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
          <p x-show="tdnCount > 0" class="text-xs font-mono" :class="darkMode ? 'text-blue-400' : 'text-blue-200'" x-text="tdnCount + ' item TDN'"></p>
          <!-- Info VAN di bottom bar -->
          <p x-show="vanAnsweredCount === 3" class="text-xs font-mono"
            :class="vanPositive ? 'text-red-300' : (darkMode ? 'text-blue-400' : 'text-blue-200')"
            x-text="vanPositive ? 'VAN Positif (LVO)' : 'VAN Negatif'"></p>
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

<!-- ══ DETAIL MODAL ══ -->
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
          <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
            :class="selectedItem ? getCategoryBadge(selectedItem.category) : ''"
            x-text="selectedItem?.category"></span>
          <span x-show="selectedItem && selectedItem.tdnCount > 0"
            class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
            x-text="selectedItem?.tdnCount + ' TDN'"></span>
          <!-- Badge VAN di modal -->
          <span x-show="selectedItem && selectedItem.vanResult && selectedItem.vanResult.answered === 3"
            class="px-2 py-0.5 rounded-full text-xs font-semibold"
            :class="selectedItem && selectedItem.vanResult && selectedItem.vanResult.positive
              ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
              : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'"
            x-text="selectedItem && selectedItem.vanResult && selectedItem.vanResult.positive ? 'VAN Positif' : 'VAN Negatif'">
          </span>
        </div>
      </div>
      <button @click="showModal = false"
        class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors"
        :class="darkMode ? 'text-gray-500 hover:bg-gray-800' : 'text-gray-400 hover:bg-gray-100'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="px-5 py-4 space-y-4">

      <!-- Ringkasan VAN di modal -->
      <div x-show="selectedItem && selectedItem.vanResult && selectedItem.vanResult.answered === 3"
        class="rounded-2xl border px-4 py-3"
        :class="selectedItem && selectedItem.vanResult && selectedItem.vanResult.positive
          ? (darkMode ? 'border-red-900 bg-red-950/20' : 'border-red-200 bg-red-50')
          : (darkMode ? 'border-blue-900 bg-blue-950/10' : 'border-blue-200 bg-blue-50')">
        <p class="text-xs font-semibold font-mono uppercase tracking-widest mb-2"
          :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Skrining VAN</p>
        <p class="text-sm font-bold mb-1"
          :class="selectedItem && selectedItem.vanResult && selectedItem.vanResult.positive
            ? (darkMode ? 'text-red-400' : 'text-red-700')
            : (darkMode ? 'text-blue-400' : 'text-blue-700')"
          x-text="selectedItem && selectedItem.vanResult && selectedItem.vanResult.positive
            ? 'VAN Positif — Suspek LVO'
            : 'VAN Negatif — Risiko LVO rendah'">
        </p>
        <div class="flex gap-3">
          <template x-for="vk in ['V','A','N']" :key="vk">
            <div class="flex items-center gap-1.5">
              <span class="van-item-badge" x-text="vk"></span>
              <span class="text-xs font-mono font-semibold"
                :class="selectedItem && selectedItem.vanResult && selectedItem.vanResult[vk] === true
                  ? 'text-red-500'
                  : (darkMode ? 'text-gray-400' : 'text-gray-500')"
                x-text="selectedItem && selectedItem.vanResult && selectedItem.vanResult[vk] === true ? 'Ya' : 'Tidak'">
              </span>
            </div>
          </template>
          <span x-show="selectedItem && selectedItem.vanResult && selectedItem.vanResult.positive"
            class="text-xs font-mono"
            :class="darkMode ? 'text-red-400' : 'text-red-600'"
            x-text="'Positif: ' + (selectedItem && selectedItem.vanResult ? selectedItem.vanResult.flags : '')">
          </span>
        </div>
      </div>

      <!-- Chart -->
      <div class="rounded-2xl p-4 transition-colors" :class="darkMode ? 'bg-gray-800/50' : 'bg-gray-50'">
        <p class="text-xs font-semibold font-mono uppercase tracking-widest mb-3" :class="darkMode ? 'text-gray-500' : 'text-gray-400'">Profil Skor per Domain</p>
        <div style="position:relative;height:200px">
          <canvas id="detailChart" role="img" aria-label="Grafik skor NIHSS per domain"></canvas>
        </div>
      </div>

      <!-- Tabel klasifikasi -->
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

      <!-- Rincian 15 item -->
      <div>
        <p class="text-xs font-semibold font-mono uppercase tracking-widest mb-3" :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Rincian 15 Item</p>
        <div class="space-y-0">
          <template x-for="(detail, di) in selectedItem?.details" :key="detail.code">
            <div class="flex items-start justify-between py-3 border-b last:border-0"
              :class="darkMode ? 'border-gray-800' : 'border-gray-100'">
              <div class="flex-1 pr-4">
                <span class="text-xs font-mono font-bold text-blue-500" x-text="detail.code"></span>
                <p class="text-sm font-semibold mt-0.5" x-text="detail.label"></p>
                <p x-show="!detail.isTDN" class="text-xs mt-0.5 leading-snug"
                  :class="darkMode ? 'text-gray-500' : 'text-gray-400'"
                  x-text="detail.answer"></p>
                <div x-show="detail.isTDN" class="mt-1 space-y-0.5">
                  <span class="text-xs font-bold" :class="darkMode ? 'text-blue-400' : 'text-blue-600'">Tidak Dapat Dinilai</span>
                  <p x-show="detail.tdnNote" class="text-xs leading-snug italic"
                    :class="darkMode ? 'text-blue-500' : 'text-blue-500'"
                    x-text="'\u201c' + detail.tdnNote + '\u201d'"></p>
                  <p x-show="!detail.tdnNote" class="text-xs leading-snug"
                    :class="darkMode ? 'text-gray-600' : 'text-gray-400'">Tidak ada keterangan</p>
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
    tdnAnswers: {},
    tdnNotes: {},
    history: [],
    showModal: false,
    selectedItem: null,
    currentDatetime: '',
    chartInstance: null,
    showErrors: false,
    pdfLoading: false,

    // ── VAN ──
    vanOpen: true,
    vanAnswers: {},
    vanValues: {},

    vanItems: [
      {
        id: 'V',
        title: 'Vision — Gangguan penglihatan',
        instruction: 'Tanya: "Apakah penglihatan Anda berubah atau ada yang hilang?" Amati adanya hemianopia atau deviasi pandang.',
        opts: [
          { label: 'Tidak ada gangguan penglihatan', yn: 'Tidak', val: false },
          { label: 'Ada gangguan penglihatan (hemianopia, buta sebagian/total)', yn: 'Ya', val: true },
        ]
      },
      {
        id: 'A',
        title: 'Aphasia — Gangguan bahasa',
        instruction: 'Minta pasien menyebutkan nama benda atau mengikuti perintah 2 langkah sederhana.',
        opts: [
          { label: 'Dapat berbicara dan memahami dengan normal', yn: 'Tidak', val: false },
          { label: 'Ada kesulitan bicara, menemukan kata, atau memahami instruksi', yn: 'Ya', val: true },
        ]
      },
      {
        id: 'N',
        title: 'Neglect — Inatensi satu sisi',
        instruction: 'Stimulasi kedua sisi tubuh bersamaan (sentuhan/gerakan). Apakah pasien mengabaikan satu sisi?',
        opts: [
          { label: 'Merespons stimulasi bilateral secara normal', yn: 'Tidak', val: false },
          { label: 'Mengabaikan atau tidak merespons salah satu sisi tubuh', yn: 'Ya', val: true },
        ]
      },
    ],

    severityRows: [
      { label: 'Ringan',       range: '0 - 4',   prognosis: 'Baik',         color: '#22c55e' },
      { label: 'Sedang',       range: '5 - 14',  prognosis: 'Sedang',       color: '#eab308' },
      { label: 'Berat',        range: '15 - 24', prognosis: 'Kurang baik',  color: '#f97316' },
      { label: 'Sangat Berat', range: '25 - 42', prognosis: 'Buruk',        color: '#ef4444' },
    ],

    questions: [
      { id:'1a', code:'1a', label:'Tingkat Kesadaran', instruction:'Nilai kesadaran pasien secara umum.',
        options:[{score:0,label:'Sadar penuh'},{score:1,label:'Somnolen'},{score:2,label:'Stupor'},{score:3,label:'Koma'}] },
      { id:'1b', code:'1b', label:'Menjawab Pertanyaan', instruction:'Tanyakan bulan dan usia pasien. Yang dinilai adalah jawaban pertama, pemeriksa tidak diperkenankan membantu pasien dengan verbal atau non verbal',
        options:[{score:0,label:'Benar semua (2 pertanyaan)'},{score:1,label:'1 benar / ETT / disartria'},{score:2,label:'Salah semua / afasia / stupor / koma'}] },
      { id:'1c', code:'1c', label:'Mengikuti Perintah', instruction:'Berikan 2 perintah sederhana, membuka dan menutup mata, menggenggam tangan dan melepaskannya atau 2 perintah lain',
        options:[{score:0,label:'Mampu melakukan 2 perintah'},{score:1,label:'Mampu melakukan 1 perintah'},{score:2,label:'Tidak mampu melakukan perintah'}] },
      { id:'2', code:'2', label:'Gaze : gerakan mata konyugat horizontal', instruction:'',
        options:[{score:0,label:'Normal'},{score:1,label:'Abnormal pada 1 mata'},{score:2,label:'Deviasi konjugat kuat atau paresis konjugat pada 2 mata'}] },
      { id:'3', code:'3', label:'Visual : lapang pandang pada tes konfrontasi', instruction:'Tes konfrontasi lapang pandang.',
        options:[{score:0,label:'Tidak ada gangguan'},{score:1,label:'Kuadrianopsia'},{score:2,label:'Hemianopia total'},{score:3,label:'Hemianopia bilateral / buta kortikal'}] },
      { id:'4', code:'4', label:'Paresis wajah', instruction:'Anjurkan pasien menyeringai atau mengangkat alis dan menutup mata.',
        options:[{score:0,label:'Normal'},{score:1,label:'Paresis wajah ringan'},{score:2,label:'Paresis wajah parsial'},{score:3,label:'Paresis wajah total'}] },
      { id:'5a', code:'5a', label:'Motorik Lengan Kiri', instruction:'Anjurkan pasien mengangkat lengan hingga 45 derajat bila tidur berbaring atau 90 derajat bila posisi duduk. Bila pasien afasia berikan perintah menggunakan pantomime atau peragaan',
        options:[{score:0,label:'Mampu mengangkat lengan minimal 10 detik'},{score:1,label:'Lengan terjatuh sebelum 10 detik'},{score:2,label:'Tidak mampu mengangkat secara penuh'},{score:3,label:'Tidak mampu mengangkat, hanya bergeser'},{score:4,label:'Tidak ada gerakan'}] },
      { id:'5b', code:'5b', label:'Motorik Lengan Kanan', instruction:'Anjurkan pasien mengangkat lengan hingga 45 derajat bila tidur berbaring atau 90 derajat bila posisi duduk. Bila pasien afasia berikan perintah menggunakan pantomime atau peragaan',
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

    // ── VAN methods ──
    setVAN(id, optIdx, val) {
      this.vanAnswers = { ...this.vanAnswers, [id]: optIdx }
      this.vanValues  = { ...this.vanValues,  [id]: val }
    },

    get vanAnsweredCount() {
      return ['V','A','N'].filter(k => this.vanAnswers[k] !== undefined).length
    },

    get vanPositive() {
      return ['V','A','N'].some(k => this.vanValues[k] === true)
    },

    get vanPositiveCount() {
      return ['V','A','N'].filter(k => this.vanValues[k] === true).length
    },

    get vanPositiveFlags() {
      const map = { V: 'Vision', A: 'Aphasia', N: 'Neglect' }
      return ['V','A','N'].filter(k => this.vanValues[k] === true).map(k => map[k]).join(', ')
    },

    resetVAN() {
      this.vanAnswers = {}
      this.vanValues  = {}
    },

    // ── NIHSS methods ──
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
          return { code: q.code, label: q.label, answer: note ? 'TDN — ' + note : 'Tidak Dapat Dinilai (TDN)', tdnNote: note, score: null, maxScore: q.options[q.options.length - 1].score, isTDN: true }
        }
        return { code: q.code, label: q.label, answer: q.options[this.answers[q.id]].label, score: q.options[this.answers[q.id]].score, maxScore: q.options[q.options.length - 1].score, isTDN: false }
      })

      const tdnCountSaved = details.filter(d => d.isTDN).length

      // Simpan hasil VAN
      const vanResult = {
        answered: this.vanAnsweredCount,
        positive: this.vanPositive,
        flags: this.vanPositiveFlags,
        V: this.vanValues['V'] ?? null,
        A: this.vanValues['A'] ?? null,
        N: this.vanValues['N'] ?? null,
      }

      this.history.push({ datetime, total: this.totalScore, category: this.categoryLabel, details, tdnCount: tdnCountSaved, vanResult })
      this.saveHistory()
      this.answers = {}
      this.tdnAnswers = {}
      this.tdnNotes = {}
      this.showErrors = false
      this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }) })
    },

    resetAll() {
      this.answers = {}
      this.tdnAnswers = {}
      this.tdnNotes = {}
      this.showErrors = false
      this.resetVAN()
    },

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
        if (d.isTDN) return '#3b82f6'
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
                  return d.isTDN ? 'Tidak Dapat Dinilai (TDN)' : 'Skor: ' + c.raw + ' / ' + maxes[c.dataIndex]
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

    // ── PDF ──
    async downloadPDF() {
      const item = this.selectedItem
      if (!item) return
      this.pdfLoading = true
      await this.$nextTick()

      try {
        const { jsPDF } = window.jspdf
        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' })

        const pageW = 210, pageH = 297, marginL = 18, marginR = 18
        const contentW = pageW - marginL - marginR
        let y = 0

        const catColors = {
          'Ringan':      [22,163,74], 'Sedang': [202,138,4],
          'Berat':       [234,88,12], 'Sangat Berat': [220,38,38],
        }
        const catRgb = catColors[item.category] || [100,116,139]
        const blueRgb = [37,99,235]

        // Header biru
        doc.setFillColor(37,99,235)
        doc.rect(0,0,pageW,38,'F')
        doc.setFillColor(255,255,255)
        doc.roundedRect(marginL,10,18,18,3,3,'F')
        doc.setFontSize(8); doc.setFont('helvetica','bold'); doc.setTextColor(37,99,235)
        doc.text('NIHSS', marginL+9, 21, { align:'center' })
        doc.setTextColor(255,255,255)
        doc.setFontSize(15); doc.setFont('helvetica','bold')
        doc.text('SMART NIHSS', marginL+22, 18)
        doc.setFontSize(8); doc.setFont('helvetica','normal'); doc.setTextColor(186,210,255)
        doc.text('RSUP Fatmawati', marginL+22, 25)
        doc.text('Laporan Pemeriksaan Stroke', marginL+22, 31)
        doc.text(item.datetime, pageW-marginR, 25, { align:'right' })

        y = 50

        // Kotak skor utama
        doc.setFillColor(248,250,252); doc.setDrawColor(226,232,240); doc.setLineWidth(0.3)
        doc.roundedRect(marginL, y, contentW, 28, 4, 4, 'FD')
        doc.setFontSize(32); doc.setFont('helvetica','bold'); doc.setTextColor(...catRgb)
        doc.text(String(item.total), marginL+22, y+20, { align:'center' })
        doc.setFontSize(10); doc.setTextColor(148,163,184)
        doc.text('/42', marginL+34, y+20)

        const badgeX = marginL+55, badgeY = y+8
        doc.setFillColor(...catRgb.map(c => Math.min(255,c+200)))
        doc.roundedRect(badgeX, badgeY, 38, 8, 2, 2, 'F')
        doc.setFontSize(8); doc.setFont('helvetica','bold'); doc.setTextColor(...catRgb)
        doc.text(item.category, badgeX+19, badgeY+5.5, { align:'center' })

        if (item.tdnCount > 0) {
          const tdnBX = badgeX+42
          doc.setFillColor(219,234,254)
          doc.roundedRect(tdnBX, badgeY, 28, 8, 2, 2, 'F')
          doc.setFontSize(8); doc.setFont('helvetica','bold'); doc.setTextColor(...blueRgb)
          doc.text(item.tdnCount + ' TDN', tdnBX+14, badgeY+5.5, { align:'center' })
        }

        // Badge VAN di PDF
        if (item.vanResult && item.vanResult.answered === 3) {
          const vanBX = badgeX + (item.tdnCount > 0 ? 74 : 42)
          const vanRgb = item.vanResult.positive ? [220,38,38] : [37,99,235]
          const vanBgRgb = item.vanResult.positive ? [254,226,226] : [219,234,254]
          doc.setFillColor(...vanBgRgb)
          doc.roundedRect(vanBX, badgeY, 28, 8, 2, 2, 'F')
          doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(...vanRgb)
          doc.text(item.vanResult.positive ? 'VAN (+)' : 'VAN (−)', vanBX+14, badgeY+5.5, { align:'center' })
        }

        doc.setFontSize(7); doc.setFont('helvetica','normal'); doc.setTextColor(100,116,139)
        doc.text('Total Skor NIHSS', marginL+22, y+26, { align:'center' })

        const barX = badgeX, barY = y+18, barW = contentW-(badgeX-marginL)-8
        doc.setFillColor(226,232,240); doc.roundedRect(barX,barY,barW,3,1.5,1.5,'F')
        doc.setFillColor(...catRgb); doc.roundedRect(barX,barY,Math.min((item.total/42)*barW,barW),3,1.5,1.5,'F')

        y += 36

        // ── Blok VAN di PDF ──
        if (item.vanResult && item.vanResult.answered === 3) {
          const vanRgb  = item.vanResult.positive ? [220,38,38]  : [37,99,235]
          const vanBgRgb = item.vanResult.positive ? [254,242,242] : [239,246,255]
          const vanBorderRgb = item.vanResult.positive ? [252,165,165] : [147,197,253]

          doc.setFillColor(...vanBgRgb)
          doc.setDrawColor(...vanBorderRgb)
          doc.setLineWidth(0.3)
          doc.roundedRect(marginL, y, contentW, 18, 3, 3, 'FD')

          doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(...vanRgb)
          doc.text(
            item.vanResult.positive ? 'VAN Positif — Suspek Large Vessel Occlusion (LVO)' : 'VAN Negatif — Risiko LVO rendah',
            marginL+4, y+6.5
          )

          doc.setFont('helvetica','normal'); doc.setFontSize(7); doc.setTextColor(...vanRgb.map(c => Math.min(255, c+40)))
          const vanDesc = item.vanResult.positive
            ? 'Positif: ' + item.vanResult.flags + '. Pertimbangkan CT Angiografi & konsultasi intervensi segera.'
            : 'Tidak ditemukan tanda Vision, Aphasia, atau Neglect.'
          doc.text(vanDesc, marginL+4, y+11.5)

          // 3 chip V/A/N
          const chipLabels = ['V — Vision','A — Aphasia','N — Neglect']
          const chipKeys   = ['V','A','N']
          chipKeys.forEach((k, i) => {
            const cx = marginL+4 + i*46
            const isPos = item.vanResult[k] === true
            doc.setFillColor(...(isPos ? [220,38,38] : [37,99,235]).map(c => Math.min(255,c+210)))
            doc.roundedRect(cx, y+13.5, 42, 4, 1, 1, 'F')
            doc.setFont('helvetica','bold'); doc.setFontSize(6.5)
            doc.setTextColor(...(isPos ? [220,38,38] : [37,99,235]))
            doc.text(chipLabels[i] + ': ' + (isPos ? 'Ya' : 'Tidak'), cx+21, y+16.5, { align:'center' })
          })

          y += 24
        }

        // Klasifikasi keparahan
        doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(100,116,139)
        doc.text('KLASIFIKASI KEPARAHAN', marginL, y)
        y += 5

        const sevData = [
          { label:'Ringan',       range:'0 - 4',   prognosis:'Baik',         rgb:[22,163,74]   },
          { label:'Sedang',       range:'5 - 14',  prognosis:'Sedang',       rgb:[202,138,4]   },
          { label:'Berat',        range:'15 - 24', prognosis:'Kurang baik',  rgb:[234,88,12]   },
          { label:'Sangat Berat', range:'25 - 42', prognosis:'Buruk',        rgb:[220,38,38]   },
        ]

        doc.setFillColor(241,245,249); doc.rect(marginL,y,contentW,6,'F')
        doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(100,116,139)
        doc.text('Kategori', marginL+3, y+4.2)
        doc.text('Rentang', marginL+48, y+4.2)
        doc.text('Prognosis', marginL+80, y+4.2)
        doc.text('Status', pageW-marginR-3, y+4.2, { align:'right' })
        y += 6

        sevData.forEach(row => {
          const isActive = item.category === row.label
          if (isActive) { doc.setFillColor(...row.rgb.map(c => Math.min(255,c+215))); doc.rect(marginL,y,contentW,7,'F') }
          doc.setDrawColor(226,232,240); doc.setLineWidth(0.2); doc.line(marginL,y+7,marginL+contentW,y+7)
          doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(...row.rgb)
          doc.text(row.label, marginL+3, y+4.8)
          doc.setFont('helvetica','normal'); doc.setTextColor(71,85,105)
          doc.text(row.range, marginL+48, y+4.8)
          doc.text(row.prognosis, marginL+80, y+4.8)
          if (isActive) { doc.setFont('helvetica','bold'); doc.setTextColor(...row.rgb); doc.text('Pasien ini', pageW-marginR-3, y+4.8, { align:'right' }) }
          y += 7
        })

        y += 8

        // Rincian 15 item
        doc.setFontSize(7.5); doc.setFont('helvetica','bold'); doc.setTextColor(100,116,139)
        doc.text('RINCIAN 15 ITEM PEMERIKSAAN', marginL, y)
        y += 5

        doc.setFillColor(241,245,249); doc.rect(marginL,y,contentW,6,'F')
        doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(100,116,139)
        doc.text('Kode', marginL+3, y+4.2)
        doc.text('Domain', marginL+18, y+4.2)
        doc.text('Jawaban', marginL+85, y+4.2)
        doc.text('Skor', pageW-marginR-3, y+4.2, { align:'right' })
        y += 6

        item.details.forEach((d, i) => {
          if (y > pageH - 40) { doc.addPage(); y = 20 }
          const hasNote = d.isTDN && d.tdnNote
          const rowH = hasNote ? 14 : 9

          if (i % 2 === 0 && !d.isTDN) { doc.setFillColor(248,250,252); doc.rect(marginL,y,contentW,rowH,'F') }
          if (d.isTDN) { doc.setFillColor(239,246,255); doc.rect(marginL,y,contentW,rowH,'F') }

          doc.setDrawColor(226,232,240); doc.setLineWidth(0.15); doc.line(marginL,y+rowH,marginL+contentW,y+rowH)

          doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(37,99,235)
          doc.text(d.code, marginL+3, y+5.8)

          doc.setFont('helvetica','bold'); doc.setTextColor(30,41,59)
          doc.text(doc.splitTextToSize(d.label, 62)[0], marginL+18, y+5.8)

          if (d.isTDN) {
            doc.setFont('helvetica','bold'); doc.setFontSize(7); doc.setTextColor(...blueRgb)
            doc.text('Tidak Dapat Dinilai', marginL+85, y+5.8)
            if (hasNote) {
              doc.setFont('helvetica','italic'); doc.setFontSize(6.5); doc.setTextColor(37,99,235)
              doc.text(doc.splitTextToSize('\u201c' + d.tdnNote + '\u201d', 60)[0], marginL+85, y+10.5)
            }
            doc.setFillColor(219,234,254)
            doc.roundedRect(pageW-marginR-12, y+2, 10, 5, 1, 1, 'F')
            doc.setFont('helvetica','bold'); doc.setFontSize(6.5); doc.setTextColor(...blueRgb)
            doc.text('TDN', pageW-marginR-7, y+5.8, { align:'center' })
          } else {
            doc.setFont('helvetica','normal'); doc.setFontSize(7); doc.setTextColor(71,85,105)
            doc.text(doc.splitTextToSize(d.answer, 55)[0], marginL+85, y+5.8)
            const scoreRgb = d.score === 0 ? [22,163,74] : d.score >= 3 ? [220,38,38] : [234,88,12]
            doc.setFont('helvetica','bold'); doc.setTextColor(...scoreRgb)
            doc.text(String(d.score), pageW-marginR-3, y+5.8, { align:'right' })
          }
          y += rowH
        })

        y += 10

        // Catatan TDN
        if (item.tdnCount > 0) {
          if (y > pageH - 30) { doc.addPage(); y = 20 }
          doc.setFillColor(239,246,255); doc.setDrawColor(147,197,253); doc.setLineWidth(0.3)
          doc.roundedRect(marginL,y,contentW,14,3,3,'FD')
          doc.setFontSize(7); doc.setFont('helvetica','bold'); doc.setTextColor(...blueRgb)
          doc.text('Catatan TDN:', marginL+4, y+5.5)
          doc.setFont('helvetica','normal'); doc.setTextColor(29,78,216)
          doc.text(item.tdnCount + ' item ditandai Tidak Dapat Dinilai (TDN) dan tidak dihitung dalam total skor.', marginL+4, y+10.5)
          y += 20
        }

        // Footer
        if (y > pageH - 20) { doc.addPage(); y = 20 }
        doc.setDrawColor(226,232,240); doc.setLineWidth(0.3); doc.line(marginL,y,pageW-marginR,y); y += 5
        doc.setFontSize(7); doc.setFont('helvetica','normal'); doc.setTextColor(148,163,184)
        doc.text('Dibuat otomatis oleh SMART NIHSS - RSUP Fatmawati', marginL, y)
        doc.text('Halaman 1', pageW-marginR, y, { align:'right' })

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