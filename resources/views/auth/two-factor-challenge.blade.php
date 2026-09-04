<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Verifikasi 2FA - SiReKa Kota Banjarbaru</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS with Forms -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
            width: 100%;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #04121a 0%, #06231b 30%, #0d3429 60%, #163e30 85%, #1d4636 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            position: relative;
            color: #1e293b;
        }

        .horizon-glow {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 380px;
            background: linear-gradient(0deg, rgba(245, 158, 11, 0.32) 0%, rgba(217, 119, 6, 0.22) 35%, rgba(180, 83, 9, 0.10) 70%, transparent 100%);
            pointer-events: none;
            z-index: 1;
        }

        .horizon-rim {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 220px;
            background: radial-gradient(ellipse 100% 90% at 50% 100%, rgba(251, 191, 36, 0.25) 0%, rgba(245, 158, 11, 0.15) 50%, transparent 100%);
            pointer-events: none;
            z-index: 1;
        }

        #fireflies-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 3;
        }

        .silhouette-layer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            pointer-events: none;
            z-index: 2;
            line-height: 0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 
                0 25px 60px -15px rgba(2, 20, 15, 0.65),
                0 0 35px rgba(245, 158, 11, 0.18),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        @keyframes subtleFloat {
            0%, 100% { transform: translateY(0px); filter: drop-shadow(0 4px 10px rgba(16, 185, 129, 0.25)); }
            50% { transform: translateY(-4px); filter: drop-shadow(0 8px 16px rgba(245, 158, 11, 0.35)); }
        }
        .logo-floating { animation: subtleFloat 5s ease-in-out infinite; }

        @keyframes clockGlow {
            0%, 100% { opacity: 0.9; filter: drop-shadow(0 0 8px #fef08a); }
            50% { opacity: 1; filter: drop-shadow(0 0 16px #fde047); }
        }
        .tower-clock-glow { animation: clockGlow 3s ease-in-out infinite; }

        @keyframes lampGlow {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }
        .lamp-glow { animation: lampGlow 3.5s ease-in-out infinite; }
    </style>
</head>
<body class="flex flex-col justify-between min-h-screen">

    <div class="horizon-glow"></div>
    <div class="horizon-rim"></div>
    <canvas id="fireflies-canvas"></canvas>

    <!-- Siluet Balai Kota & Lapangan Murdjani -->
    <div class="silhouette-layer">
        <svg viewBox="0 0 1600 350" class="w-full h-[220px] sm:h-[280px] md:h-[320px] object-cover object-bottom" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="backLayerGrad" x1="800" y1="50" x2="800" y2="350" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#0b2c22"/>
                    <stop offset="60%" stop-color="#061f18"/>
                    <stop offset="100%" stop-color="#03120e"/>
                </linearGradient>
                <linearGradient id="frontLayerGrad" x1="800" y1="30" x2="800" y2="350" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#041812"/>
                    <stop offset="40%" stop-color="#020f0b"/>
                    <stop offset="100%" stop-color="#010806"/>
                </linearGradient>
                <radialGradient id="lampHalo" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#fef08a" stop-opacity="0.95"/>
                    <stop offset="35%" stop-color="#f59e0b" stop-opacity="0.65"/>
                    <stop offset="70%" stop-color="#d97706" stop-opacity="0.2"/>
                    <stop offset="100%" stop-color="#b45309" stop-opacity="0"/>
                </radialGradient>
            </defs>
            <path fill="url(#backLayerGrad)" d="M0,350 L0,220 L20,180 L35,225 L60,160 L85,220 L115,150 L140,210 L170,140 L195,205 L225,170 L255,225 L290,210 L330,180 L370,180 L410,210 L450,190 L490,225 L540,170 L590,220 L650,165 L710,215 L770,160 L830,210 L890,165 L950,215 L1010,165 L1070,210 L1110,155 L1140,210 L1180,135 L1210,200 L1250,145 L1290,210 L1330,130 L1370,200 L1410,145 L1450,210 L1490,140 L1530,200 L1570,160 L1600,210 L1600,350 Z"/>
            <path fill="url(#frontLayerGrad)" d="M0,350 L0,260 C15,260 30,230 45,230 C60,230 75,255 90,255 L105,255 L120,135 L135,255 C150,230 165,190 190,190 C215,190 230,230 240,250 L240,240 L265,240 L265,215 L290,215 L290,195 L305,195 L320,170 L335,195 L335,150 L340,150 L340,70 L344,70 L344,50 L348,50 L348,32 L351,32 L351,15 L353,15 L353,32 L356,32 L356,50 L360,50 L360,70 L364,70 L364,150 L369,150 L369,195 L384,170 L399,195 L414,195 L414,215 L439,215 L439,240 L465,240 L465,255 L480,255 L495,125 L510,255 L550,255 C630,250 720,250 800,250 C880,250 970,250 1030,255 L1050,255 L1065,120 L1080,255 C1100,220 1125,170 1160,170 C1195,170 1215,220 1235,255 L1250,255 L1265,95 L1280,255 L1295,255 L1315,45 L1335,255 L1350,255 L1365,105 L1380,255 L1395,255 L1415,65 L1435,255 C1455,220 1480,180 1510,180 C1540,180 1555,225 1570,255 L1580,255 L1590,110 L1600,255 L1600,350 Z"/>
            <circle cx="352" cy="105" r="11" fill="#fef08a" class="tower-clock-glow" />
            <circle cx="352" cy="105" r="8" fill="#f59e0b" />
            <line x1="352" y1="105" x2="352" y2="100" stroke="#451a03" stroke-width="1.8" stroke-linecap="round"/>
            <line x1="352" y1="105" x2="357" y2="105" stroke="#451a03" stroke-width="1.8" stroke-linecap="round"/>
            <rect x="298" y="222" width="8" height="13" rx="2" fill="#fde68a" opacity="0.95"/>
            <rect x="312" y="222" width="8" height="13" rx="2" fill="#fde68a" opacity="0.95"/>
            <rect x="390" y="222" width="8" height="13" rx="2" fill="#fde68a" opacity="0.95"/>
            <rect x="404" y="222" width="8" height="13" rx="2" fill="#fde68a" opacity="0.95"/>
            <g class="lamp-glow">
                <circle cx="205" cy="215" r="26" fill="url(#lampHalo)"/>
                <circle cx="205" cy="215" r="5" fill="#fef08a"/>
                <line x1="205" y1="215" x2="205" y2="295" stroke="#020f0b" stroke-width="3.5"/>
            </g>
            <g class="lamp-glow">
                <circle cx="1160" cy="210" r="26" fill="url(#lampHalo)"/>
                <circle cx="1160" cy="210" r="5" fill="#fef08a"/>
                <line x1="1160" y1="210" x2="1160" y2="295" stroke="#020f0b" stroke-width="3.5"/>
            </g>
        </svg>
    </div>

    <!-- Top Header -->
    <header class="relative z-10 pt-3 px-3 sm:px-8 w-full">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-1.5">
            <div class="flex items-center gap-1.5 min-w-0">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                <span class="text-[10.5px] sm:text-xs tracking-wider text-emerald-200/90 font-bold uppercase truncate">Verifikasi Dua Langkah (2FA)</span>
            </div>
            <div class="shrink-0 flex items-center gap-1.5 text-[10.5px] sm:text-xs text-emerald-200/90 font-semibold bg-emerald-950/50 backdrop-blur-md px-2 sm:px-3 py-0.5 sm:py-1 rounded-full border border-emerald-500/20">
                <span id="live-clock">--:--:-- WITA</span>
            </div>
        </div>
    </header>

    <!-- Main Card -->
    <main class="relative z-10 flex-grow flex items-center justify-center px-4 py-3 sm:py-6 w-full">
        <div class="glass-card w-full max-w-[400px] rounded-2xl overflow-hidden transition-all duration-300 mx-auto">
            
            <div class="h-1.5 w-full bg-gradient-to-r from-emerald-600 via-amber-400 to-teal-600"></div>

            <div class="p-5 sm:p-7">
                <!-- Branding Header -->
                <div class="text-center mb-5">
                    @php
                        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
                        $logoApp = null;
                        if ($pengaturanGlobal && $pengaturanGlobal->logo) {
                            if (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http')) {
                                $logoApp = $pengaturanGlobal->logo;
                            } elseif (file_exists(public_path($pengaturanGlobal->logo))) {
                                $logoApp = asset($pengaturanGlobal->logo);
                            } elseif (file_exists(public_path('storage/' . $pengaturanGlobal->logo))) {
                                $logoApp = asset('storage/' . $pengaturanGlobal->logo);
                            }
                        }
                        if (!$logoApp && file_exists(public_path('images/logo_banjarbaru.png'))) {
                            $logoApp = asset('images/logo_banjarbaru.png');
                        }
                    @endphp

                    <div class="flex justify-center mb-2">
                        @if($logoApp)
                            <img src="{{ $logoApp }}" alt="Logo Pemko Banjarbaru" class="h-14 sm:h-16 object-contain logo-floating">
                        @endif
                    </div>

                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                        Autentikasi Dua Langkah
                    </h1>
                    <p class="text-xs font-medium text-slate-500 mt-1">
                        Hai, <span class="font-bold text-emerald-800">{{ $user->name ?? $user->username }}</span>. Masukkan kode dari aplikasi autentikator Anda.
                    </p>
                </div>

                @if($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-semibold">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Form 2FA -->
                <form action="{{ route('two-factor.verify') }}" method="POST" autocomplete="off" class="space-y-4">
                    @csrf

                    <!-- Mode OTP Google Authenticator -->
                    <div id="otpSection">
                        <label for="code" class="block text-xs font-bold text-slate-700 mb-1.5 text-center">
                            Kode 6-Digit Google Authenticator
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autofocus placeholder="• • • • • •" 
                                class="w-full py-3 text-2xl tracking-[0.35em] text-center font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-emerald-950 placeholder-slate-300 transition duration-200">
                        </div>
                        <p class="text-[11px] text-slate-400 text-center mt-1.5">
                            Buka aplikasi Google Authenticator di smartphone Anda.
                        </p>
                    </div>

                    <!-- Mode Recovery Code (Hidden by default) -->
                    <div id="recoverySection" class="hidden">
                        <label for="recovery_code" class="block text-xs font-bold text-slate-700 mb-1.5 text-center">
                            Kode Pemulihan Cadangan (Recovery Code)
                        </label>
                        <input id="recovery_code" type="text" name="recovery_code" placeholder="XXXX-XXXX" 
                            class="w-full py-2.5 text-base tracking-wider text-center font-mono font-bold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-800 uppercase transition duration-200">
                        <p class="text-[11px] text-slate-400 text-center mt-1.5">
                            Gunakan salah satu kode pemulihan yang tersimpan.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full relative group overflow-hidden bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 hover:from-emerald-600 hover:via-teal-600 hover:to-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg shadow-emerald-950/25 hover:shadow-emerald-700/40 transform active:scale-[0.99] transition duration-200 flex items-center justify-center gap-2">
                            <span>Verifikasi & Lanjutkan</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                    <!-- Toggle Recovery / OTP Mode -->
                    <div class="pt-2 text-center">
                        <button type="button" id="toggleRecoveryBtn" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition underline underline-offset-2">
                            Gunakan Kode Pemulihan Cadangan
                        </button>
                    </div>
                </form>

                <!-- Back to login -->
                <div class="mt-4 pt-3 border-t border-slate-200 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Halaman Login</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="relative z-10 pb-3 text-center px-4 w-full">
        <p class="text-[10.5px] sm:text-[11px] font-medium text-emerald-200/75 max-w-md mx-auto leading-relaxed">
            &copy; {{ date('Y') }} Badan Pengelola Keuangan dan Aset Daerah (BPKAD) Kota Banjarbaru.
        </p>
    </footer>

    <script>
        // Realtime WITA Clock
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                const now = new Date();
                const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
                clockEl.textContent = new Intl.DateTimeFormat('id-ID', options).format(now) + ' WITA';
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Toggle Recovery Code / OTP
        const toggleBtn = document.getElementById('toggleRecoveryBtn');
        const otpSection = document.getElementById('otpSection');
        const recoverySection = document.getElementById('recoverySection');
        const codeInput = document.getElementById('code');
        const recoveryInput = document.getElementById('recovery_code');

        let isRecovery = false;
        toggleBtn.addEventListener('click', function() {
            isRecovery = !isRecovery;
            if (isRecovery) {
                otpSection.classList.add('hidden');
                recoverySection.classList.remove('hidden');
                toggleBtn.textContent = 'Gunakan Kode Google Authenticator (OTP)';
                codeInput.value = '';
                recoveryInput.focus();
            } else {
                otpSection.classList.remove('hidden');
                recoverySection.classList.add('hidden');
                toggleBtn.textContent = 'Gunakan Kode Pemulihan Cadangan';
                recoveryInput.value = '';
                codeInput.focus();
            }
        });

        // Golden Fireflies Canvas (Smooth 60FPS)
        (function() {
            const canvas = document.getElementById('fireflies-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width, height;
            let fireflies = [];
            const count = Math.min(Math.floor(window.innerWidth / 20), 60);

            function resize() {
                const dpr = window.devicePixelRatio || 1;
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width * dpr;
                canvas.height = height * dpr;
                ctx.scale(dpr, dpr);
            }
            window.addEventListener('resize', resize);
            resize();

            const colors = [
                { r: 245, g: 158, b: 11 },
                { r: 251, g: 191, b: 36 },
                { r: 254, g: 240, b: 138 },
                { r: 52,  g: 211, b: 153 },
            ];

            class Firefly {
                constructor() {
                    this.init(true);
                }
                init(initial = false) {
                    this.x = Math.random() * width;
                    this.y = initial ? Math.random() * height : height + 30;
                    this.size = 1.6 + Math.random() * 2;
                    this.baseAlpha = 0.25 + Math.random() * 0.55;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.vy = -(0.35 + Math.random() * 0.5);
                    this.vx = (Math.random() - 0.5) * 0.3;
                    this.swayFreq = 0.015 + Math.random() * 0.02;
                    this.swayAmp = 0.5 + Math.random() * 0.8;
                    this.pulseFreq = 0.025 + Math.random() * 0.035;
                    this.phase = Math.random() * Math.PI * 2;
                    this.time = Math.random() * 1000;
                }
                update() {
                    this.time += 1;
                    this.x += this.vx + Math.sin(this.time * this.swayFreq) * this.swayAmp;
                    this.y += this.vy;
                    if (this.y < -30 || this.x < -30 || this.x > width + 30) this.init(false);
                }
                draw() {
                    const pulse = Math.sin(this.phase + this.time * this.pulseFreq);
                    const alpha = Math.max(0.05, Math.min(1, this.baseAlpha + pulse * 0.35));
                    const glow = this.size * 4.5;
                    const grad = ctx.createRadialGradient(this.x, this.y, 0, this.x, this.y, glow);
                    grad.addColorStop(0, `rgba(254, 243, 199, ${alpha})`);
                    grad.addColorStop(0.35, `rgba(${this.color.r}, ${this.color.g}, ${this.color.b}, ${alpha * 0.65})`);
                    grad.addColorStop(1, `rgba(${this.color.r}, ${this.color.g}, ${this.color.b}, 0)`);
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, glow, 0, Math.PI * 2);
                    ctx.fillStyle = grad;
                    ctx.fill();
                }
            }

            for (let i = 0; i < count; i++) fireflies.push(new Firefly());

            function loop() {
                ctx.clearRect(0, 0, width, height);
                for (let f of fireflies) {
                    f.update();
                    f.draw();
                }
                requestAnimationFrame(loop);
            }
            loop();
        })();
    </script>
</body>
</html>
