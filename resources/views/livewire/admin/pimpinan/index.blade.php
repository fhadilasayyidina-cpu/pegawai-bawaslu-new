<div>
    <x-header-page title="Data Pimpinan" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-mary-button
                label="Tambah Baru"
                icon="o-plus"
                link="/admin/pimpinans/create"
                class="btn-primary"
            />
        </x-slot:actions>
    </x-header-page>

    <!-- Tabs -->
    <x-mary-tabs wire:model="selectedTab" selected="sulsel">
        <x-mary-tab name="sulsel" label="Sulawesi Selatan" icon="o-map-pin">
            <!-- Search and Filters -->
            <div class="my-4 bg-base-200 p-4 rounded-lg">
                <x-mary-input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari berdasarkan nama..."
                    icon="o-magnifying-glass"
                />
                <x-mary-select
                    label="Kabupaten Kota"
                    wire:model.live="kabKota"
                    :options="$kabKotaOptions"
                    icon="o-map"
                    placeholder="Semua Kabupaten/Kota"
                />
            </div>

            <x-mary-table
                :headers="$tableHeaders"
                :rows="$this->pimpinans"
                striped
                with-pagination
                link="/admin/pimpinans/{id}/details"
            >
                @scope('cell_nomor', $pimpinan)
                    {{ ($this->pimpinans->currentPage() - 1) * $this->pimpinans->perPage() + $loop->iteration }}
                @endscope

                @scope('cell_foto', $pimpinan)
                    @if($pimpinan->foto)
                        <img src="{{ $pimpinan->foto_url }}" alt="{{ $pimpinan->nama }}" class="w-12 h-12 rounded-full object-cover" />
                    @else
                        <flux:avatar :name="$pimpinan->nama" size="sm" class="w-12 h-12" />
                    @endif
                @endscope

                @scope('cell_jabatan', $pimpinan)
                    {{ $pimpinan->jabatan->value == 'ketua' ? 'Ketua' : 'Anggota' }}
                @endscope

                @scope('actions', $pimpinan)
                    <x-mary-button
                        icon="o-trash"
                        wire:click="delete({{ $pimpinan->id }})"
                        class="btn-ghost text-error btn-sm"
                        wire:confirm="Yakin mau hapus?"
                    />
                @endscope
            </x-mary-table>
        </x-mary-tab>

        <x-mary-tab name="lainnya" label="Kab/Kota Lainnya" icon="o-globe-americas">
            <!-- Search and Filters -->
            <div class="my-4 bg-base-200 p-4 rounded-lg">
                <x-mary-input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari berdasarkan nama..."
                    icon="o-magnifying-glass"
                />
                <x-mary-select
                    label="Kabupaten Kota"
                    wire:model.live="kabKota"
                    :options="$kabKotaOptions"
                    icon="o-map"
                    placeholder="Semua Kabupaten/Kota"
                />
            </div>

            <x-mary-table
                :headers="$tableHeaders"
                :rows="$this->pimpinans"
                striped
                with-pagination
                link="/admin/pimpinans/{id}/details"
            >
                @scope('cell_nomor', $pimpinan)
                    {{ ($this->pimpinans->currentPage() - 1) * $this->pimpinans->perPage() + $loop->iteration }}
                @endscope

                @scope('cell_foto', $pimpinan)
                    @if($pimpinan->foto)
                        <img src="{{ $pimpinan->foto_url }}" alt="{{ $pimpinan->nama }}" class="w-12 h-12 rounded-full object-cover" />
                    @else
                        <flux:avatar :name="$pimpinan->nama" size="sm" class="w-12 h-12" />
                    @endif
                @endscope

                @scope('cell_jabatan', $pimpinan)
                    {{ $pimpinan->jabatan->value == 'ketua' ? 'Ketua' : 'Anggota' }}
                @endscope

                @scope('actions', $pimpinan)
                    <x-mary-button
                        icon="o-trash"
                        wire:click="delete({{ $pimpinan->id }})"
                        class="btn-ghost text-error btn-sm"
                        wire:confirm="Yakin mau hapus?"
                    />
                @endscope
            </x-mary-table>
        </x-mary-tab>
    </x-mary-tabs>
</div>
