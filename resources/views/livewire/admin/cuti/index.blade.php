<div>
    <x-header-page title="Data Cuti" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Cuti', 'href' => '/admin/cutis']]">
        <x-slot:actions>
            <x-mary-button
                label="Tambah Cuti"
                icon="o-plus"
                link="/admin/cutis/create"
                class="btn-primary"
            />
        </x-slot:actions>
    </x-header-page>

    <div class="my-4">
        <x-mary-card>
            <!-- Search -->
            <div class="mb-4">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama pegawai, NIP, atau nomor surat..."
                    icon="magnifying-glass"
                />
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>NIP</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal</th>
                            <th>Lama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutis as $cuti)
                            <tr>
                                <td>{{ $cuti->pegawai->nama }}</td>
                                <td>{{ $cuti->pegawai->nip_baru }}</td>
                                <td>
                                    <flux:badge variant="primary">
                                        @switch($cuti->jenis_cuti)
                                            @case('tahunan')
                                                Cuti Tahunan
                                                @break
                                            @case('besar')
                                                Cuti Besar
                                                @break
                                            @case('sakit')
                                                Cuti Sakit
                                                @break
                                            @case('melahirkan')
                                                Cuti Melahirkan
                                                @break
                                            @case('alasan_penting')
                                                Cuti Alasan Penting
                                                @break
                                            @case('luar_tanggungan')
                                                Cuti Luar Tanggungan
                                                @break
                                        @endswitch
                                    </flux:badge>
                                </td>
                                <td>
                                    {{ $cuti->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $cuti->tanggal_selesai->format('d/m/Y') }}
                                </td>
                                <td>{{ $cuti->lama_hari }} hari</td>
                                <td>
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                        <flux:menu>
                                            <flux:menu.item :href="'/admin/cutis/' . $cuti->id . '/details'" icon="eye">
                                                Lihat
                                            </flux:menu.item>
                                            <flux:menu.item :href="'/admin/cutis/' . $cuti->id . '/edit'" icon="pencil">
                                                Edit
                                            </flux:menu.item>
                                            <flux:menu.item
                                                wire:click="delete({{ $cuti->id }})"
                                                icon="trash"
                                                class="text-red-500"
                                            >
                                                Hapus
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-8">
                                    Belum ada data cuti
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-mary-card>
    </div>
</div>
