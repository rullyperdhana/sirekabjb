<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pendaftaran Akun Operator SKPD - SiReKa Kota Banjarbaru</title>
    
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
                            deep: '#001938',
                        }
                    }
                }
            }
        }
    </script>

    <!-- TomSelect CSS for searchable SKPD dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

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

        /* Particles canvas wrapper */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }

        /* Register Card */
        .register-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 
                0 25px 50px -12px rgba(0, 15, 45, 0.45),
                0 0 0 1px rgba(255, 255, 255, 0.15) inset;
            width: 100%;
            max-width: 520px;
            box-sizing: border-box;
        }

        @media (max-width: 640px) {
            .register-card {
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

        /* Override TomSelect styles */
        .ts-control {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.75rem !important;
            padding: 0.6rem 0.85rem !important;
            background: #f8fafc !important;
            font-size: 0.875rem !important;
            font-family: inherit !important;
            min-height: 42px;
            box-shadow: none !important;
        }
        .ts-control.focus {
            background: #ffffff !important;
            border-color: #00346f !important;
            box-shadow: 0 0 0 3px rgba(0, 52, 111, 0.15) !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid #e2e8f0 !important;
            font-size: 0.875rem !important;
            z-index: 50 !important;
        }
        .ts-dropdown .active {
            background-color: #eff6ff !important;
            color: #00346f !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="flex flex-col justify-between min-h-screen relative">

    <!-- Interactive Particle Mesh Network (Constellation) -->
    <div id="particles-js"></div>

    @php
        $pengaturanGlobal = \App\Models\Pengaturan::whereNull('skpd_id')->first();
        $logoApp = ($pengaturanGlobal && $pengaturanGlobal->logo) 
            ? (\Illuminate\Support\Str::startsWith($pengaturanGlobal->logo, 'http') ? $pengaturanGlobal->logo : asset('storage/' . $pengaturanGlobal->logo)) 
            : asset('images/logo_banjarbaru.png');
    @endphp

    <!-- Top Navigation Bar -->
    <header class="relative z-10 pointer-events-none w-full px-4 sm:px-8 lg:px-12 py-3 sm:py-4 flex items-center justify-between">
        <!-- Logo & Identity -->
        <a href="{{ route('landing') }}" class="pointer-events-auto inline-flex items-center gap-2.5 sm:gap-3 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 shadow-sm hover:bg-white/25 transition-all group">
            <img src="{{ $logoApp }}" alt="Logo Kota Banjarbaru" class="w-6 h-6 sm:w-7 sm:h-7 object-contain drop-shadow-xs">
            <div class="text-left">
                <span class="block text-xs font-black text-white tracking-tight leading-tight group-hover:text-blue-200 transition">SiReKa BJB</span>
                <span class="block text-[9.5px] sm:text-[10px] font-semibold text-blue-100/80 leading-none">Kota Banjarbaru</span>
            </div>
        </a>

        <!-- Back to Login -->
        <a href="{{ route('login') }}" class="pointer-events-auto inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 shadow-xs text-xs font-bold text-white hover:bg-white/30 transition-all">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali ke Login</span>
        </a>
    </header>

    <!-- Main Content: Centered Card -->
    <main class="relative z-10 pointer-events-none flex-1 flex items-center justify-center px-4 py-4 sm:py-6 w-full max-w-full">
        <div class="w-full max-w-[520px] mx-auto my-auto">
            <div class="register-card pointer-events-auto p-5 sm:p-7 relative overflow-hidden transition-all duration-300 w-full">
                
                <!-- Header Branding -->
                <div class="text-center mb-4">
                    <div class="flex flex-col items-center justify-center gap-1.5 mb-2">
                        <div class="p-2.5 bg-slate-50 rounded-2xl shadow-2xs border border-slate-200 inline-flex items-center justify-center">
                            <img src="{{ $logoApp }}" alt="Lambang Kota Banjarbaru" class="w-10 h-10 object-contain">
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 border border-blue-200 text-[10px] font-extrabold text-[#00346f] tracking-wider uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#00346f] animate-pulse"></span>
                                Pemko Banjarbaru
                            </div>
                            <h2 class="text-[11px] font-bold text-slate-600 mt-0.5">BPKAD Kota Banjarbaru</h2>
                        </div>
                    </div>

                    <h1 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-snug">
                        PENDAFTARAN OPERATOR SKPD
                    </h1>
                    <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed max-w-sm mx-auto">
                        Buat akun mandiri resmi untuk Operator SKPD di lingkungan Pemerintah Kota Banjarbaru.
                    </p>
                </div>

                <!-- Session Status / Errors -->
                <x-auth-session-status class="mb-3 text-center text-xs text-blue-600 font-bold" :status="session('status')" />
                
                <form action="{{ route('register') }}" method="POST" autocomplete="off" class="space-y-3.5">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">badge</span>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan Nama Lengkap beserta gelar" 
                                class="w-full pl-10 pr-4 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Email Pengguna -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                            Email Kedinasan / Aktif <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">mail</span>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: op.skpd@banjarbarukota.go.id" 
                                class="w-full pl-10 pr-4 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- SKPD (Instansi) -->
                    <div>
                        <label for="skpd_id" class="block text-xs font-bold text-slate-700 mb-1">
                            Pilih SKPD (Instansi) <span class="text-rose-500">*</span>
                        </label>
                        <select id="skpd_id" name="skpd_id" required>
                            <option value="">-- Cari atau Pilih SKPD Anda --</option>
                            @foreach($skpds as $skpd)
                                <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                    {{ $skpd->kode }} - {{ $skpd->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-500 mt-1 leading-normal">*Hanya SKPD yang belum memiliki operator yang akan tampil.</p>
                        <x-input-error :messages="$errors->get('skpd_id')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label for="no_whatsapp" class="block text-xs font-bold text-slate-700 mb-1">
                            Nomor WhatsApp Resmi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">call</span>
                            </div>
                            <input id="no_whatsapp" type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required placeholder="Contoh: 081234567890" 
                                class="w-full pl-10 pr-4 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 leading-normal">*Nomor ini digunakan untuk verifikasi dan menerima notifikasi rekonsiliasi.</p>
                        <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Password & Konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">lock</span>
                                </div>
                                <input id="password" type="password" name="password" required placeholder="Min. 8 karakter" 
                                    class="w-full pl-10 pr-4 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 font-semibold" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                                Konfirmasi Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Ulangi password" 
                                    class="w-full pl-10 pr-4 py-2 text-sm input-solid rounded-xl text-slate-800 placeholder-slate-400 font-medium outline-none">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-600 font-semibold" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full relative group overflow-hidden bg-gradient-to-r from-[#00346f] via-[#00428c] to-[#00346f] hover:from-[#00224d] hover:via-[#00346f] hover:to-[#00224d] text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg shadow-blue-950/20 active:scale-[0.99] transition duration-200 flex items-center justify-center gap-2">
                            <span>Daftarkan Akun Operator</span>
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <!-- Back to Login footer -->
                <div class="mt-3.5 text-center border-t border-slate-200/80 pt-2.5">
                    <p class="text-xs text-slate-500 mb-0.5">Sudah memiliki akun operator?</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#00346f] hover:text-blue-900 transition">
                        <span class="material-symbols-outlined text-[14px]">login</span>
                        <span>Masuk ke Akun Anda</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Note -->
    <footer class="relative z-10 pointer-events-none py-3 text-center px-4 w-full">
        <div class="pointer-events-auto inline-block max-w-full px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 shadow-2xs text-center">
            <p class="text-[10px] sm:text-[11px] font-semibold text-blue-100 leading-normal">
                &copy; {{ date('Y') }} Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Pemerintah Kota Banjarbaru.
            </p>
        </div>
    </footer>

    <!-- Particles.js Script (Local with Fallback) -->
    <script src="{{ asset('js/particles.min.js') }}"></script>
    <script>
        // 1. Inisialisasi TomSelect untuk SKPD
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof TomSelect !== 'undefined') {
                new TomSelect("#skpd_id", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "Cari SKPD..."
                });
            }
        });

        // 2. Konfigurasi Particles Mesh Network Constellation (Bergerak Perlahan & Interaktif)
        if (typeof particlesJS !== 'undefined') {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 85,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle"
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false
                    },
                    "size": {
                        "value": 3,
                        "random": true
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 145,
                        "color": "#ffffff",
                        "opacity": 0.35,
                        "width": 1.1
                    },
                    "move": {
                        "enable": true,
                        "speed": 1.8, // Bergerak perlahan dan mulus
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false
                    }
                },
                "interactivity": {
                    "detect_on": "window", // Mendeteksi kursor di seluruh jendela halaman
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab" // Mode grab: otomatis menghubungkan garis-garis putih ke arah kursor mouse / sentuhan
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 160, // Mode interaksi grab berjarak 160px
                            "line_linked": {
                                "opacity": 0.85 // Garis putih penghubung tebal & jelas
                            }
                        },
                        "push": {
                            "particles_nb": 3
                        }
                    }
                },
                "retina_detect": true
            });

            // Sinkronisasi Interaktivitas Kursor Mouse & Sentuhan Layar (Touch Screen)
            (function() {
                function getPJS() {
                    return (window.pJSDom && window.pJSDom.length > 0) ? window.pJSDom[0].pJS : null;
                }

                function syncPointer(clientX, clientY) {
                    var pJS = getPJS();
                    if (!pJS || !pJS.interactivity) return;
                    var ratio = (pJS.tmp && pJS.tmp.retina) ? pJS.canvas.pxratio : 1;
                    pJS.interactivity.mouse.pos_x = clientX * ratio;
                    pJS.interactivity.mouse.pos_y = clientY * ratio;
                    pJS.interactivity.status = 'mousemove';
                }

                function clearPointer() {
                    var pJS = getPJS();
                    if (!pJS || !pJS.interactivity) return;
                    pJS.interactivity.mouse.pos_x = null;
                    pJS.interactivity.mouse.pos_y = null;
                    pJS.interactivity.status = 'mouseleave';
                }

                // Listener capture pada window untuk kursor mouse
                window.addEventListener('mousemove', function(e) {
                    syncPointer(e.clientX, e.clientY);
                }, { passive: true, capture: true });

                // Listener untuk sentuhan layar (Touch Screen pada HP/Tablet/Laptop Sentuh)
                window.addEventListener('touchstart', function(e) {
                    if (e.touches && e.touches.length > 0) {
                        syncPointer(e.touches[0].clientX, e.touches[0].clientY);
                    }
                }, { passive: true });

                window.addEventListener('touchmove', function(e) {
                    if (e.touches && e.touches.length > 0) {
                        syncPointer(e.touches[0].clientX, e.touches[0].clientY);
                    }
                }, { passive: true });

                window.addEventListener('touchend', function() {
                    clearPointer();
                }, { passive: true });

                window.addEventListener('touchcancel', function() {
                    clearPointer();
                }, { passive: true });
            })();
        }
    </script>
</body>
</html>
