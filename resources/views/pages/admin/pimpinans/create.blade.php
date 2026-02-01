<x-layouts.app title="Tambah Pimpinan Baru">
    @volt
    <?php

    new class extends \Livewire\Volt\Component {

        // ========================
        // State / Properties
        // ========================
        public string $nama = '';
        public string $jabatan = '';
        public string $kab_kota = '';
        public string $email = '';
        public string $no_hp = '';

        public array $breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Data Pimpinan', 'link' => '/admin/pimpinans'],
            ['label' => 'Tambah Pimpinan', 'link' => '#'],
        ];

        public array $jabatanOptions = [
            ['id' => 'ketua', 'name' => 'Ketua'],
            ['id' => 'anggota', 'name' => 'Anggota'],
        ];

        // ========================
        // Actions
        // ========================
        public function save()
        {
            $validated = $this->validate([
                'nama' => ['required', 'string', 'max:255'],
                'jabatan' => ['required', 'in:ketua,anggota'],
                'kab_kota' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'no_hp' => ['required', 'string', 'max:20'],
            ]);

            // FULLY QUALIFIED NAME
            app(\App\Services\Pimpinan\PimpinanService::class)
                ->createPimpinan($validated);

            // Notyf Toast (dari app.js)
            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'Pimpinan baru berhasil ditambahkan!'
            ]);

            return $this->redirect('/admin/pimpinans');
        }
    };
    ?>

    <div>
        {{-- Header Page --}}
        <x-header-page title="Tambah Pimpinan Baru" :breadcrumbs="$breadcrumbs" />

        {{-- Form Section --}}
        <div class="max-w-2xl">
            <x-mary-card>
                <x-mary-form wire:submit="save">

                    <x-mary-input
                        label="Nama"
                        wire:model="nama"
                        placeholder="Masukkan nama lengkap"
                        required
                        icon="o-user"
                    />

                    <x-mary-select
                        label="Jabatan"
                        wire:model="jabatan"
                        :options="$jabatanOptions"
                        placeholder="Pilih jabatan"
                        required
                        icon="o-user-group"
                    />

                    <x-mary-input
                        label="Kabupaten/Kota"
                        wire:model="kab_kota"
                        placeholder="Masukkan nama kabupaten/kota"
                        required
                        icon="o-map-pin"
                    />

                    <x-mary-input
                        label="Email"
                        wire:model="email"
                        type="email"
                        placeholder="Masukkan alamat email"
                        required
                        icon="o-envelope"
                    />

                    <x-mary-input
                        label="No HP"
                        wire:model="no_hp"
                        placeholder="Masukkan nomor HP"
                        required
                        icon="o-phone"
                    />

                    <x-slot:actions>
                        <x-mary-button
                            label="Batal"
                            link="/admin/pimpinans"
                            variant="ghost"
                        />
                        <x-mary-button
                            label="Simpan"
                            class="btn-primary"
                            icon="o-check"
                            spinner="save"
                            wire:click="save"
                        />
                    </x-slot:actions>

                </x-mary-form>
            </x-mary-card>
        </div>
    </div>
    @endvolt
</x-layouts.app>
