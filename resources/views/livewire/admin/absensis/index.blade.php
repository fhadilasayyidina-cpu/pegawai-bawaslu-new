<div>
    <x-header-page title="Data Absensi" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="primary"
                icon="plus"
                href="/admin/absensis/create{{ $this->pegawaiId ? '?pegawai_id=' . $this->pegawaiId : '' }}"
            >
                Tambah Absensi
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="my-4">
        <x-mary-card>
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama pegawai..."
                        icon="magnifying-glass"
                    />
                </div>
                <div>
                    <flux:input
                        wire:model.live="tanggalMulai"
                        type="date"
                        label="Dari Tanggal"
                    />
                </div>
                <div>
                    <flux:input
                        wire:model.live="tanggalAkhir"
                        type="date"
                        label="Sampai Tanggal"
                    />
                </div>
                <div>
                    <flux:select
                        wire:model.live="pegawaiId"
                        placeholder="Semua Pegawai"
                        label="Pegawai"
                    >
                        @foreach($pegawaiOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:select
                        wire:model.live="status"
                        placeholder="Semua Status"
                        label="Status"
                    >
                        @foreach($statusOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @if($search || $tanggalMulai || $tanggalAkhir || $pegawaiId || $status)
                        <flux:button
                            wire:click="resetFilters"
                            variant="ghost"
                            size="sm"
                            class="mt-2"
                        >
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
                            <th>Tanggal</th>
                            <th>Pegawai</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->absensis as $index => $absensi)
                            <tr>
                                <td>{{ ($this->absensis->currentPage() - 1) * $this->absensis->perPage() + $index + 1 }}</td>
                                <td>{{ $absensi->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    <div>{{ $absensi->pegawai->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $absensi->pegawai->nip_baru }}</div>
                                </td>
                                <td>
                                    @switch($absensi->status)
                                        @case('Hadir')
                                            <flux:badge variant="success">Hadir</flux:badge>
                                            @break
                                        @case('Izin')
                                            <flux:badge variant="warning">Izin</flux:badge>
                                            @break
                                        @case('Sakit')
                                            <flux:badge variant="danger">Sakit</flux:badge>
                                            @break
                                        @case('Cuti')
                                            <flux:badge variant="info">Cuti</flux:badge>
                                            @break
                                        @case('Bolos')
                                            <flux:badge variant="danger">Bolos</flux:badge>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                                <td>
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" />
                                        <flux:menu>
                                            <flux:menu.item :href="'/admin/absensis/' . $absensi->id . '/details'" icon="eye">
                                                Lihat
                                            </flux:menu.item>
                                            <flux:menu.item :href="'/admin/absensis/' . $absensi->id . '/edit'" icon="pencil">
                                                Edit
                                            </flux:menu.item>
                                            <flux:menu.item
                                                wire:click="delete({{ $absensi->id }})"
                                                icon="trash"
                                                class="text-red-500"
                                                wire:confirm="Yakin ingin menghapus data absensi ini?"
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
                                    Belum ada data absensi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($this->absensis->hasPages())
                <div class="mt-4">
                    {{ $this->absensis->links() }}
                </div>
            @endif
        </x-mary-card>
    </div>
</div>
