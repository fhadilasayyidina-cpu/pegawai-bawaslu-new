<div>
    <x-header-page title="Manajemen Pegawai" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-modal wire:model="myModal3" title="Payment confirmation" persistent separator>
                
            
            <x-mary-form wire:submit.prevent="import" no-separator>
                <x-mary-file
                    wire:model="file"
                    label="Receipt"
                    hint="Only xlsx"
                    accept=".xlsx"
                />

                <x-slot:actions>
                    <x-mary-button
                        label="Import"
                        class="btn-primary"
                        type="submit"
                        spinner="import"
                    />
                </x-slot:actions>
            </x-mary-form>


                <x-slot:actions>
                    <x-mary-button label="Cancel" @click="$wire.myModal3 = false" />
                </x-slot:actions>
            </x-modal>

            <x-mary-button
                label="Import Data"
                icon="o-arrow-up-tray"
                wire:click="$set('myModal3', true)"
                class="btn-secondary"
            />
        </x-slot:actions>
    </x-header-page>

    <!-- Search and Filters -->
    <div class="my-4 bg-base-200 p-4 rounded-lg space-y-4">
        <!-- Search Input -->
        <x-mary-input
            wire:model.live.debounce.300ms="search"
            placeholder="Cari berdasarkan nama atau NIP..."
            icon="o-magnifying-glass"
        />

        <!-- Filters Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-mary-select
                label="Kabupaten Kota"
                wire:model.live="kabKota"
                :options="$kabKotaOptions"
                icon="o-map"
                placeholder="Semua Kabupaten/Kota"
            />

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
        link="/admin/pegawais/{id}/details"
    >
        @scope('cell_nomor', $pegawai)
            {{ ($this->pegawais->currentPage() - 1) * $this->pegawais->perPage() + $loop->iteration }}
        @endscope

        @scope('actions', $pegawai)
            <x-mary-button
                icon="o-trash"
                wire:click="delete({{ $pegawai->id }})"
                class="btn-ghost text-error btn-sm"
                wire:confirm="Yakin mau hapus?"
            />
        @endscope
    </x-mary-table>
</div>
