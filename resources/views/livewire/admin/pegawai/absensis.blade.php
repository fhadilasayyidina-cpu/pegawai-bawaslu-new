<div>
    <x-header-page title="Absensi: {{ $pegawai->nama }}" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="primary"
                icon="plus"
                href="/admin/absensis/create?pegawai_id={{ $pegawai->id }}"
            >
                Tambah Absensi
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <x-statistic-card title="Total" :value="$this->statistics['total']" color="primary" />
        <x-statistic-card title="Hadir" :value="$this->statistics['hadir']" color="success">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Izin" :value="$this->statistics['izin']" color="warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Cuti" :value="$this->statistics['cuti']" color="info">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </x-statistic-card>
        <x-statistic-card title="Tidak Hadir" :value="$this->statistics['tidak_hadir']" color="danger">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </x-statistic-card>
    </div>

    <div class="my-4">
        <x-mary-card>
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
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
                        wire:model.live="status"
                        placeholder="Semua Status"
                        label="Status"
                    >
                        @foreach($statusOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="flex items-end">
                    @if($tanggalMulai || $tanggalAkhir || $status)
                        <flux:button
                            wire:click="resetFilters"
                            variant="ghost"
                            size="sm"
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
                                    @switch($absensi->status)
                                        @case('Hadir')
                                            <flux:badge variant="success">Hadir</flux:badge>
                                            @break
                                        @case('Izin')
                                            <flux:badge variant="warning">Izin</flux:badge>
                                            @break
                                        @case('Cuti')
                                            <flux:badge variant="info">Cuti</flux:badge>
                                            @break
                                        @case('Tidak Hadir')
                                            <flux:badge variant="danger">Tidak Hadir</flux:badge>
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
                                <td colspan="5" class="text-center text-gray-500 py-8">
                                    Belum ada data absensi untuk {{ $pegawai->nama }}
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
