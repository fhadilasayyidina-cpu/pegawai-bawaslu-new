<div>
    <x-header-page title="Data Pegawai" :breadcrumbs="$breadcrumbs">
        {{-- Hide import button for operator --}}
    </x-header-page>

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
</div>
