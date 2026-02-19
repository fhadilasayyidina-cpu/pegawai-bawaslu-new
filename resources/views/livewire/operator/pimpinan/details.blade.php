<div>
    <x-header-page title="Detail Pimpinan" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-mary-button
                label="Edit"
                icon="o-pencil"
                link="/operator/pimpinans/{{ $pimpinan->id }}/edit"
                class="btn-primary"
            />
            <x-mary-button
                label="Kembali"
                icon="o-arrow-left"
                link="/operator/pimpinans"
                variant="ghost"
            />
        </x-slot:actions>
    </x-header-page>

    <div class="my-4 max-w-4xl">
        <x-mary-card>
            <!-- Foto Section -->
            <div class="flex flex-col items-center p-6 mb-6">
                @if($pimpinan->foto)
                    <img
                        src="{{ $pimpinan->foto_url }}"
                        alt="Foto {{ $pimpinan->nama }}"
                        class="w-48 h-48 object-cover rounded-full border-4 border-gray-200 dark:border-gray-700 shadow-lg"
                    />
                @else
                    <flux:avatar
                        :name="$pimpinan->nama"
                        size="full"
                        class="w-48 h-48 rounded-full border-4 border-gray-200 dark:border-gray-700 shadow-lg text-6xl"
                    />
                @endif

                <flux:heading size="xl" class="mt-4 text-center">
                    {{ $pimpinan->nama }}
                </flux:heading>

                <flux:badge variant="primary" class="mt-2">
                    {{ $pimpinan->jabatan->value === 'ketua' ? 'Ketua' : 'Anggota' }}
                </flux:badge>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <x-mary-input label="Nama" :value="$pimpinan->nama" readonly />

                <!-- Jabatan -->
                <x-mary-input
                    label="Jabatan"
                    :value="$pimpinan->jabatan->value === 'ketua' ? 'Ketua' : 'Anggota'"
                    readonly
                />

                <!-- Kabupaten/Kota -->
                <x-mary-input label="Kabupaten/Kota" :value="$pimpinan->kab_kota" readonly />

                <!-- Email -->
                <x-mary-input label="Email" :value="$pimpinan->email ?? '-'" readonly />

                <!-- No HP -->
                <x-mary-input label="No HP" :value="$pimpinan->no_hp ?? '-'" readonly class="md:col-span-2" />
            </div>
        </x-mary-card>
    </div>
</div>
