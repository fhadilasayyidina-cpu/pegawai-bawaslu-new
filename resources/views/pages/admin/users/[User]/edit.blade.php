<x-layouts.app title="Edit User">
    @volt
    <?php
    use App\Models\User;
    use App\Services\User\UserService;
    use Livewire\Volt\Component;

    new class extends Component {

        public User $user;

        public string $name = '';
        public string $email = '';
        public ?string $password = '';
        public ?string $password_confirmation = '';
        public string $role = 'operator';
        public ?string $access_scope = null;

        public array $kabKotaOptions = [];

        public array $breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Manajemen User', 'link' => '/admin/users'],
            ['label' => 'Edit User', 'link' => '#'],
        ];

        public function mount(User $user): void
        {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role->value;
            $this->access_scope = $user->access_scope;
            $this->kabKotaOptions = app(\App\Services\Pegawai\PegawaiService::class)->getKabKota()->toArray();
        }

        public function update()
        {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $this->user->id],
                'role' => ['required', 'in:admin,operator,pegawai'],
            ];

            // access_scope required only for operator
            if ($this->role === 'operator') {
                $rules['access_scope'] = ['required', 'string'];
            } else {
                $rules['access_scope'] = ['nullable', 'string'];
            }

            // Only validate password if provided
            if (!empty($this->password)) {
                $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            }

            $validated = $this->validate($rules);

            app(\App\Services\User\UserService::class)->updateUser($this->user, $validated);

            // Notyf Toast (dari app.js)
            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'Data user berhasil diperbarui.'
            ]);

            return $this->redirect('/admin/users', navigate: true);
        }
    };
    ?>

    <div>
        {{-- Header Page --}}
        <x-header-page title="Edit User" :breadcrumbs="$breadcrumbs" />

        {{-- Form Section --}}
        <div class="max-w-2xl">
            <x-mary-card>
                <x-mary-form wire:submit="update">
                    <!-- Nama -->
                    <x-mary-input
                        label="Nama Lengkap"
                        wire:model="name"
                        placeholder="Masukkan nama lengkap"
                        required
                        icon="o-user"
                    />

                    <!-- Email -->
                    <x-mary-input
                        label="Email"
                        wire:model="email"
                        placeholder="contoh@email.com"
                        type="email"
                        required
                        icon="o-envelope"
                    />

                    <!-- Role -->
                    <x-mary-select
                        label="Role"
                        wire:model="role"
                        :options="[
                            ['id' => 'admin', 'name' => 'Admin'],
                            ['id' => 'operator', 'name' => 'Operator'],
                            ['id' => 'pegawai', 'name' => 'Pegawai'],
                        ]"
                        placeholder="Pilih role"
                        required
                        icon="o-shield-check"
                    />

                    <!-- Access Scope -->
                    @if($role === 'operator')
                        <x-mary-select
                            label="Wilayah Akses"
                            wire:model="access_scope"
                            :options="$kabKotaOptions"
                            placeholder="Pilih wilayah akses"
                            icon="o-map-pin"
                            required
                        />
                    @else
                        <x-mary-input
                            label="Wilayah Akses"
                            wire:model="access_scope"
                            placeholder="Tidak berlaku untuk role ini"
                            icon="o-map-pin"
                            readonly
                        />
                    @endif

                    <!-- Password (Optional) -->
                    <div class="divider divider-text">Ganti Password (Opsional)</div>

                    <x-mary-input
                        label="Password Baru"
                        wire:model="password"
                        type="password"
                        placeholder="Kosongkan jika tidak diganti"
                        icon="o-lock-closed"
                    />

                    <x-mary-input
                        label="Konfirmasi Password Baru"
                        wire:model="password_confirmation"
                        type="password"
                        placeholder="Ulangi password baru"
                        icon="o-lock-closed"
                    />

                    <!-- Info -->
                    @if($user->email === auth()->user()->email)
                        <x-mary-callout title="Perhatian" icon="o-information-circle" class="alert-warning">
                            Anda sedang mengedit data diri sendiri.
                        </x-mary-callout>
                    @endif

                    <!-- Actions -->
                    <x-slot:actions>
                        <x-mary-button
                            label="Batal"
                            link="/admin/users"
                            variant="ghost"
                        />
                        <x-mary-button
                            label="Update User"
                            type="submit"
                            class="btn-primary"
                            icon="o-check"
                            spinner="update"
                        />
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-card>
        </div>
    </div>
    @endvolt
</x-layouts.app>
