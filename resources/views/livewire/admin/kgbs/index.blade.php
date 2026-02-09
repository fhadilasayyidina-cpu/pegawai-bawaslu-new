<div>
    <x-header-page title="Kenaikan Gaji Berkala (KGB)" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="ghost"
                icon="arrow-up-tray"
                href="/admin/kgbs/import"
            >
                Import
            </flux:button>
            <flux:button variant="ghost" icon="arrow-down-tray">
                Export
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <x-statistic-card title="Total" :value="$this->statistics['total']" color="primary" />
        <x-statistic-card title="Sudah Lewat" :value="$this->statistics['sudah_lewat']" color="danger">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Bulan Ini" :value="$this->statistics['bulan_ini']" color="warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Bulan Depan" :value="$this->statistics['bulan_depan']" color="info">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Lainnya" :value="$this->statistics['lainnya']" color="secondary" />
    </div>

    <div class="my-4">
        <x-mary-card>
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <flux:select
                        wire:model.live="monthsAhead"
                        label="Rentang Waktu"
                    >
                        @foreach($monthsOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:select
                        wire:model.live="kabKota"
                        label="Filter Kabupaten/Kota"
                    >
                        @foreach($kabKotaOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama / NIP</th>
                            <th>KGB Terakhir</th>
                            <th>KGB Berikutnya</th>
                            <!-- <th>Hari Lagi</th> -->
                            <th>Status</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->kgbList as $index => $pegawai)
                            <tr class="{{ $pegawai->days_until_kgb < 0 ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-medium">{{ $pegawai->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $pegawai->nip_baru }}</div>
                                </td>
                                <td>{{ $pegawai->tgl_kgb_terakhir->format('d/m/Y') }}</td>
                                <td>{{ $pegawai->next_kgb_date->format('d/m/Y') }}</td>
                                
                                <td>
                                    <flux:badge>{{ $pegawai->jenis_pegawai }}</flux:badge>
                                </td>
                                <td>{{ $pegawai->unit_kerja ?? '-' }}</td>
                                <td>
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                        <flux:menu>
                                            <flux:menu.item :href="'/admin/pegawais/' . $pegawai->id . '/details'" icon="eye">
                                                Lihat Detail
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-8">
                                    Tidak ada pegawai yang akan KGB dalam periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-mary-card>
    </div>
</div>
