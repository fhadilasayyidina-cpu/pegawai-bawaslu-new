<div>
    <x-header-page title="Kenaikan Gaji Berkala (KGB)" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="primary"
                icon="plus"
                href="/admin/kgbs/create-pns"
            >
                Input KGB PNS
            </flux:button>
            <flux:button
                variant="primary"
                icon="plus"
                href="/admin/kgbs/create-pppk"
            >
                Input KGB PPPK
            </flux:button>
            <flux:button
                variant="ghost"
                icon="arrow-up-tray"
                href="/admin/kgbs/import"
            >
                Import
            </flux:button>
            <flux:button
                variant="ghost"
                icon="arrow-down-tray"
                wire:click="export"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Export</span>
                <span wire:loading>Exporting...</span>
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <x-statistic-card title="Total KGB" :value="$this->statistics['total']" color="primary" />
        <x-statistic-card title="KGB PNS" :value="$this->statistics['pns']" color="info">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="KGB PPPK" :value="$this->statistics['pppk']" color="warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Bulan Ini" :value="$this->statistics['bulan_ini']" color="success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Bulan Depan" :value="$this->statistics['bulan_depan']" color="secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-statistic-card>
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
                            <th>Jenis</th>
                            <th>Nomor Naskah</th>
                            <th>Tanggal Naskah</th>
                            <th>TMT Baru</th>
                            <th>KGB Berikutnya</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->kgbList as $index => $kgb)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-medium">{{ $kgb->pegawai->nama ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $kgb->pegawai->nip_baru ?? '-' }}</div>
                                </td>
                                <td>
                                    <flux:badge :color="$kgb->jenis_kgb === 'PNS' ? 'blue' : 'emerald'">{{ $kgb->jenis_kgb }}</flux:badge>
                                </td>
                                <td class="font-mono text-sm">{{ $kgb->nomor_naskah }}</td>
                                <td>{{ $kgb->tanggal_naskah?->format('d/m/Y') ?? '-' }}</td>
                                <td><span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $kgb->tmt_baru?->format('d/m/Y') ?? '-' }}</span></td>
                                <td>{{ $kgb->next_kgb_date?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $kgb->pegawai->unit_kerja ?? '-' }}</td>
                                <td>
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                        <flux:menu>
                                            @if(($kgb->jenis_kgb ?? '') === 'PNS')
                                                <flux:menu.item :href="route('admin.kgbs.pns-pdf', $kgb->data ?? [])" target="_blank" icon="printer">
                                                    Cetak PDF
                                                </flux:menu.item>
                                            @else
                                                <flux:menu.item :href="route('admin.kgbs.pppk-pdf', $kgb->data ?? [])" target="_blank" icon="printer">
                                                    Cetak PDF
                                                </flux:menu.item>
                                            @endif
                                            @if($kgb->pegawai_id)
                                                <flux:menu.item :href="'/admin/pegawais/' . $kgb->pegawai_id . '/details'" icon="eye">
                                                    Detail Pegawai
                                                </flux:menu.item>
                                            @endif
                                            <flux:menu.separator />
                                            <flux:menu.item
                                                wire:click="delete({{ $kgb->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus riwayat KGB ini?"
                                                icon="trash"
                                                variant="danger"
                                            >
                                                Hapus
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-8">
                                    Belum ada data KGB yang diinputkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-mary-card>
    </div>
</div>
