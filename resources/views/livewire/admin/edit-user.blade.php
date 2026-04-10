<div>
    {{-- Header Page --}}
    <x-header-page title="Edit User" :breadcrumbs="$breadcrumbs" />

    {{-- Form Section --}}
    <div class="max-w-2xl">
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            <form wire:submit="update">
                <div class="space-y-4 p-6">
                    <!-- Nama -->
                    <flux:input
                        label="Nama Lengkap"
                        wire:model="name"
                        placeholder="Masukkan nama lengkap"
                        required
                    />

                    <!-- Email -->
                    <flux:input
                        label="Email"
                        wire:model="email"
                        placeholder="contoh@email.com"
                        type="email"
                        required
                    />

                    <!-- Role -->
                    <flux:select
                        label="Role"
                        wire:model="role"
                    >
                        <flux:select.option value="admin">Admin</flux:select.option>
                        <flux:select.option value="operator">Operator</flux:select.option>
                        <flux:select.option value="pegawai">Pegawai</flux:select.option>
                    </flux:select>

                    <!-- Access Scope -->
                    @if($role === 'operator')
                        <flux:select
                            label="Wilayah Akses"
                            wire:model="access_scope"
                            required
                        >
                            @foreach($kabKotaOptions as $option)
                                <flux:select.option :value="$option->id">{{ $option->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    @else
                        <flux:input
                            label="Wilayah Akses"
                            wire:model="access_scope"
                            placeholder="Tidak berlaku untuk role ini"
                            readonly
                        />
                    @endif

                    <!-- Password (Optional) -->
                    <flux:separator />
                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Ganti Password (Opsional)</p>

                    <flux:input
                        label="Password Baru"
                        wire:model="password"
                        type="password"
                        placeholder="Kosongkan jika tidak diganti"
                    />

                    <flux:input
                        label="Konfirmasi Password Baru"
                        wire:model="password_confirmation"
                        type="password"
                        placeholder="Ulangi password baru"
                    />

                    <!-- Info -->
                    @if($user->email === auth()->user()->email)
                        <flux:callout title="Perhatian" variant="warning">
                            Anda sedang mengedit data diri sendiri.
                        </flux:callout>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <flux:button
                            variant="ghost"
                            link="/admin/users"
                        >
                            Batal
                        </flux:button>
                        <flux:button
                            variant="primary"
                            type="submit"
                            indicator="submitting"
                        >
                            Update User
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
