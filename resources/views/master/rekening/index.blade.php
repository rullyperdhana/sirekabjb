<x-app-layout>
    <div class="max-w-[1200px] mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex justify-between items-end pb-4 border-b border-outline-variant">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-primary">Master Rekening</h2>
                <p class="text-body-lg font-body-lg text-on-surface-variant mt-1">Kelola data rekening bank dan kode buku besar (GL).</p>
            </div>
            @if(in_array(auth()->user()->role, ['admin', 'operator']))
            <a href="{{ route('rekening.create') }}" class="bg-primary text-on-primary hover:bg-primary/90 px-4 py-2 rounded-lg flex items-center gap-2 font-label-sm text-label-sm shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Rekening Baru
            </a>
            @endif
        </div>

        <!-- Filters & Search for Table -->
        <div class="flex justify-between items-center bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm">
            <div class="flex gap-4">
                <div class="relative">
                    <select class="appearance-none bg-surface-container-low border border-outline-variant rounded-md py-2 pl-4 pr-10 text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <option>Semua Bank</option>
                        <option>Bank Kalsel</option>
                        <option>BRI</option>
                        <option>BNI</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">arrow_drop_down</span>
                </div>
                <div class="relative">
                    <select class="appearance-none bg-surface-container-low border border-outline-variant rounded-md py-2 pl-4 pr-10 text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <option>Semua Status</option>
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">arrow_drop_down</span>
                </div>
            </div>
            <div class="relative w-72">
                 <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                 <input type="text" placeholder="Cari nama atau nomor rekening..." class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
        </div>

        <!-- Data Table Container -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface-variant">Nama Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface-variant">Nomor Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface-variant">Bank</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface-variant">SKPD</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface-variant">Status</th>
                            <th class="py-3 px-4 font-label-sm text-label-sm text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($rekenings as $rekening)
                        <tr class="hover:bg-surface-container/50 transition-colors">
                            <td class="py-3 px-4 font-body-md text-on-surface font-semibold">{{ $rekening->nama }}</td>
                            <td class="py-3 px-4 font-data-tabular text-data-tabular text-on-surface-variant">{{ $rekening->nomor }}</td>
                            <td class="py-3 px-4 font-body-md text-on-surface-variant">{{ $rekening->bank }}</td>
                            <td class="py-3 px-4 font-body-md text-on-surface">{{ $rekening->skpd->nama ?? '-' }}</td>
                            <td class="py-3 px-4">
                                @if($rekening->status)
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full bg-secondary/10 text-secondary text-[11px] font-bold tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                    AKTIF
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full bg-surface-variant/50 text-on-surface-variant text-[11px] font-bold tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                    TIDAK AKTIF
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if(in_array(auth()->user()->role, ['admin', 'operator']))
                                    <a href="{{ route('rekening.edit', $rekening->id) }}" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-primary-container/20 text-primary transition-colors border border-transparent hover:border-primary-container">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('rekening.destroy', $rekening->id) }}" method="POST" class="inline-block form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-error-container/50 text-error transition-colors border border-transparent hover:border-error-container">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-on-surface-variant">Belum ada data Rekening.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-surface-container-low border-t border-outline-variant px-4 py-3">
                {{ $rekenings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
