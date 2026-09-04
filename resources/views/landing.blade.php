<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiReKa') }}</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-container": "#004a99",
                        "surface-container-high": "#e6e8ea",
                        "secondary-fixed": "#a3f69c",
                        "error": "#ba1a1a",
                        "outline": "#737783",
                        "on-surface": "#191c1e",
                        "surface-tint": "#255dad",
                        "on-secondary-container": "#217128",
                        "on-tertiary-container": "#ffaa4d",
                        "surface-dim": "#d8dadc",
                        "tertiary-fixed-dim": "#ffb870",
                        "on-primary-fixed-variant": "#00458f",
                        "inverse-on-surface": "#eff1f3",
                        "surface": "#f8f9fb",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed": "#2c1600",
                        "on-secondary": "#ffffff",
                        "secondary": "#1b6d24",
                        "tertiary": "#512d00",
                        "background": "#f8f9fb",
                        "on-primary": "#ffffff",
                        "surface-container": "#eceef0",
                        "tertiary-container": "#714000",
                        "inverse-primary": "#abc7ff",
                        "primary-fixed-dim": "#abc7ff",
                        "surface-bright": "#f8f9fb",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#001b3f",
                        "on-secondary-fixed": "#002204",
                        "surface-container-highest": "#e0e3e5",
                        "surface-variant": "#e0e3e5",
                        "inverse-surface": "#2d3133",
                        "on-error": "#ffffff",
                        "secondary-container": "#a0f399",
                        "primary": "#00346f",
                        "outline-variant": "#c2c6d3",
                        "on-surface-variant": "#424751",
                        "secondary-fixed-dim": "#88d982",
                        "primary-fixed": "#d7e2ff",
                        "on-background": "#191c1e",
                        "on-primary-container": "#9bbdff",
                        "on-secondary-fixed-variant": "#005312",
                        "tertiary-fixed": "#ffdcbe",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-fixed-variant": "#693c00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "container-max": "1200px",
                        "margin-desktop": "48px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "baseline": "4px"
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface-container-low text-on-background min-h-screen flex flex-col font-sans">
    
    <!-- Top Section (Hero & Header) with Gradient Color Block -->
    <div class="bg-gradient-to-br from-primary to-[#001a40] text-white pb-28 relative overflow-hidden">
        <!-- Abstract Background Blobs for organic feel -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30 pointer-events-none">
            <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[100%] rounded-full bg-gradient-to-b from-white/30 to-transparent blur-3xl"></div>
            <div class="absolute -bottom-[20%] -left-[10%] w-[40%] h-[80%] rounded-full bg-gradient-to-t from-secondary/40 to-transparent blur-3xl"></div>
        </div>

        <header class="relative z-10 h-24 flex items-center justify-between px-6 lg:px-12">
            <div class="flex items-center gap-4">
                @php
                    $logoApp = ($pengaturan && $pengaturan->logo)
                        ? (Str::startsWith($pengaturan->logo, 'http') ? $pengaturan->logo : asset('storage/' . $pengaturan->logo))
                        : (file_exists(public_path('images/logo_banjarbaru.png')) ? asset('images/logo_banjarbaru.png') : null);
                @endphp
                @if($logoApp)
                    <img src="{{ $logoApp }}" alt="Logo Aplikasi" class="h-12 w-auto object-contain bg-white/10 rounded p-1.5 backdrop-blur-sm shadow-sm">
                @else
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/10 shadow-lg">
                        <span class="material-symbols-outlined text-[24px]" data-weight="fill">account_balance</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-white drop-shadow-sm leading-none mb-1">SiReKa</h1>
                    <p class="text-[11px] text-white/80 font-semibold uppercase tracking-widest">Sistem Rekonsiliasi Kas BPKAD</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="hidden md:flex px-4 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg text-sm font-semibold transition-all items-center gap-2" title="Buku Panduan SiReKa">
                    <span class="material-symbols-outlined text-[20px]">menu_book</span> Panduan
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-6 py-3 bg-white text-primary rounded-xl text-sm font-bold shadow-lg hover:shadow-xl hover:bg-surface-container-lowest hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 bg-white text-primary rounded-xl text-sm font-bold shadow-lg hover:shadow-xl hover:bg-surface-container-lowest hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">login</span> Login
                    </a>
                @endauth
            </div>
        </header>

        <div class="relative z-10 text-center mt-12 mb-8 px-6">
            <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 border border-white/20 text-white/90 text-[10px] font-extrabold tracking-widest uppercase mb-6 shadow-sm backdrop-blur-md">TAHUN ANGGARAN {{ $tahunAktif }}</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight drop-shadow-md">Status Rekonsiliasi SKPD</h2>
            <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto font-light leading-relaxed">Pantau secara real-time progres penyelesaian data keuangan antar instansi di lingkungan Pemerintah Kota Banjarbaru.</p>
        </div>
    </div>

    <!-- Main Content overlaps the dark hero section -->
    <main class="flex-1 w-full max-w-[1200px] mx-auto px-4 sm:px-6 relative z-20 -mt-16 pb-16">
        
        <!-- Search Form Floating Card -->
        <div class="bg-white p-2 rounded-2xl shadow-xl shadow-primary/10 mb-10 border border-outline-variant/30 backdrop-blur-xl">
            <form action="{{ route('landing') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[24px]">search</span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Ketik nama instansi atau SKPD untuk mencari..." class="w-full pl-14 pr-4 py-3.5 bg-transparent border-none focus:ring-0 text-base outline-none text-on-surface placeholder:text-on-surface-variant/50 font-medium">
                </div>
                @if(!empty($search))
                    <a href="{{ route('landing') }}" class="px-5 py-3.5 bg-error-container/50 text-error rounded-xl text-sm font-bold hover:bg-error-container transition-colors flex items-center justify-center" title="Reset Pencarian">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </a>
                @endif
                <button type="submit" class="px-8 py-3.5 bg-primary text-white rounded-xl text-sm font-bold shadow-md hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all">Cari</button>
            </form>
        </div>

        <!-- Table / Cards Section -->
        <div class="w-full overflow-x-auto pb-6">
            <table class="w-full text-left border-collapse border-separate border-spacing-y-4 min-w-[900px]">
                <thead>
                    <tr>
                        <th class="py-2 px-8 text-[11px] text-on-surface-variant/70 font-bold uppercase tracking-widest w-2/5">Nama Instansi / SKPD</th>
                        <th class="py-2 px-6 text-[11px] text-on-surface-variant/70 font-bold uppercase tracking-widest text-center w-1/5">Status Progres</th>
                        <th class="py-2 px-6 text-[11px] text-on-surface-variant/70 font-bold uppercase tracking-widest">Bulan Diselesaikan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skpdRekonStatus as $stat)
                        <tr class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 group relative {{ $stat['bulan_selesai'] == 12 ? 'ring-1 ring-secondary/30' : 'border border-transparent hover:border-primary/10' }}">
                            <td class="py-5 px-8 bg-white rounded-l-2xl {{ $stat['bulan_selesai'] == 12 ? 'border-l-4 border-l-secondary' : 'border-l-4 border-l-transparent' }}">
                                <div class="text-on-surface font-bold text-base md:text-lg">{{ $stat['nama'] }}</div>
                            </td>
                            <td class="py-5 px-6 text-center bg-white">
                                @if($stat['bulan_selesai'] == 12)
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[11px] font-bold bg-secondary/10 text-secondary border border-secondary/20 uppercase tracking-wide">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Tuntas 100%
                                    </span>
                                @elseif($stat['bulan_selesai'] > 0)
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[11px] font-bold bg-tertiary-container/20 text-tertiary-container border border-tertiary-container/20 uppercase tracking-wide">
                                        <span class="material-symbols-outlined text-[16px]">pending</span> {{ $stat['bulan_selesai'] }} Bulan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[11px] font-bold bg-surface-container-highest text-on-surface-variant uppercase tracking-wide">
                                        <span class="material-symbols-outlined text-[16px]">radio_button_unchecked</span> Belum
                                    </span>
                                @endif
                            </td>
                            <td class="py-5 px-6 bg-white rounded-r-2xl">
                                <div class="flex flex-wrap gap-2">
                                    @for($i = 1; $i <= 12; $i++)
                                        @if(in_array($i, $stat['bulan_list']))
                                            <span class="inline-flex w-8 h-8 md:w-9 md:h-9 rounded-full items-center justify-center bg-primary text-white text-xs font-bold shadow-md shadow-primary/30 transform hover:scale-110 transition-transform cursor-default">{{ $i }}</span>
                                        @else
                                            <span class="inline-flex w-8 h-8 md:w-9 md:h-9 rounded-full items-center justify-center bg-surface-container-low text-on-surface-variant/40 text-xs font-semibold border border-outline-variant/30">{{ $i }}</span>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-16 text-center">
                                <div class="inline-flex flex-col items-center justify-center text-on-surface-variant/50">
                                    <span class="material-symbols-outlined text-[64px] mb-4 opacity-50">search_off</span>
                                    <p class="text-xl font-semibold text-on-surface-variant">Tidak ada data SKPD ditemukan.</p>
                                    <p class="text-sm mt-2">Coba gunakan kata kunci pencarian yang lain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-center">
            <!-- Custom styling to ensure pagination matches organic theme (if tailwind paginator is used, it will automatically look better on the bg-surface-container-low background) -->
            {{ $skpdsPaginated->links() }}
        </div>
    </main>

    <footer class="bg-white border-t border-outline-variant/20 py-8 mt-auto">
        <div class="max-w-[1200px] mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-on-surface-variant font-medium">
            <p>&copy; {{ date('Y') }} BPKAD Kota Banjarbaru. All rights reserved.</p>
            <p class="text-[11px] uppercase tracking-widest opacity-80">Developed by <strong class="text-primary font-bold">rully.perdhana@gmail.com</strong></p>
        </div>
    </footer>
</body>

</html>