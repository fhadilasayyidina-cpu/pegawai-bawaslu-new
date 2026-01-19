<x-layouts.app title="Manajemen Pegawai">
    @volt
    <?php
    use Livewire\Volt\Component;
    use App\Services\Pegawai\PegawaiService;

    new class extends Component {
        public ?string $search = '';

        public array $tableHeaders = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'nip_baru', 'label' => 'NIP'],
            ['key' => 'nama', 'label' => 'Nama'],
            ['key' => 'jabatan_nama', 'label' => 'Jabatan'],
            ['key' => 'unor_nama', 'label' => 'Unit Organisasi'],
        ];

        public array $breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Manajemen Pegawai', 'link' => '#'],
        ];

        public function pegawais()
        {
            return app(\App\Services\Pegawai\PegawaiService::class)->getAllPegawai(
                search: $this->search
            );
        }

        public function delete($id): void
        {
            app(\App\Services\Pegawai\PegawaiService::class)->deletePegawai($id);
            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'Pegawai berhasil dihapus!'
            ]);
        }
    };
    ?>

    <div>
        <x-header-page title="Manajemen Pegawai" :breadcrumbs="$breadcrumbs">
            <x-slot:actions>
                <x-mary-button label="Import Data" icon="o-arrow-up-tray" link="/admin/pegawais/import" class="btn-secondary" />
            </x-slot:actions>
        </x-header-page>

        <div class="my-4 bg-base-200 p-4 rounded-lg">
            <x-mary-input
                wire:model.live.debounce.300ms="search"
                placeholder="Cari berdasarkan nama, NIP, atau NIK..."
                icon="o-magnifying-glass"
            />
        </div>

        <x-mary-table
            :headers="$tableHeaders"
            :rows="$this->pegawais()"
            striped
            with-pagination
        >
            @scope('cell_nip_baru', $pegawai)
                {{ $pegawai->nip_baru ?? '-' }}
            @endscope

            @scope('actions', $pegawai)
                <div class="flex gap-1">
                    <x-mary-button icon="eye-o" link="/admin/pegawais/{{ $pegawai->id }}" class="btn-ghost btn-sm" />
                    <x-mary-button icon="o-trash" wire:click="delete({{ $pegawai->id }})" class="btn-ghost text-error btn-sm" wire:confirm="Yakin mau hapus?" />
                </div>
            @endscope
        </x-mary-table>
    </div>

    @endvolt
</x-layouts.app>
