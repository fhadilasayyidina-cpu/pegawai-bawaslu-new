<div>
    <x-header-page title="Data Pimpinan" :breadcrumbs="$breadcrumbs">
        {{-- Hide create button for operator --}}
    </x-header-page>

    <!-- Search and Filters -->
    <div class="my-4 bg-base-200 p-4 rounded-lg">
        <x-mary-input
            wire:model.live.debounce.300ms="search"
            placeholder="Cari berdasarkan nama..."
            icon="o-magnifying-glass"
        />
        {{-- Hide kab_kota filter for operator since it's auto-filtered --}}
    </div>

    <x-mary-table
        :headers="$tableHeaders"
        :rows="$this->pimpinans"
        striped
        with-pagination
        link="/operator/pimpinans/{id}/details"
    >
        @scope('cell_nomor', $pimpinan)
            {{ ($this->pimpinans->currentPage() - 1) * $this->pimpinans->perPage() + $loop->iteration }}
        @endscope

        @scope('cell_jabatan', $pimpinan)
            {{ $pimpinan->jabatan->value == 'ketua' ? 'Ketua' : 'Anggota' }}
        @endscope

        {{-- Hide delete button for operator --}}
    </x-mary-table>
</div>
