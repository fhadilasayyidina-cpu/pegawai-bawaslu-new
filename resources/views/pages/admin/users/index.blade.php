<x-layouts.app title="Manajemen User">
    @volt
    <?php
    // 1. Semua Import diletakkan tepat di sini
    use Livewire\Volt\Component;
    use App\Services\User\UserService;

    // 2. Deklarasi Class Anonim
    new class extends Component {

        // State / Properties
        public ?string $search = '';
        public array $tableHeaders = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Nama User'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'access_scope', 'label' => 'Akses Wilayah'],
        ];

        public array $breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'User Management', 'link' => '#'],
        ];

        // Method untuk ambil data (Reaktif terhadap $search)
        public function users()
        {
            return app(\App\Services\User\UserService::class)->getAllUser(
                nameOrEmail: $this->search
            );
        }

        // Contoh Aksi
        public function delete($id)
        {
            app(\App\Services\User\UserService::class)->deleteUser($id);
            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'User berhasil dihapus!'
            ]);
        }
    };
    ?>

    {{-- 3. Bagian Tampilan (View) --}}
    <div>
        {{-- Header Page (Anonymous Component) --}}
        <x-header-page title="Manajemen Akses User" :breadcrumbs="$breadcrumbs">
            <x-slot:actions>
                <x-mary-button label="Tambah Baru" icon="o-plus" link="/admin/users/create" class="btn-primary" />
            </x-slot:actions>
        </x-header-page>

        {{-- Filter Section --}}
        <div class="my-4 bg-base-200 p-4 rounded-lg">
            <x-mary-input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari berdasarkan nama atau email..." 
                icon="o-magnifying-glass" 
            />
        </div>

        {{-- Tabel Utama --}}
        <x-mary-table
            :headers="$tableHeaders"
            :rows="$this->users()"
            striped
            with-pagination
            per-page="10"
        >
            {{-- Slot untuk aksi per baris --}}
            @scope('actions', $user)
                <x-mary-button icon="o-pencil" link="/admin/users/{{ $user->id }}/edit" class="btn-ghost btn-sm" />
                <x-mary-button icon="o-trash" wire:click="delete({{ $user->id }})" class="btn-ghost text-error btn-sm" wire:confirm="Yakin mau hapus?" />
            @endscope
        </x-mary-table>
    </div>
    
    


    @endvolt
</x-layouts.app>