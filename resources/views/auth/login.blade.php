<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - SiReKa (Sistem Rekonsiliasi Kas) Kota Banjarbaru</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    
    <!-- Tailwind CSS with Forms -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        bjb: {
                            blue: '#0284c7',
                            navy: '#00346f',
                            deep: '#001f44',
                            light: '#e0f2fe',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * {
            box-sizing: border-box;
        }
        html, body {
            overflow-x: hidden;
            min-height: 100vh;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #001938 0%, #00346f 50%, #004b99 100%);
            position: relative;
            color: #1e293b;
            min-height: 100vh;
        }

        /* Custom HTML5 Fireflies Canvas */
        #fireflies-canvas {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: auto;
        }

        /* Solid Crisp Center Login Card */
        .login-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 
                0 25px 50px -12px rgba(0, 15, 45, 0.45),
                0 0 0 1px rgba(255, 255, 255, 0.15) inset;
            width: 100%;
            max-width: 440px;
            box-sizing: border-box;
        }

        @media (max-width: 640px) {
            .login-card {
                padding: 1.25rem !important;
                border-radius: 1.25rem !important;
            }
        }

        /* Input field styling */
        .input-solid {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease-in-out;
        }
        .input-solid:focus {
            background: #ffffff;
            border-color: #00346f;
            box-shadow: 0 0 0 3px rgba(0, 52, 111, 0.15);
        }
    </style>
