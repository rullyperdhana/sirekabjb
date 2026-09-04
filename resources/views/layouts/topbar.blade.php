<header id="appTopbar" class="bg-surface-container-lowest dark:bg-surface-dim docked full-width top-0 border-b-2 border-primary dark:border-primary-container flat no shadows flex justify-between items-center h-16 px-4 lg:px-8 lg:ml-72 w-full lg:w-[calc(100%-18rem)] fixed z-30 transition-all duration-300">
    <div class="flex items-center gap-4 lg:gap-8">
        <button onclick="toggleSidebar()" class="text-on-surface-variant hover:text-primary p-1 rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="text-headline-sm font-headline-sm font-black text-primary dark:text-primary-fixed hidden sm:inline">Sistem Rekonsiliasi Kas</span>
        <span class="text-headline-sm font-headline-sm font-black text-primary dark:text-primary-fixed sm:hidden">SIPANDA</span>
    </div>
    <div class="flex items-center gap-4">
        @if(session()->has('tahun_login'))
        <div class="px-3 py-1.5 bg-secondary-container text-on-secondary-container rounded-full text-label-sm font-label-sm font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
            TA. {{ session('tahun_login') }}
        </div>
        @endif

        <button class="text-on-surface-variant hover:text-primary p-1 rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="relative group">
            <button class="flex items-center gap-3 text-on-surface-variant hover:text-primary p-1 pr-3 rounded-full hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                <div class="flex flex-col items-start hidden sm:flex">
                    <span class="text-sm font-semibold leading-tight">{{ Auth::user()->name ?? 'User' }}</span>
                    <span class="text-[11px] text-primary font-bold leading-tight uppercase">{{ Auth::user()->role === 'operator' ? 'Operator: ' . (Auth::user()->skpd->nama ?? 'SKPD') : Auth::user()->role }}</span>
                </div>
            </button>
            <div class="absolute right-0 top-full mt-2 w-64 bg-white border border-outline-variant rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                <div class="p-4 text-left">
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                    <p class="text-[11px] font-bold text-primary mt-2 uppercase">{{ Auth::user()->role === 'operator' ? 'Operator: ' . (Auth::user()->skpd->nama ?? 'SKPD') : Auth::user()->role }}</p>
                </div>
                <hr>
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('profile.two-factor') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <span class="material-symbols-outlined text-[18px]">security</span>
                        <span>Keamanan 2FA</span>
                    </a>
                </div>
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
