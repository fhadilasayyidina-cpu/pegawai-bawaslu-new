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
</div>