</head>
<body class="flex flex-col justify-between min-h-screen relative">

    <!-- Custom HTML5 Canvas: Bioluminescent Golden Fireflies -->
    <canvas id="fireflies-canvas"></canvas>

    @php
        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
        $logoLogin = ($pengaturanGlobal && $pengaturanGlobal->logo) 
            ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
            : asset('images/logo_banjarbaru.png');
    @endphp

    <!-- Top Navigation Bar -->
    <header class="relative z-10 w-full px-4 sm:px-8 lg:px-12 py-3 sm:py-4 flex items-center justify-between">
        <!-- Logo & City Identity -->
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2.5 sm:gap-3 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 shadow-sm hover:bg-white/25 transition-all group">
            <img src="{{ $logoLogin }}" alt="Logo Kota Banjarbaru" class="w-6 h-6 sm:w-7 sm:h-7 object-contain drop-shadow-xs">
            <div class="text-left">
                <span class="block text-xs font-black text-white tracking-tight leading-tight group-hover:text-blue-200 transition">SiReKa BJB</span>
                <span class="block text-[9.5px] sm:text-[10px] font-semibold text-blue-100/80 leading-none">Kota Banjarbaru</span>
            </div>
        </a>

        <!-- Right Quick Info: Live Clock & Landing link -->
        <div class="flex items-center gap-2">
            <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 shadow-xs text-xs font-semibold text-white">
                <span class="material-symbols-outlined text-[16px] text-blue-200">schedule</span>
                <span id="live-clock">--:--:-- WITA</span>
            </div>
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 shadow-xs text-xs font-bold text-white hover:bg-white/30 transition-all">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span class="text-xs">Beranda</span>
            </a>
        </div>
    </header>

    <!-- Main Content: Centered Login Card -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-2 sm:py-3 w-full max-w-full">
        <div class="w-full max-w-[440px] mx-auto my-auto">
            
            <!-- White Center Card -->
            <div class="login-card p-5 sm:p-7 relative overflow-hidden transition-all duration-300 w-full">
                
                <!-- Header Branding -->
                <div class="text-center mb-4">
                    <!-- Government Shield Logo -->
                    <div class="flex flex-col items-center justify-center gap-1.5 mb-2">
                        <div class="p-2.5 bg-slate-50 rounded-2xl shadow-2xs border border-slate-200 inline-flex items-center justify-center">
                            <img src="{{ $logoLogin }}" alt="Lambang Kota Banjarbaru" class="w-11 h-11 object-contain">
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 border border-blue-200 text-[10px] font-extrabold text-[#00346f] tracking-wider uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00346f] animate-pulse"></span>
                                Pemko Banjarbaru
                            </div>
                            <h2 class="text-[11px] font-bold text-slate-600 mt-0.5">BPKAD Kota Banjarbaru</h2>
                        </div>
                    </div>

                    <!-- Main Portal Title -->
                    <h1 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-snug">
                        SISTEM REKONSILIASI KAS DAERAH
                    </h1>
                    <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed max-w-sm mx-auto">
                        Masuk ke akun SiReKa untuk mengakses layanan verifikasi rekonsiliasi kas daerah.
                    </p>
                </div>

                <!-- Session Alert Messages -->
                <x-auth-session-status class="mb-3" :status="session('status')" />
                @if(session('error'))
                    <div class="mb-3 p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-800 flex items-center gap-2 shadow-2xs">
                        <span class="material-symbols-outlined text-[18px] text-rose-600">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-3">
                    @csrf

                    <!-- Field 1: Username / Email -->
                    <div>
                        <label for="login" class="block text-xs font-bold text-slate-700 mb-1">Username / NIP / Email</label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                            </div>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus 
                                placeholder="Ketik username atau email akun" 
                                class="w-full pl-10 pr-4 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                        </div>
                        <x-input-error :messages="$errors->get('login')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Field 2: Password with Toggle Eye -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                            </div>
                            <input id="password" type="password" name="password" required 
                                placeholder="••••••••" 
                                class="w-full pl-10 pr-11 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#00346f] transition" aria-label="Lihat Password">
                                <span id="eyeIcon" class="material-symbols-outlined text-[18px]">visibility</span>
                                <span id="eyeSlashIcon" class="material-symbols-outlined text-[18px] hidden">visibility_off</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Field 3: Tahun Anggaran Dropdown -->
                    @php
                        $tahunAnggarans = \App\Models\TahunAnggaran::where('is_active', true)->orderBy('tahun', 'desc')->get();
                    @endphp
                    <div>
                        <label for="tahun_login" class="block text-xs font-bold text-slate-700 mb-1">Tahun Anggaran</label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            </div>
                            <select id="tahun_login" name="tahun_login" required 
                                class="w-full pl-10 pr-8 py-2 text-sm input-solid rounded-xl text-slate-800 font-bold outline-none cursor-pointer">
                                @forelse($tahunAnggarans as $ta)
                                    <option value="{{ $ta->tahun }}" {{ date('Y') == $ta->tahun ? 'selected' : '' }}>Tahun Anggaran {{ $ta->tahun }}</option>
                                @empty
                                    <option value="{{ date('Y') }}">Tahun Anggaran {{ date('Y') }}</option>
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <!-- Field 4: Pertanyaan Keamanan (Math Captcha) Card -->
                    <div class="bg-blue-50/80 border border-blue-200 rounded-xl p-3 shadow-2xs">
                        <div class="flex items-center justify-between gap-1 mb-1.5">
                            <span class="flex items-center gap-1.5 text-xs font-bold text-[#00346f]">
                                <span class="material-symbols-outlined text-[16px]">security</span>
                                <span>Verifikasi Keamanan</span>
                            </span>
                            <span class="text-xs font-black text-[#00346f] bg-white px-2.5 py-0.5 rounded-md border border-blue-200 shadow-2xs">
                                Berapa {{ $num1 ?? 0 }} + {{ $num2 ?? 0 }}?
                            </span>
                        </div>
                        <input id="captcha" type="number" name="captcha" required placeholder="Tulis hasil penjumlahan" 
                            class="w-full px-3 py-1.5 text-sm bg-white border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00346f]/25 focus:border-[#00346f] text-center font-extrabold text-[#00346f] placeholder-blue-300 transition duration-200">
                        <x-input-error :messages="$errors->get('captcha')" class="mt-1 text-xs text-rose-600 text-center font-semibold" />
                    </div>

                    <!-- Remember Me (Tanpa Slogan) -->
                    <div class="flex items-center justify-between gap-2 pt-0.5">
                        <label class="inline-flex items-center cursor-pointer">
                            <input id="customCheck1" type="checkbox" name="remember" class="w-4 h-4 text-[#00346f] bg-white border-slate-300 rounded focus:ring-[#00346f] focus:ring-2 cursor-pointer">
                            <span class="ml-2 text-xs font-semibold text-slate-600 select-none">Ingat sesi saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1">
                        <button type="submit" class="w-full relative group overflow-hidden bg-gradient-to-r from-[#00346f] via-[#00428c] to-[#00346f] hover:from-[#00224d] hover:via-[#00346f] hover:to-[#00224d] text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg shadow-blue-950/20 active:scale-[0.99] transition duration-200 flex items-center justify-center gap-2">
                            <span>Masuk ke Sistem</span>
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <!-- Optional Self Registration -->
                @if($pengaturanGlobal && $pengaturanGlobal->is_registration_open)
                <div class="mt-3 text-center border-t border-slate-200/80 pt-2.5">
                    <p class="text-xs text-slate-500 mb-0.5">Belum memiliki akun operator SKPD?</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#00346f] hover:text-blue-900 transition">
                        <span>Daftar Akun Mandiri</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
                @endif

                <!-- 4-Pilar Civic Info Footer Card -->
                <div class="mt-3.5 pt-2.5 border-t border-slate-200/80 text-center">
                    <p class="text-[10px] sm:text-[10.5px] font-semibold text-slate-500 leading-relaxed">
                        <span class="font-extrabold text-[#00346f]">Alur 4-Pilar:</span> Operator SKPD &bull; Bank Kalsel &bull; BPKAD &bull; Inspektorat
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Note -->
    <footer class="relative z-10 py-3 text-center px-4 w-full">
        <div class="inline-block max-w-full px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 shadow-2xs text-center">
            <p class="text-[10px] sm:text-[11px] font-semibold text-blue-100 leading-normal">
                &copy; {{ date('Y') }} Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Pemerintah Kota Banjarbaru.
            </p>
        </div>
    </footer>

    <!-- Custom HTML5 Canvas: Bioluminescent Golden Fireflies (Kunang-Kunang Emas) -->
    <script>
        (function() {
            const canvas = document.getElementById('fireflies-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            let width, height;
            let dpr = Math.min(window.devicePixelRatio || 1, 2);

            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width * dpr;
                canvas.height = height * dpr;
                ctx.scale(dpr, dpr);
            }
            window.addEventListener('resize', resize);
            resize();

            // Track mouse for interactive swirling evasion
            const mouse = { x: null, y: null };
            window.addEventListener('mousemove', function(e) {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
            });
            window.addEventListener('mouseleave', function() {
                mouse.x = null;
                mouse.y = null;
            });

            // Firefly Color Palette: Emas, Amber, Hijau Zamrud
            const PALETTE = [
                { r: 251, g: 191, b: 36, weight: 0.50 },  // Emas Murni (Gold)
                { r: 245, g: 158, b: 11, weight: 0.25 },  // Amber Hangat
                { r: 52,  g: 211, b: 153, weight: 0.25 }  // Hijau Zamrud Tropis (Emerald)
            ];

            function pickColor() {
                const rand = Math.random();
                let acc = 0;
                for (const col of PALETTE) {
                    acc += col.weight;
                    if (rand <= acc) return col;
                }
                return PALETTE[0];
            }

            class Firefly {
                constructor() {
                    this.init(true);
                }

                init(isFirst = false) {
                    this.x = Math.random() * width;
                    this.y = isFirst ? Math.random() * height : (Math.random() < 0.5 ? -15 : height + 15);
                    this.vx = (Math.random() - 0.5) * 0.6;
                    this.vy = (Math.random() - 0.5) * 0.6;
                    this.baseRadius = 1.4 + Math.random() * 2.2;
                    this.color = pickColor();

                    // Breathing pulse parameters
                    this.pulse = Math.random() * Math.PI * 2;
                    this.pulseSpeed = 0.02 + Math.random() * 0.035;

                    // Organic meandering / wandering
                    this.wanderAngle = Math.random() * Math.PI * 2;
                    this.wanderSpeed = 0.015 + Math.random() * 0.02;
                }

                update() {
                    // Gentle wandering motion
                    this.wanderAngle += this.wanderSpeed;
                    this.vx += Math.cos(this.wanderAngle) * 0.035;
                    this.vy += Math.sin(this.wanderAngle) * 0.035;

                    // Velocity damping
                    this.vx *= 0.97;
                    this.vy *= 0.97;

                    // Interactive dynamic swirling / evasion when mouse approaches
                    if (mouse.x !== null && mouse.y !== null) {
                        const dx = this.x - mouse.x;
                        const dy = this.y - mouse.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        const proximityRadius = 140;

                        if (dist < proximityRadius && dist > 1) {
                            const factor = (1 - dist / proximityRadius);
                            // Direct repulsion
                            const repulseX = (dx / dist) * factor * 1.6;
                            const repulseY = (dy / dist) * factor * 1.6;
                            // Tangential swirl vector ("meliuk")
                            const swirlX = (-dy / dist) * factor * 2.0;
                            const swirlY = (dx / dist) * factor * 2.0;

                            this.vx += repulseX + swirlX;
                            this.vy += repulseY + swirlY;
                        }
                    }

                    this.x += this.vx;
                    this.y += this.vy;

                    // Pulse glow
                    this.pulse += this.pulseSpeed;

                    // Toroidal wrap-around
                    const pad = 25;
                    if (this.x < -pad) this.x = width + pad;
                    if (this.x > width + pad) this.x = -pad;
                    if (this.y < -pad) this.y = height + pad;
                    if (this.y > height + pad) this.y = -pad;
                }

                draw() {
                    // Soft breathing pulse glow
                    const pulseFactor = 0.5 + 0.5 * Math.sin(this.pulse);
                    const coreAlpha = 0.35 + 0.60 * pulseFactor;
                    const glowRadius = this.baseRadius * (3.8 + 3.2 * pulseFactor);

                    const { r, g, b } = this.color;

                    // Radial glow gradient (emas / amber / zamrud)
                    const grad = ctx.createRadialGradient(
                        this.x, this.y, 0,
                        this.x, this.y, glowRadius
                    );
                    grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${coreAlpha})`);
                    grad.addColorStop(0.3, `rgba(${r}, ${g}, ${b}, ${coreAlpha * 0.55})`);
                    grad.addColorStop(0.7, `rgba(${r}, ${g}, ${b}, ${coreAlpha * 0.15})`);
                    grad.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);

                    ctx.fillStyle = grad;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, glowRadius, 0, Math.PI * 2);
                    ctx.fill();

                    // Bright incandescent core
                    ctx.fillStyle = `rgba(255, 255, 255, ${coreAlpha * 0.95})`;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.baseRadius * 0.55, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            // Create optimal firefly population (~55 particles for smooth 60fps)
            const count = Math.min(Math.floor((window.innerWidth * window.innerHeight) / 18000), 65);
            const fireflies = [];
            for (let i = 0; i < Math.max(count, 35); i++) {
                fireflies.push(new Firefly());
            }

            // 60 FPS Render Loop
            function animate() {
                ctx.clearRect(0, 0, width, height);

                // Additive screen blend for radiant glowing bioluminescence
                ctx.globalCompositeOperation = 'screen';

                for (let i = 0; i < fireflies.length; i++) {
                    fireflies[i].update();
                    fireflies[i].draw();
                }

                ctx.globalCompositeOperation = 'source-over';
                requestAnimationFrame(animate);
            }

            requestAnimationFrame(animate);
        })();

        // 1. Password Visibility Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                eyeIcon.classList.toggle('hidden', isPassword);
                eyeSlashIcon.classList.toggle('hidden', !isPassword);
            });
        }

        // 2. Realtime WITA Clock
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            if (!clockEl) return;
            
            const now = new Date();
            // Banjarbaru operates in WITA (UTC+8)
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const witaTime = new Date(utc + (3600000 * 8));
            
            const hours = String(witaTime.getHours()).padStart(2, '0');
            const minutes = String(witaTime.getMinutes()).padStart(2, '0');
            const seconds = String(witaTime.getSeconds()).padStart(2, '0');
            
            clockEl.textContent = `${hours}:${minutes}:${seconds} WITA`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
