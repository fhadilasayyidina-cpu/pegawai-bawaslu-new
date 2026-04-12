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
