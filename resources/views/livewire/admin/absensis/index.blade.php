<div>
    <x-header-page title="Data Absensi" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button variant="ghost" icon="numbered-list" href="/admin/pegawais/import-id-absensi">
                Import ID Absensi
            </flux:button>
            <flux:button variant="ghost" icon="arrow-up-tray" href="/admin/absensis/import">
                Import
            </flux:button>
            <flux:button variant="primary" icon="plus" href="/admin/absensis/create">
                Tambah Absensi
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-statistic-card title="Total" :value="$this->statistics['total']" color="primary" />
        <x-statistic-card title="Hadir" :value="$this->statistics['hadir']" color="success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Hadir WFO" :value="$this->statistics['hadir_wfo']" color="success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Hadir WFH" :value="$this->statistics['hadir_wfh']" color="success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Sakit" :value="$this->statistics['sakit']" color="warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-dasharray=""
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Izin" :value="$this->statistics['izin']" color="warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Cuti" :value="$this->statistics['cuti']" color="info">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Tidak Hadir" :value="$this->statistics['tidak_hadir']" color="danger">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </x-statistic-card>
    </div>

    <!-- Kalender -->
    <x-mary-calendar months="3" />

    <div class="my-4">
        <x-mary-card>
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari nama pegawai..."
                        icon="magnifying-glass" />
                </div>
                <div>
                    <flux:input wire:model.live="tanggalMulai" type="date" label="Dari Tanggal" />
                </div>
                <div>
                    <flux:input wire:model.live="tanggalAkhir" type="date" label="Sampai Tanggal" />
                </div>
                <div>
                    <flux:select wire:model.live="status" placeholder="Semua Status" label="Status">
                        @foreach ($statusOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @if ($search || $tanggalMulai || $tanggalAkhir || $status)
                        <flux:button wire:click="resetFilters" variant="ghost" size="sm" class="mt-2">
                            Reset Filter
                        </flux:button>
                    @endif
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pegawai</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Scan Masuk</th>
                            <th>Scan Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->absensis as $index => $absensi)
                            <tr>
                                <td>{{ ($this->absensis->currentPage() - 1) * $this->absensis->perPage() + $index + 1 }}
                                </td>
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $absensi->pegawai?->nama }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $absensi->pegawai?->nip_baru ?? $absensi->nip }}</div>
                                </td>
                                <td>{{ $absensi->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    @if ($absensi->jenis_absen)
                                        <flux:badge size="sm"
                                            :color="$absensi->jenis_absen->value === 'WFO' ? 'emerald' : 'blue'">
                                            {{ $absensi->jenis_absen->value }}
                                        </flux:badge>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $absensi->scan_masuk ?? '-' }}</td>
                                <td>{{ $absensi->scan_pulang ?? '-' }}</td>
                                <td>
                                    @switch($absensi->status?->value)
                                        @case('Hadir')
                                            <flux:badge variant="success">Hadir</flux:badge>
                                        @break

                                        @case('Izin')
                                            <flux:badge variant="warning">Izin</flux:badge>
                                        @break

                                        @case('Sakit')
                                            <flux:badge color="amber">Sakit</flux:badge>
                                        @break

                                        @case('Cuti')
                                            <flux:badge variant="info">Cuti</flux:badge>
                                        @break

                                        @case('Tidak Hadir')
                                            <flux:badge variant="danger">Tidak Hadir</flux:badge>
                                        @break

                                        @default
                                            <flux:badge variant="ghost">{{ $absensi->status?->value ?? '-' }}</flux:badge>
                                    @endswitch
                                </td>
                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                                <td>
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                        <flux:menu>
                                            <flux:menu.item :href="'/admin/absensis/' . $absensi->id . '/details'"
                                                icon="eye">
                                                Lihat
                                            </flux:menu.item>
                                            <flux:menu.item :href="'/admin/absensis/' . $absensi->id . '/edit'"
                                                icon="pencil">
                                                Edit
                                            </flux:menu.item>
                                            <flux:menu.item wire:click="delete({{ $absensi->id }})" icon="trash"
                                                class="text-red-500"
                                                wire:confirm="Yakin ingin menghapus data absensi ini?">
                                                Hapus
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-gray-500 py-8">
                                        Belum ada data absensi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($this->absensis->hasPages())
                    <div class="mt-4">
                        {{ $this->absensis->links() }}
                    </div>
                @endif
            </x-mary-card>
        </div>
    </div>
