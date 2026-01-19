<x-layouts.app title="Tambah User Baru">
    @volt
    <?php

    new class extends \Livewire\Volt\Component {

        // ========================
        // State / Properties
        // ========================
        public string $name = '';
        public string $email = '';
        public string $password = '';
        public string $password_confirmation = '';
        public string $role = 'operator';
        public ?string $access_scope = null;

        public array $breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Manajemen User', 'link' => '/admin/users'],
            ['label' => 'Tambah User', 'link' => '#'],
        ];

        // ========================
        // Actions
        // ========================
        public function save()
        {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'in:operator,pegawai'],
                'access_scope' => ['nullable', 'string'],
            ]);

            // FULLY QUALIFIED NAME
            app(\App\Services\User\UserService::class)
                ->createUser($validated);

            // MaryUI Toast (FQN via trait bawaan Volt)
            $this->success(
                'Berhasil!',
                'User baru berhasil ditambahkan.',
                position: 'toast-bottom toast-end',
                timeout: 3000,
            );

            return $this->redirect('/admin/users', navigate: true);
        }
    };
    ?>

    <div>
        {{-- Header Page --}}
        <x-header-page title="Tambah User Baru" :breadcrumbs="$breadcrumbs" />

        {{-- Form Section --}}
        <div class="max-w-2xl">
            <x-mary-card>
                <x-mary-form wire:submit="save">

                    <x-mary-input
                        label="Nama Lengkap"
                        wire:model="name"
                        placeholder="Masukkan nama lengkap"
                        required
                        icon="o-user"
                    />

                    <x-mary-input
                        label="Email"
                        wire:model="email"
                        type="email"
                        placeholder="contoh@email.com"
                        required
                        icon="o-envelope"
                    />

                    <x-mary-select
                        label="Role"
                        wire:model="role"
                        :options="[
                            ['id' => 'operator', 'name' => 'Operator'],
                            ['id' => 'pegawai', 'name' => 'Pegawai'],
                        ]"
                        placeholder="Pilih role"
                        required
                        icon="o-shield-check"
                    />

                    <x-mary-input
                        label="Wilayah Akses (Opsional)"
                        wire:model="access_scope"
                        placeholder="Contoh: Jakarta Pusat"
                        hint="Kosongkan jika akses semua wilayah"
                        icon="o-map-pin"
                    />

                    <x-mary-input
                        label="Password"
                        wire:model="password"
                        type="password"
                        placeholder="Minimal 8 karakter"
                        required
                        icon="o-lock-closed"
                    />

                    <x-mary-input
                        label="Konfirmasi Password"
                        wire:model="password_confirmation"
                        type="password"
                        placeholder="Ulangi password"
                        required
                        icon="o-lock-closed"
                    />

                    <x-slot:actions>
                        <x-mary-button
                            label="Batal"
                            link="/admin/users"
                            variant="ghost"
                        />
                        <x-mary-button
                            label="Simpan User"
                            type="submit"
                            class="btn-primary"
                            icon="o-check"
                            spinner="save"
                        />
                    </x-slot:actions>

                </x-mary-form>
            </x-mary-card>
        </div>
    </div>
    @endvolt
</x-layouts.app>
