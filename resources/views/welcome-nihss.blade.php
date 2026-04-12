<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern Card UI</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes bounceSoft {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }
  </style>
</head>

<body class="bg-gradient-to-br from-[#dbeafe] via-[#bfdbfe] to-[#93c5fd] min-h-screen flex items-center justify-center font-sans">

  <!-- LOADING -->
  <div id="loader" class="fixed inset-0 bg-white flex items-center justify-center z-50">
    <div class="flex flex-col items-center gap-4">
      <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white animate-[bounceSoft_1s_infinite] shadow-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M5 21h14a2 2 0 002-2V11H3v8a2 2 0 002 2z" />
        </svg>
      </div>
      <p class="text-blue-600 font-semibold">Loading...</p>
    </div>
  </div>

  <div id="content" class="hidden w-full max-w-6xl px-6 grid md:grid-cols-2 gap-10 items-center">

    <!-- LEFT CONTENT (TEXT) -->
    <div>
      <h1 class="text-4xl md:text-5xl font-bold text-blue-900 leading-tight">
        RSUP Fatmawati <span class="text-white">NIHSS</span>
      </h1>
      <p class="mt-4 text-gray-700 text-lg">
        Platform modern untuk membantu tenaga medis melakukan penilaian stroke secara cepat, akurat, dan efisien.
      </p>

      <!-- INFO CARDS -->
      <div class="grid grid-cols-2 gap-4 mt-8">
        <div class="bg-white/60 backdrop-blur-xl p-4 rounded-2xl shadow">
          <h3 class="font-bold text-blue-800">⚡ Cepat</h3>
          <p class="text-sm text-gray-600">Input & hasil instan</p>
        </div>
        <div class="bg-white/60 backdrop-blur-xl p-4 rounded-2xl shadow">
          <h3 class="font-bold text-blue-800">⚡ Akurat</h3>
          <p class="text-sm text-gray-600">Perhitungan otomatis</p>
        </div>
        <div class="bg-white/60 backdrop-blur-xl p-4 rounded-2xl shadow">
          <h3 class="font-bold text-blue-800">⚡ Aman</h3>
          <p class="text-sm text-gray-600">Data tersimpan rapi</p>
        </div>
        <div class="bg-white/60 backdrop-blur-xl p-4 rounded-2xl shadow">
          <h3 class="font-bold text-blue-800">⚡ Responsive</h3>
          <p class="text-sm text-gray-600">Bisa di semua device</p>
        </div>
      </div>
    </div>

    <!-- RIGHT CONTENT (CARDS MENU) -->
    <div class="grid gap-6">

      <!-- <a href="analytics.html" class="group">
        <div class="bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-2xl hover:scale-105 hover:-translate-y-2 transition">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-blue-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 13l3-3 4 4 5-5" />
              </svg>
            </div>
            <div>
              <h2 class="font-semibold text-gray-800">Analytics</h2>
              <p class="text-sm text-gray-600">Lihat statistik</p>
            </div>
          </div>
        </div>
      </a> -->
      <a href="" class="group">
        <div class="bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-2xl hover:scale-105 hover:-translate-y-2 transition">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-blue-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 13l3-3 4 4 5-5" />
              </svg>
            </div>
            <div>
              <h2 class="font-semibold text-gray-800">Mulai NAP</h2>
              <p class="text-sm text-gray-600">Mulai Pemeriksaan</p>
            </div>
          </div>
        </div>
      </a>

      
      <a href="{{ route('nihss.calculator') }}" class="group">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-3xl p-6 shadow-2xl hover:scale-105 hover:-translate-y-2 transition">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/20">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
              </svg>
            </div>
            <div>
              <h2 class="font-semibold">Mulai NIHSS</h2>
              <p class="text-sm opacity-90">Mulai pemeriksaan</p>
            </div>
          </div>
        </div>
      </a>
      <!-- <a href="history.html" class="group">
        <div class="bg-white/60 backdrop-blur-xl rounded-3xl p-6 shadow-2xl hover:scale-105 hover:-translate-y-2 transition">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-purple-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
              </svg>
            </div>
            <div>
              <h2 class="font-semibold text-gray-800">Riwayat</h2>
              <p class="text-sm text-gray-600">Data sebelumnya</p>
            </div>
          </div>
        </div>
      </a> -->

    </div>

  </div>

  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('content').classList.remove('hidden');
      }, 1200);
    });
  </script>

</body>
</html>
