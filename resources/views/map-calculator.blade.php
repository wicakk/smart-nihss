<!DOCTYPE html>
<html lang="id" class="light" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MAP Calculator — RSUP Fatmawati</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        fontFamily: {
          sans: ['DM Sans', 'sans-serif'],
          mono: ['DM Mono', 'monospace'],
          serif: ['DM Serif Display', 'serif'],
        },
      }
    }
  }
</script>
<style>
  /* Result color classes — Tailwind purge workaround */
  .result-empty  { background-color: #1d4ed8; }
  .result-normal { background-color: #0d7b5e; }
  .result-below  { background-color: #854d0e; }
  .result-above  { background-color: #7c3aed; }
  .result-danger { background-color: #991b1b; }

  /* Input number hide arrows */
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; }
  input[type=number] { -moz-appearance: textfield; }

  /* Smooth theme transition */
  body { transition: background-color 0.2s, color 0.2s; }

  /* Print styles */
  @media print {
    body { background: #fff !important; padding-bottom: 0 !important; }
    .no-print { display: none !important; }
    .tab-content { display: block !important; }
    #tab-formula, #tab-referensi { page-break-before: always; display: block !important; }
    .result-area { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .formula-block { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .sticky { position: static !important; }
  }
</style>
</head>

<body class="font-sans bg-slate-100 dark:bg-[#0d1117] text-slate-900 dark:text-[#e6edf3] min-h-screen pb-36 transition-colors">

<!-- ══ TOPBAR ══ -->
<header class="sticky top-0 z-50 bg-white dark:bg-[#161b22] border-b border-slate-200 dark:border-[#30363d] px-5 py-3 flex items-center justify-between no-print">
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-700 to-blue-500 flex items-center justify-center shrink-0">
      <svg class="w-5 h-5 stroke-white fill-none" stroke-width="1.8" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
      </svg>
    </div>
    <div>
      <p class="text-sm font-semibold text-slate-900 dark:text-[#e6edf3] leading-tight">MAP Calculator</p>
      <p class="text-[11px] text-slate-400 dark:text-[#484f58] leading-tight mt-0.5">RSUP Fatmawati</p>
    </div>
  </div>
  <button onclick="toggleTheme()" id="theme-btn" title="Toggle tema"
    class="w-9 h-9 rounded-full border border-slate-200 dark:border-[#30363d] bg-slate-50 dark:bg-[#1c2128] flex items-center justify-center text-slate-500 dark:text-slate-400 hover:border-blue-400 transition-colors">
    <svg id="icon-moon" class="w-4 h-4 stroke-current fill-none" stroke-width="1.8" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
    <svg id="icon-sun" class="w-4 h-4 stroke-current fill-none hidden" stroke-width="1.8" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="5"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
    </svg>
  </button>
</header>

<!-- ══ DATETIME BAR ══ -->
<div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-xl px-4 py-2.5 flex items-center justify-between">
  <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-[#8b949e]">
    <svg class="w-3.5 h-3.5 stroke-slate-400 dark:stroke-[#484f58] fill-none shrink-0" stroke-width="1.8" viewBox="0 0 24 24">
      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    <span id="datetime-str" class="font-mono text-[11px]">—</span>
  </div>
  <span class="text-[10px] font-semibold tracking-widest text-slate-400 dark:text-[#484f58]">WIB</span>
</div>

<!-- ══ TABS ══ -->
{{-- <div class="mx-4 mt-3 bg-slate-100 dark:bg-[#1c2128] border border-slate-200 dark:border-[#30363d] rounded-xl p-1 flex gap-0.5 no-print">
  <button class="tab flex-1 py-2 text-xs font-medium rounded-lg transition-all text-blue-600 dark:text-blue-400 bg-white dark:bg-[#1c2128] shadow-sm"
    onclick="switchTab('calc',this)" data-tab="calc">Kalkulator</button>
  <button class="tab flex-1 py-2 text-xs font-medium rounded-lg transition-all text-slate-500 dark:text-[#6e7681]"
    onclick="switchTab('formula',this)" data-tab="formula">Formula</button>
  <button class="tab flex-1 py-2 text-xs font-medium rounded-lg transition-all text-slate-500 dark:text-[#6e7681]"
    onclick="switchTab('referensi',this)" data-tab="referensi">Referensi</button>
</div> --}}

<!-- ══════════════════════════ TAB: KALKULATOR ══════════════════════════ -->
<div id="tab-calc" class="tab-content">

  <!-- SBP Card -->
  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-sm">
    <div class="px-4 py-3.5 flex items-center gap-3 border-b border-slate-100 dark:border-[#30363d]">
      <div class="w-8 h-8 rounded-lg bg-blue-600 dark:bg-blue-500 flex items-center justify-center shrink-0">
        <span class="font-mono text-[11px] font-semibold text-white">SBP</span>
      </div>
      <div>
        <p class="text-sm font-medium text-slate-800 dark:text-[#e6edf3]">Systolic Blood Pressure</p>
        <p class="text-[11px] text-slate-400 dark:text-[#484f58] mt-0.5">Tekanan puncak saat jantung berkontraksi</p>
      </div>
    </div>
    <div class="px-4 py-3.5">
      <label class="block text-xs text-slate-500 dark:text-[#8b949e] mb-2" for="sbp">Masukkan nilai sistolik (normal: 100–120 mmHg)</label>
      <div id="sbp-wrap" class="flex border-[1.5px] border-slate-200 dark:border-[#30363d] rounded-xl overflow-hidden bg-white dark:bg-[#0d1117] transition-all focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/10 dark:focus-within:border-blue-400 dark:focus-within:ring-blue-400/10">
        <input type="number" id="sbp" min="50" max="300" placeholder="Contoh: 120" oninput="calculate()"
          class="flex-1 border-0 outline-none px-4 py-3 text-[17px] font-mono bg-transparent text-slate-800 dark:text-[#e6edf3] placeholder:font-sans placeholder:text-sm placeholder:text-slate-300 dark:placeholder:text-[#484f58]">
        <div class="px-4 flex items-center bg-slate-50 dark:bg-[#1c2128] border-l border-slate-200 dark:border-[#30363d] text-xs font-medium text-slate-500 dark:text-[#6e7681] whitespace-nowrap">
          mm Hg
        </div>
      </div>
    </div>
  </div>

  <!-- DBP + Result Card -->
  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-sm">
    <div class="px-4 py-3.5 flex items-center gap-3 border-b border-slate-100 dark:border-[#30363d]">
      <div class="w-8 h-8 rounded-lg bg-blue-600 dark:bg-blue-500 flex items-center justify-center shrink-0">
        <span class="font-mono text-[11px] font-semibold text-white">DBP</span>
      </div>
      <div>
        <p class="text-sm font-medium text-slate-800 dark:text-[#e6edf3]">Diastolic Blood Pressure</p>
        <p class="text-[11px] text-slate-400 dark:text-[#484f58] mt-0.5">Tekanan saat jantung berelaksasi</p>
      </div>
    </div>
    <div class="px-4 py-3.5 border-b border-slate-100 dark:border-[#30363d]">
      <label class="block text-xs text-slate-500 dark:text-[#8b949e] mb-2" for="dbp">Masukkan nilai diastolik (normal: 60–80 mmHg)</label>
      <div class="flex border-[1.5px] border-slate-200 dark:border-[#30363d] rounded-xl overflow-hidden bg-white dark:bg-[#0d1117] transition-all focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/10 dark:focus-within:border-blue-400 dark:focus-within:ring-blue-400/10">
        <input type="number" id="dbp" min="30" max="200" placeholder="Contoh: 80" oninput="calculate()"
          class="flex-1 border-0 outline-none px-4 py-3 text-[17px] font-mono bg-transparent text-slate-800 dark:text-[#e6edf3] placeholder:font-sans placeholder:text-sm placeholder:text-slate-300 dark:placeholder:text-[#484f58]">
        <div class="px-4 flex items-center bg-slate-50 dark:bg-[#1c2128] border-l border-slate-200 dark:border-[#30363d] text-xs font-medium text-slate-500 dark:text-[#6e7681] whitespace-nowrap">
          mm Hg
        </div>
      </div>
    </div>
    <!-- Result Area -->
    <div id="result-area" class="result-area result-empty transition-colors duration-300">
      <div class="px-5 py-5">
        <p class="text-[10px] font-semibold tracking-[0.12em] uppercase text-white/60 mb-2.5">Hasil MAP</p>
        <div id="result-body">
          <span class="text-[13px] text-white/50">Isi nilai SBP dan DBP untuk menghitung.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Interpretasi Card -->
  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-sm">
    <div class="px-4 pt-4 pb-1">
      <p class="text-[10px] font-semibold tracking-[0.1em] uppercase text-slate-400 dark:text-[#484f58] mb-3">Tabel Interpretasi MAP</p>
      <table class="w-full">
        <thead>
          <tr class="border-b border-slate-100 dark:border-[#30363d]">
            <th class="text-left text-[11px] font-medium text-slate-400 dark:text-[#484f58] pb-2">Kategori</th>
            <th class="text-right text-[11px] font-medium text-slate-400 dark:text-[#484f58] pb-2">Rentang</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-[#30363d]">
          <tr>
            <td class="py-2.5 text-sm text-slate-700 dark:text-[#e6edf3] flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>Kritis rendah
            </td>
            <td class="py-2.5 text-right font-mono text-xs text-slate-500 dark:text-[#8b949e]">&lt; 60 mmHg</td>
          </tr>
          <tr>
            <td class="py-2.5 text-sm text-slate-700 dark:text-[#e6edf3] flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>Di bawah normal
            </td>
            <td class="py-2.5 text-right font-mono text-xs text-slate-500 dark:text-[#8b949e]">60–69 mmHg</td>
          </tr>
          <tr>
            <td class="py-2.5 text-sm text-slate-700 dark:text-[#e6edf3] flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>Normal
            </td>
            <td class="py-2.5 text-right font-mono text-xs text-slate-500 dark:text-[#8b949e]">70–100 mmHg</td>
          </tr>
          <tr>
            <td class="py-2.5 text-sm text-slate-700 dark:text-[#e6edf3] flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-violet-500 shrink-0"></span>Di atas normal
            </td>
            <td class="py-2.5 text-right font-mono text-xs text-slate-500 dark:text-[#8b949e]">101–109 mmHg</td>
          </tr>
          <tr>
            <td class="py-2.5 text-sm text-slate-700 dark:text-[#e6edf3] flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-red-600 shrink-0"></span>Hipertensi berat
            </td>
            <td class="py-2.5 text-right font-mono text-xs text-slate-500 dark:text-[#8b949e]">≥ 110 mmHg</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tips Klinis Card -->
  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-sm">
    <div class="px-4 pt-4 pb-2">
      <p class="text-[10px] font-semibold tracking-[0.1em] uppercase text-slate-400 dark:text-[#484f58] mb-3">Tips Klinis</p>
      <div class="divide-y divide-slate-100 dark:divide-[#30363d]">
        <div class="flex gap-3 py-3 first:pt-0 items-start">
          <div class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-[#1c2128] flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5 stroke-blue-500 fill-none" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <p class="text-xs text-slate-500 dark:text-[#8b949e] leading-relaxed pt-1">Target MAP ≥ 65 mmHg pada syok sepsis (Surviving Sepsis Campaign 2016).</p>
        </div>
        <div class="flex gap-3 py-3 items-start">
          <div class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-[#1c2128] flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5 stroke-blue-500 fill-none" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          </div>
          <p class="text-xs text-slate-500 dark:text-[#8b949e] leading-relaxed pt-1">Perfusi serebral optimal pada MAP 70–90 mmHg. Di bawah 60 mmHg berisiko iskemia otak.</p>
        </div>
        <div class="flex gap-3 py-3 items-start">
          <div class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-[#1c2128] flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5 stroke-blue-500 fill-none" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
          </div>
          <p class="text-xs text-slate-500 dark:text-[#8b949e] leading-relaxed pt-1">Vasopressor (Norepinefrin) lini pertama jika MAP &lt; 65 mmHg pada sepsis.</p>
        </div>
        <div class="flex gap-3 py-3 items-start">
          <div class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-[#1c2128] flex items-center justify-center shrink-0">
            <svg class="w-3.5 h-3.5 stroke-blue-500 fill-none" stroke-width="1.8" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <p class="text-xs text-slate-500 dark:text-[#8b949e] leading-relaxed pt-1">Pulse pressure = SBP − DBP. Nilai &lt; 25 mmHg dapat mengindikasikan syok kardiogenik.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Disclaimer -->
  {{-- <div class="mx-4 mt-3 bg-amber-50 dark:bg-[#1c1500] border border-amber-200 dark:border-amber-900 rounded-xl px-4 py-3 flex gap-2.5 items-start">
    <svg class="w-4 h-4 stroke-amber-500 fill-none shrink-0 mt-0.5" stroke-width="1.8" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <span class="text-xs text-amber-800 dark:text-amber-400 leading-relaxed">Kalkulator ini hanya sebagai alat bantu klinis. Hasil harus selalu diverifikasi ulang dan tidak menggantikan penilaian klinis profesional.</span>
  </div> --}}

</div><!-- /tab-calc -->

<!-- ══════════════════════════ TAB: FORMULA ══════════════════════════ -->
<div id="tab-formula" class="tab-content hidden">

  <!-- Code block — always dark -->
  <div class="mx-4 mt-3 bg-[#0d1117] border border-[#30363d] rounded-2xl p-5 font-mono text-sm leading-loose">
    <p class="text-[#6e7681]">// Rumus standar Mean Arterial Pressure</p>
    <p class="mt-2">
      <span class="text-[#79c0ff]">MAP</span>
      <span class="text-[#ffa657]"> = </span>
      <span class="text-[#79c0ff]">DBP</span>
      <span class="text-[#ffa657]"> + ⅓ × ( </span>
      <span class="text-[#79c0ff]">SBP</span>
      <span class="text-[#ffa657]"> − </span>
      <span class="text-[#79c0ff]">DBP</span>
      <span class="text-[#ffa657]"> )</span>
    </p>
    <p class="mt-1 text-[#6e7681]">// Ekuivalen:</p>
    <p>
      <span class="text-[#79c0ff]">MAP</span>
      <span class="text-[#ffa657]"> = ( </span>
      <span class="text-[#79c0ff]">SBP</span>
      <span class="text-[#ffa657]"> + </span>
      <span class="text-[#7ee787]">2</span>
      <span class="text-[#ffa657]"> × </span>
      <span class="text-[#79c0ff]">DBP</span>
      <span class="text-[#ffa657]"> ) ÷ </span>
      <span class="text-[#7ee787]">3</span>
    </p>
  </div>

  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl p-4 space-y-3 shadow-sm">
    <p class="text-sm text-slate-600 dark:text-[#8b949e] leading-relaxed">
      <span class="font-medium text-slate-800 dark:text-[#e6edf3]">Mengapa ⅓ sistolik dan ⅔ diastolik?</span>
      Jantung menghabiskan sekitar sepertiga siklus jantung dalam fase sistol (kontraksi) dan dua pertiga dalam fase diastol (relaksasi).
    </p>
    <p class="text-sm text-slate-600 dark:text-[#8b949e] leading-relaxed">
      Rumus ini merupakan <span class="font-medium text-slate-800 dark:text-[#e6edf3]">pendekatan klinis</span> — nilai MAP sesungguhnya bisa diukur secara invasif melalui kateter arteri, namun formula ini terbukti akurat secara klinis.
    </p>
    <p class="text-sm text-slate-600 dark:text-[#8b949e] leading-relaxed">
      <span class="font-medium text-slate-800 dark:text-[#e6edf3]">Contoh:</span> SBP 120 mmHg, DBP 80 mmHg → MAP = 80 + ⅓ × 40 = <span class="font-semibold text-slate-800 dark:text-[#e6edf3]">93 mmHg</span>
    </p>
  </div>

  <div class="mx-4 mt-3 bg-amber-50 dark:bg-[#1c1500] border border-amber-200 dark:border-amber-900 rounded-xl px-4 py-3 flex gap-2.5 items-start">
    <svg class="w-4 h-4 stroke-amber-500 fill-none shrink-0 mt-0.5" stroke-width="1.8" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <span class="text-xs text-amber-800 dark:text-amber-400 leading-relaxed">Paling akurat pada detak jantung normal (60–100 bpm). Pada takikardia berat, rasio sistol/diastol berubah dan formula ini mungkin sedikit menyimpang.</span>
  </div>

</div><!-- /tab-formula -->

<!-- ══════════════════════════ TAB: REFERENSI ══════════════════════════ -->
<div id="tab-referensi" class="tab-content hidden">

  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl p-4 shadow-sm space-y-3">
    <p class="font-serif text-base text-slate-800 dark:text-[#e6edf3]">Signifikansi Klinis</p>
    <p class="text-sm text-slate-600 dark:text-[#8b949e] leading-relaxed">MAP ≥ 65 mmHg umumnya dianggap sebagai target minimum untuk memastikan perfusi organ adekuat pada pasien dewasa, terutama dalam tatalaksana syok sepsis.</p>
    <p class="text-sm text-slate-600 dark:text-[#8b949e] leading-relaxed">Pada cedera otak traumatik, target MAP seringkali ditingkatkan menjadi ≥ 80 mmHg untuk menjaga tekanan perfusi serebral.</p>
  </div>

  <div class="mx-4 mt-3 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl p-4 shadow-sm space-y-3">
    <p class="font-serif text-base text-slate-800 dark:text-[#e6edf3]">Referensi Utama</p>
    <div class="bg-slate-50 dark:bg-[#1c2128] rounded-xl p-3.5 space-y-1">
      <p class="text-xs text-slate-600 dark:text-[#8b949e] leading-relaxed">Rhodes A, et al. <em>Surviving Sepsis Campaign: International Guidelines for Management of Sepsis and Septic Shock 2016.</em> Critical Care Medicine. 2017;45(3):486–552.</p>
      <a href="https://doi.org/10.1097/CCM.0000000000002255" target="_blank" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">doi:10.1097/CCM.0000000000002255</a>
    </div>
    <div class="bg-slate-50 dark:bg-[#1c2128] rounded-xl p-3.5 space-y-1">
      <p class="text-xs text-slate-600 dark:text-[#8b949e] leading-relaxed">Magder S. <em>The meaning of blood pressure.</em> Critical Care. 2018;22(1):257.</p>
      <p class="text-[11px] italic text-slate-400 dark:text-[#484f58]">Pembahasan mendalam tentang interpretasi MAP secara fisiologis.</p>
    </div>
  </div>

</div><!-- /tab-referensi -->

<!-- ══ BOTTOM BAR ══ -->
<div class="no-print fixed bottom-0 left-0 right-0 z-40 bg-blue-800 dark:bg-[#0f2254] border-t border-white/10 px-4 pt-3.5 pb-6 shadow-[0_-4px_24px_rgba(0,0,0,0.2)]">
  <div class="flex items-start justify-between mb-3">
    <div>
      <p class="text-[10px] font-semibold tracking-[0.12em] uppercase text-white/50 mb-1">Hasil MAP =</p>
      <div class="flex items-baseline gap-1">
        <span id="score-display" class="font-mono text-3xl font-medium text-white leading-none">—</span>
        <span id="unit-display" class="text-sm text-white/40"></span>
      </div>
    </div>
    <span id="status-pill" class="text-[11px] font-semibold px-3.5 py-1.5 rounded-full bg-white/15 text-white tracking-wide">Belum Dihitung</span>
  </div>
  <div class="flex gap-2.5">
    <button onclick="resetCalc()"
      class="flex-none px-5 py-3 rounded-xl bg-white/15 hover:bg-white/20 text-white text-sm font-medium transition-colors active:scale-[0.98]">
      Reset
    </button>
    <button id="pdf-btn" onclick="downloadPDF()" disabled
      class="flex-1 py-3 rounded-xl bg-white text-blue-800 text-sm font-medium flex items-center justify-center gap-1.5 transition-all hover:bg-blue-50 active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed">
      <svg class="w-4 h-4 stroke-blue-800 fill-none" stroke-width="1.8" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      Unduh PDF
    </button>
  </div>
</div>

<script>
// ── DATETIME ──
function pad(n){return String(n).padStart(2,'0');}
function updateTime(){
  const d=new Date();
  const days=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const months=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  document.getElementById('datetime-str').textContent=`${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()} ${pad(d.getHours())}.${pad(d.getMinutes())}.${pad(d.getSeconds())}`;
}
updateTime(); setInterval(updateTime,1000);

// ── THEME ──
function toggleTheme(){
  const html=document.documentElement;
  const isDark=html.classList.contains('dark');
  html.classList.toggle('dark',!isDark);
  document.getElementById('icon-moon').classList.toggle('hidden',!isDark);
  document.getElementById('icon-sun').classList.toggle('hidden',isDark);
}

// ── TABS ──
function switchTab(name,btn){
  document.querySelectorAll('.tab-content').forEach(el=>{
    el.classList.add('hidden'); el.style.display='';
  });
  document.querySelectorAll('.tab').forEach(b=>{
    b.classList.remove('text-blue-600','dark:text-blue-400','bg-white','dark:bg-[#1c2128]','shadow-sm');
    b.classList.add('text-slate-500','dark:text-[#6e7681]');
  });
  document.getElementById('tab-'+name).classList.remove('hidden');
  btn.classList.remove('text-slate-500','dark:text-[#6e7681]');
  btn.classList.add('text-blue-600','dark:text-blue-400','bg-white','dark:bg-[#1c2128]','shadow-sm');
}

// ── CALCULATE ──
function calculate(){
  const sbpEl=document.getElementById('sbp');
  const dbpEl=document.getElementById('dbp');
  const sbp=parseFloat(sbpEl.value);
  const dbp=parseFloat(dbpEl.value);
  const ra=document.getElementById('result-area');
  const rb=document.getElementById('result-body');
  const scoreEl=document.getElementById('score-display');
  const unitEl=document.getElementById('unit-display');
  const pillEl=document.getElementById('status-pill');
  const pdfBtn=document.getElementById('pdf-btn');

  if(sbpEl.value===''||dbpEl.value===''||isNaN(sbp)||isNaN(dbp)){
    ra.className='result-area result-empty transition-colors duration-300';
    rb.innerHTML='<span class="text-[13px] text-white/50">Isi nilai SBP dan DBP untuk menghitung.</span>';
    scoreEl.textContent='—'; unitEl.textContent=''; pillEl.textContent='Belum Dihitung'; pdfBtn.disabled=true; return;
  }
  if(sbp<=dbp){
    ra.className='result-area result-danger transition-colors duration-300';
    rb.innerHTML='<span class="text-[13px] text-white/80">⚠ Sistolik harus lebih besar dari diastolik.</span>';
    scoreEl.textContent='ERR'; unitEl.textContent=''; pillEl.textContent='Input Tidak Valid'; pdfBtn.disabled=true; return;
  }

  const map=Math.round(dbp+(1/3)*(sbp-dbp));
  let cls,cat,desc;
  if(map<60){cls='result-danger';cat='Kritis Rendah';desc='Perfusi organ sangat terganggu. Diperlukan intervensi segera — pertimbangkan resusitasi cairan dan vasopressor.';}
  else if(map<70){cls='result-below';cat='Di Bawah Normal';desc='Pantau tanda hipoperfusi organ (oliguria, perubahan kesadaran). Target MAP ≥ 65 mmHg pada pasien kritis.';}
  else if(map<=100){cls='result-normal';cat='Normal';desc='Perfusi organ umumnya adekuat. Lanjutkan pemantauan rutin sesuai kondisi klinis pasien.';}
  else if(map<110){cls='result-above';cat='Di Atas Normal';desc='Pertimbangkan evaluasi hipertensi dan dampaknya terhadap organ target (jantung, ginjal, otak).';}
  else{cls='result-danger';cat='Hipertensi Berat';desc='Risiko kerusakan organ target meningkat signifikan. Tatalaksana hipertensi segera diperlukan.';}

  ra.className='result-area '+cls+' transition-colors duration-300';
  rb.innerHTML=`
    <div class="flex items-baseline gap-1.5">
      <span style="font-family:'DM Mono',monospace;font-size:52px;font-weight:500;color:#fff;line-height:1">${map}</span>
      <span style="font-size:16px;color:rgba(255,255,255,0.5)">mm Hg</span>
    </div>
    <span style="display:inline-block;margin-top:10px;background:rgba(255,255,255,0.2);color:#fff;font-size:11px;font-weight:600;padding:4px 14px;border-radius:9999px;letter-spacing:0.04em">${cat}</span>
    <p style="font-size:13px;color:rgba(255,255,255,0.7);margin-top:10px;line-height:1.55;max-width:360px">${desc}</p>
  `;
  scoreEl.textContent=map; unitEl.textContent=' mmHg'; pillEl.textContent=cat; pdfBtn.disabled=false;
}

// ── RESET ──
function resetCalc(){
  document.getElementById('sbp').value='';
  document.getElementById('dbp').value='';
  calculate();
}

// ── DOWNLOAD PDF ──
function downloadPDF(){
  document.querySelectorAll('.tab-content').forEach(el=>{ el.classList.remove('hidden'); el.style.display='block'; });
  const orig=document.title;
  document.title='MAP Calculator — Hasil Pemeriksaan';
  window.print();
  document.title=orig;
  setTimeout(()=>{
    document.querySelectorAll('.tab-content').forEach(el=>{ el.style.display=''; el.classList.add('hidden'); });
    document.getElementById('tab-calc').classList.remove('hidden');
  },500);
}
</script>
</body>
</html>