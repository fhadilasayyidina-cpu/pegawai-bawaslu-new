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
            <x-mary-table>
                <x-slot:thead>
                    <x-mary-tr>
                        <x-mary-th>Pegawai</x-mary-th>
                        <x-mary-th>NIP</x-mary-th>
                        <x-mary-th>Jenis Cuti</x-mary-th>
                        <x-mary-th>Tanggal</x-mary-th>
                        <x-mary-th>Lama</x-mary-th>
                        <x-mary-th>Aksi</x-mary-th>
                    </x-mary-tr>
                </x-slot:thead>

                <x-slot:tbody>
                    @forelse($cutis as $cuti)
                        <x-mary-tr>
                            <x-mary-td>{{ $cuti->pegawai->nama }}</x-mary-td>
                            <x-mary-td>{{ $cuti->pegawai->nip_baru }}</x-mary-td>
                            <x-mary-td>
                                <flux:badge variant="primary">
                                    {{ ucfirst($cuti->jenis_cuti) }}
                                </flux:badge>
                            </x-mary-td>
                            <x-mary-td>
                                {{ $cuti->tanggal_mulai->format('d/m/Y') }} -
                                {{ $cuti->tanggal_selesai->format('d/m/Y') }}
                            </x-mary-td>
                            <x-mary-td>{{ $cuti->lama_hari }} hari</x-mary-td>
                            <x-mary-td>
                                <flux:dropdown>
                                    <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                    <flux:dropdown.menu>
                                        <flux:dropdown.menu.item
                                            href="/admin/cutis/{{ $cuti->id }}"
                                            icon="eye"
                                        >
                                            Lihat
                                        </flux:dropdown.menu.item>
                                        <flux:dropdown.menu.item
                                            href="/admin/cutis/{{ $cuti->id }}/edit"
                                            icon="pencil"
                                        >
                                            Edit
                                        </flux:dropdown.menu.item>
                                        <flux:dropdown.menu.item
                                            wire:click="delete({{ $cuti->id }})"
                                            icon="trash"
                                            style="danger"
                                        >
                                            Hapus
                                        </flux:dropdown.menu.item>
                                    </flux:dropdown.menu>
                                </flux:dropdown>
                            </x-mary-td>
                        </x-mary-tr>
                    @empty
                        <x-mary-tr>
                            <x-mary-td colspan="6" class="text-center text-gray-500 py-8">
                                Belum ada data cuti
                            </x-mary-td>
                        </x-mary-tr>
                    @endforelse
                </x-slot:tbody>
            </x-mary-table>
        </x-mary-card>
    </div>
</div>
