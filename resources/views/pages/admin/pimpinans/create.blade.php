<x-layouts.app title="Tambah Pimpinan Baru">
    @volt
    <?php

    new class extends \Livewire\Volt\Component {
        use Livewire\WithFileUploads;

        // ========================
        // State / Properties
        // ========================
        public string $nama = '';
        public string $jabatan = '';
        public string $kab_kota = '';
        public ?string $email = null;
        public ?string $no_hp = null;
        public $foto = null;

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
        // Computed Properties
        // ========================
        public function getKabKotaOptionsProperty(): array
        {
            return app(\App\Services\Pegawai\PegawaiService::class)->getKabKota()->toArray();
        }

        // ========================
        // Actions
        // ========================
        public function save()
        {
            $validated = $this->validate([
                'nama' => ['required', 'string', 'max:255'],
                'jabatan' => ['required', 'in:ketua,anggota'],
                'kab_kota' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'no_hp' => ['nullable', 'string', 'max:20'],
                'foto' => ['nullable', 'image', 'max:2048'], // Max 2MB
            ]);

            // Handle foto upload
            if ($this->foto) {
                $path = $this->foto->store('pimpinan/foto', 'public');
                $validated['foto'] = $path;
            }

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

                    <!-- Foto Upload -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-start gap-6">
                            <!-- Photo Preview -->
                            <div class="flex-shrink-0">
                                @if($foto)
                                    <img src="{{ $foto->temporaryUrl() }}" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700" />
                                @else
                                    <flux:avatar name="Preview" size="lg" class="w-32 h-32 rounded-lg border-2 border-gray-200 dark:border-gray-700" />
                                @endif
                            </div>

                            <!-- Upload Input -->
                            <div class="flex-1">
                                <flux:heading size="lg">Foto Profil</flux:heading>
                                <flux:text class="mb-3 text-sm">
                                    Upload foto pimpinan (format: JPG, PNG, max 2MB)
                                </flux:text>

                                <flux:input
                                    type="file"
                                    wire:model="foto"
                                    accept="image/*"
                                />
                            </div>
                        </div>
                    </div>

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

                    <x-mary-select
                        label="Kabupaten/Kota"
                        wire:model="kab_kota"
                        :options="$this->kabKotaOptions"
                        placeholder="Pilih kabupaten/kota"
                        required
                        searchable
                        icon="o-map-pin"
                    />

                    <x-mary-input
                        label="Email"
                        wire:model="email"
                        type="email"
                        placeholder="Masukkan alamat email"
                        icon="o-envelope"
                    />

                    <x-mary-input
                        label="No HP"
                        wire:model="no_hp"
                        placeholder="Masukkan nomor HP"
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
