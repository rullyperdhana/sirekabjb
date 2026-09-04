<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiReKa - Pemeliharaan & Sinkronisasi Sistem (Under Maintenance)</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @keyframes rotate-clockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes rotate-counter-clockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.4; filter: blur(40px); }
            50% { opacity: 0.8; filter: blur(60px); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-spin-slow { animation: rotate-clockwise 12s linear infinite; }
        .animate-spin-reverse { animation: rotate-counter-clockwise 10s linear infinite; }
        .animate-float { animation: float-slow 6s ease-in-out infinite; }
        .animate-glow { animation: pulse-glow 4s ease-in-out infinite; }
        .animate-shimmer { animation: shimmer 2.5s infinite; }
    </style>
</head>
<body class="bg-[#0b0f19] min-h-screen text-slate-100 flex items-center justify-center p-4 sm:p-6 sm:py-12 relative overflow-x-hidden selection:bg-amber-500 selection:text-slate-950">

    <!-- Ambient Floating Orbs (Background Animations) -->
    <div class="fixed top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-tr from-amber-500/20 to-orange-500/10 rounded-full pointer-events-none animate-glow"></div>
    <div class="fixed bottom-1/4 right-1/4 translate-x-1/3 translate-y-1/3 w-[30rem] h-[30rem] bg-gradient-to-tr from-blue-600/20 via-indigo-600/15 to-purple-600/20 rounded-full pointer-events-none animate-glow" style="animation-delay: -2s;"></div>
    <div class="fixed top-3/4 left-1/3 w-64 h-64 bg-emerald-500/10 rounded-full pointer-events-none animate-float" style="animation-delay: -1s;"></div>

    <!-- Grid lines overlay -->
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#1f293708_1px,transparent_1px),linear-gradient(to_bottom,#1f293708_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none"></div>

    <!-- Main Card Content -->
    <div class="max-w-2xl w-full bg-slate-900/80 backdrop-blur-2xl border border-slate-800/80 hover:border-slate-700/80 rounded-[2.5rem] shadow-[0_0_50px_-12px_rgba(0,0,0,0.8)] p-8 sm:p-12 text-center relative z-10 overflow-hidden transition-all">

        <!-- Shimmer Top Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-slate-800 overflow-hidden">
            <div class="w-full h-full bg-gradient-to-r from-transparent via-amber-400 to-transparent animate-shimmer"></div>
        </div>

        <!-- LIVE ANIMATED GEARS ICON -->
        <div class="relative w-36 h-36 mx-auto mb-8 flex items-center justify-center">
            <!-- Glow Aura -->
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500 to-indigo-500 rounded-full opacity-30 blur-2xl animate-pulse"></div>
            
            <!-- Outer Ring -->
            <div class="absolute inset-2 border-2 border-dashed border-amber-500/40 rounded-full animate-spin-slow"></div>

            <!-- Big Gear (Clockwise) -->
            <div class="absolute text-amber-400 drop-shadow-[0_0_15px_rgba(251,191,36,0.4)] animate-spin-slow flex items-center justify-center">
                <svg class="w-24 h-24 fill-current" viewBox="0 0 24 24">
                    <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
            </div>

            <!-- Interlocking Smaller Gear (Counter-Clockwise) -->
            <div class="absolute text-indigo-400 translate-x-7 translate-y-7 drop-shadow-[0_0_10px_rgba(129,140,248,0.5)] animate-spin-reverse flex items-center justify-center">
                <svg class="w-14 h-14 fill-current" viewBox="0 0 24 24">
                    <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
            </div>

            <!-- Center Shield / Lock Badge -->
            <div class="absolute w-12 h-12 bg-slate-950 rounded-full border-2 border-amber-400/80 flex items-center justify-center shadow-inner z-10">
                <span class="material-symbols-outlined text-amber-400 text-xl" data-weight="fill">security</span>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500/10 border border-amber-500/30 rounded-full mb-6 shadow-[0_0_20px_-5px_rgba(245,158,11,0.3)]">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
            <span class="text-amber-300 font-extrabold text-xs tracking-wider uppercase">Mode Pengamanan Akses & Sinkronisasi</span>
        </div>

        <!-- Headline -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4 leading-tight">
            SiReKa Sedang Melakukan <span class="bg-gradient-to-r from-amber-400 via-orange-400 to-indigo-400 bg-clip-text text-transparent">Pemeliharaan Sistem</span>
        </h1>

        <p class="text-sm sm:text-base text-slate-300 font-normal leading-relaxed mb-8 max-w-lg mx-auto">
            Administrator sedang memperbarui dokumen arsip atau merawat infrastruktur server. Untuk menjamin keakuratan data dan menghindari insiden duplikasi, <strong class="text-amber-400 font-semibold underline decoration-amber-500/50">akses input operator SKPD ditangguhkan sementara</strong>.
        </p>

        <!-- Live Status Bar & Admin Details -->
        <div class="bg-slate-950/90 border border-slate-800/80 rounded-2xl p-6 text-left space-y-4 mb-8 shadow-inner relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Activity progress indicator -->
            <div class="flex items-center justify-between text-xs font-mono text-slate-400 pb-2 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span>SYSTEM_STATUS: ACTIVE_MAINTENANCE</span>
                </div>
                <div class="text-amber-400 font-bold" id="live-server-time">00:00:00 WITA</div>
            </div>

            <div class="flex items-start gap-4 pt-1">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0 mt-0.5 shadow-sm">
                    <span class="material-symbols-outlined text-xl" data-weight="fill">manage_history</span>
                </div>
                <div class="flex-grow space-y-1">
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 block">Informasi dari Administrator:</span>
                    <p class="text-sm sm:text-base font-semibold text-white leading-snug">{{ $status['reason'] ?? 'Sinkronisasi berkala, pengarsipan dokumen ZIP, & pemeliharaan server SiReKa.' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-900 text-xs">
                <span class="text-slate-400 flex items-center gap-1.5 font-medium">
                    <span class="material-symbols-outlined text-base text-indigo-400">timer</span>
                    <span>Target Waktu Selesai:</span>
                </span>
                <span class="font-mono font-bold text-indigo-300 bg-indigo-950/80 border border-indigo-700/60 px-3 py-1 rounded-lg shadow-sm">
                    ⏳ {{ $status['estimated_end'] ?? 'Dalam Pengerjaan Cepat Admin' }}
                </span>
            </div>
        </div>

        <!-- Action Buttons with Hover Animations -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-7 py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-extrabold rounded-xl shadow-[0_0_25px_-5px_rgba(245,158,11,0.5)] transition-all flex items-center justify-center gap-2 text-sm sm:text-base hover:scale-[1.02] active:scale-95 group">
                <span class="material-symbols-outlined text-xl transition-transform duration-500 group-hover:rotate-180">sync</span>
                <span>Cek Kembali Akses Saya</span>
            </a>

            @auth
            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-7 py-3.5 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 border border-slate-700/80 hover:border-slate-600 font-bold rounded-xl transition-all flex items-center justify-center gap-2 text-sm sm:text-base hover:scale-[1.02] active:scale-95 shadow-lg">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
            @endauth
        </div>

        <!-- Footer -->
        <div class="mt-10 pt-6 border-t border-slate-800/60 text-xs text-slate-400 font-medium flex flex-wrap items-center justify-center gap-2">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
            <span>Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Kota Banjarbaru</span>
            <span>&bull;</span>
            <span class="text-slate-500 font-mono">SiReKa v2.0 Enterprise</span>
        </div>
    </div>

    <!-- Live WITA Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            // Convert to WITA (UTC+8)
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const witaDate = new Date(utc + (3600000 * 8));
            const hours = String(witaDate.getHours()).padStart(2, '0');
            const minutes = String(witaDate.getMinutes()).padStart(2, '0');
            const seconds = String(witaDate.getSeconds()).padStart(2, '0');
            const el = document.getElementById('live-server-time');
            if(el) {
                el.innerText = `${hours}:${minutes}:${seconds} WITA`;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
