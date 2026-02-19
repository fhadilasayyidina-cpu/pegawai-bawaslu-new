@php
use function Laravel\Folio\{middleware, name};

name('operator.pimpinans.edit');
middleware(['auth', 'verified', 'role:operator']);
@endphp

<x-layouts.app title="Edit Pimpinan">
    @volt
    <?php

    new class extends \Livewire\Volt\Component {

        // ========================
        // State / Properties
        // ========================
        public int $id;
        public \App\Models\Pimpinan $pimpinan;

        public string $nama = '';
        public string $jabatan = '';
        public string $kab_kota = '';
        public ?string $email = null;
        public ?string $no_hp = null;

        public array $breadcrumbs = [];

        public array $jabatanOptions = [
            ['id' => 'ketua', 'name' => 'Ketua'],
            ['id' => 'anggota', 'name' => 'Anggota'],
        ];

        // ========================
        // Lifecycle Hooks
        // ========================
        public function mount(int $id): void
        {
            $this->pimpinan = \App\Models\Pimpinan::findOrFail($id);

            $this->nama = $this->pimpinan->nama;
            $this->jabatan = $this->pimpinan->jabatan->value;
            $this->kab_kota = $this->pimpinan->kab_kota;
            $this->email = $this->pimpinan->email;
            $this->no_hp = $this->pimpinan->no_hp;

            $this->breadcrumbs = [
                ['label' => 'Dashboard', 'link' => '/operator'],
                ['label' => 'Data Pimpinan', 'link' => '/operator/pimpinans'],
                ['label' => 'Edit Pimpinan', 'link' => '#'],
            ];
        }

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
        public function update()
        {
            $validated = $this->validate([
                'nama' => ['required', 'string', 'max:255'],
                'jabatan' => ['required', 'in:ketua,anggota'],
                'kab_kota' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'no_hp' => ['nullable', 'string', 'max:20'],
            ]);

            app(\App\Services\Pimpinan\PimpinanService::class)
                ->updatePimpinan($this->pimpinan->id, $validated);

            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'Data pimpinan berhasil diperbarui!'
            ]);

            return $this->redirect('/operator/pimpinans/' . $this->pimpinan->id . '/details');
        }
    };
    ?>

    <div>
        {{-- Header Page --}}
        <x-header-page title="Edit Pimpinan" :breadcrumbs="$breadcrumbs">
            <x-slot:actions>
                <x-mary-button
                    label="Batal"
                    link="/operator/pimpinans/{{ $pimpinan->id }}/details"
                    variant="ghost"
                    icon="o-x-mark"
                />
            </x-slot:actions>
        </x-header-page>

        {{-- Form Section --}}
        <div class="max-w-4xl">
            <x-mary-card class="">
                <x-mary-form wire:submit="update">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <x-mary-input
                            label="Nama"
                            wire:model="nama"
                            placeholder="Masukkan nama lengkap"
                            required
                            icon="o-user"
                        />

                        <!-- Jabatan -->
                        <x-mary-select
                            label="Jabatan"
                            wire:model="jabatan"
                            :options="$jabatanOptions"
                            placeholder="Pilih jabatan"
                            required
                            icon="o-user-group"
                        />

                        <!-- Kabupaten/Kota - Auto-filtered by access_scope -->
                        <x-mary-select
                            label="Kabupaten/Kota"
                            wire:model="kab_kota"
                            :options="$this->kabKotaOptions"
                            placeholder="Pilih kabupaten/kota"
                            required
                            searchable
                            icon="o-map-pin"
                        />

                        <!-- Email -->
                        <x-mary-input
                            label="Email"
                            wire:model="email"
                            type="email"
                            placeholder="Masukkan alamat email"
                            icon="o-envelope"
                        />

                        <!-- No HP -->
                        <x-mary-input
                            label="No HP"
                            wire:model="no_hp"
                            placeholder="Masukkan nomor HP"
                            icon="o-phone"
                            class="md:col-span-2"
                        />
                    </div>

                    <x-slot:actions>
                        <x-mary-button
                            label="Batal"
                            link="/operator/pimpinans/{{ $pimpinan->id }}/details"
                            variant="ghost"
                        />
                        <x-mary-button
                            label="Simpan Perubahan"
                            class="btn-primary"
                            icon="o-check"
                            spinner="update"
                            type="submit"
                        />
                    </x-slot:actions>

                </x-mary-form>
            </x-mary-card>
        </div>
    </div>
    @endvolt
</x-layouts.app>
