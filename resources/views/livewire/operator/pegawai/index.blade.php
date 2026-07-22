<div>
    <x-header-page title="Data Pegawai" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-mary-button
                label="Ulang Tahun Pegawai"
                icon="o-cake"
                wire:click="$set('showBirthdayModal', true)"
                class="btn-primary"
            />
        </x-slot:actions>
    </x-header-page>

    {{-- Birthday Reminder --}}
    @if($birthdayEmployees->isNotEmpty())
        <div class="birthday-banner my-4 rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #a6192e 0%, #7b1822 40%, #e5ad25 100%); box-shadow: 0 8px 32px rgba(166,25,46,0.35);">
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="flex-shrink-0 text-4xl animate-bounce">🎂</div>
                <div class="flex-1">
                    <h3 class="text-white font-bold text-base mb-1 flex items-center gap-2">
                        🎉 Selamat Ulang Tahun Hari Ini!
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30">
                            {{ $birthdayEmployees->count() }} Pegawai
                        </span>
                    </h3>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($birthdayEmployees as $emp)
                            <a href="/operator/pegawais/{{ $emp->id }}/details"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400 text-slate-900 font-bold border border-amber-300 shadow-md animate-pulse hover:scale-105 transition-all">
                                <span>🎉</span>
                                <span>{{ $emp->nama }}</span>
                                @if($emp->jabatan_nama)
                                    <span class="opacity-80">— {{ $emp->jabatan_nama }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="flex-shrink-0 text-white/30 text-5xl font-black leading-none">🎈</div>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="my-4 bg-base-200 p-4 rounded-lg space-y-4">
        <!-- Search Input -->
        <x-mary-input
            wire:model.live.debounce.300ms="search"
            placeholder="Cari berdasarkan nama atau NIP..."
            icon="o-magnifying-glass"
        />

        <!-- Filters Grid - hide kab_kota filter for operator since it's auto-filtered -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-mary-select
                label="Rentang Umur"
                wire:model.live="rangeUmur"
                :options="$rangeUmurOptions"
                icon="o-calendar"
                placeholder="Semua Umur"
            />

            <x-mary-select
                label="Jenis Kelamin"
                wire:model.live="jenisKelamin"
                :options="$jenisKelaminOptions"
                icon="o-user"
                placeholder="Semua Jenis Kelamin"
            />

            <x-mary-select
                label="Agama"
                wire:model.live="agama"
                :options="$agamaOptions"
                icon="o-academic-cap"
                placeholder="Semua Agama"
            />
        </div>
    </div>

    <x-mary-table
        :headers="$tableHeaders"
        :rows="$this->pegawais"
        striped
        with-pagination
        link="/operator/pegawais/{id}/details"
    >
        @scope('cell_nomor', $pegawai)
            {{ ($this->pegawais->currentPage() - 1) * $this->pegawais->perPage() + $loop->iteration }}
        @endscope

        {{-- Hide delete button for operator --}}
    </x-mary-table>

    <!-- Modal Ulang Tahun Pegawai Per Bulan -->
    <x-modal wire:model="showBirthdayModal" title="🎂 Daftar Ulang Tahun Pegawai Per Bulan" class="backdrop-blur" box-class="max-w-4xl">
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                <div class="w-full sm:w-64">
                    <x-mary-select
                        label="Pilih Bulan"
                        wire:model.live="selectedBirthdayMonth"
                        :options="$monthOptions"
                        icon="o-calendar"
                    />
                </div>
                <div class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    Total: <span class="font-bold text-amber-600 dark:text-amber-400 text-base">{{ $this->monthlyBirthdayEmployees->count() }}</span> Pegawai Ulang Tahun
                </div>
            </div>

            <div class="overflow-x-auto max-h-[60vh]">
                <table class="table table-zebra w-full text-sm">
                    <thead class="sticky top-0 bg-base-100 z-10 shadow-xs">
                        <tr>
                            <th class="w-1">No</th>
                            <th>Nama / NIP</th>
                            <th>Tanggal Ulang Tahun</th>
                            <th>Jabatan</th>
                            <th>Kabupaten / Kota</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->monthlyBirthdayEmployees as $index => $emp)
                            @php
                                $isToday = $emp->tgl_lahir && $emp->tgl_lahir->day === now()->day && $emp->tgl_lahir->month === now()->month;
                                $age = $emp->tgl_lahir ? $emp->tgl_lahir->age : null;
                            @endphp
                            <tr class="{{ $isToday ? 'bg-amber-500/10 font-medium' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                        <span>{{ $emp->nama }}</span>
                                        @if($isToday)
                                            <span class="badge badge-warning text-xs font-bold animate-pulse">Hari Ini 🎉</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $emp->nip_baru ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-amber-700 dark:text-amber-400">
                                        {{ $emp->tgl_lahir?->translatedFormat('d F Y') ?? '-' }}
                                    </div>
                                    @if($age !== null)
                                        <div class="text-xs text-slate-500">{{ $age }} Tahun</div>
                                    @endif
                                </td>
                                <td>{{ $emp->jabatan_nama ?? '-' }}</td>
                                <td>{{ $emp->kab_kota ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="/operator/pegawais/{{ $emp->id }}/details"
                                       class="btn btn-ghost btn-xs text-info"
                                       title="Lihat Detail">
                                        <x-mary-icon name="o-eye" class="w-4 h-4" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-500">
                                    Tidak ada pegawai yang berulang tahun pada bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Tutup" @click="$wire.showBirthdayModal = false" />
        </x-slot:actions>
    </x-modal>
</div>
