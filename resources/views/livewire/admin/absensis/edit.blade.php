<div>
    <x-header-page title="Edit Absensi" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="ghost"
                icon="x-mark"
                href="/admin/absensis/{{ $absensi->id }}/details"
            >
                Batal
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="max-w-2xl">
        <x-mary-card>
            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <flux:text class="text-sm">
                    Pegawai: <strong>{{ $absensi->pegawai->nama }}</strong> ({{ $absensi->pegawai->nip_baru }})
                </flux:text>
            </div>

            <form wire:submit="update">
                <div class="space-y-4">
                    <flux:select
                        label="Pegawai"
                        wire:model="pegawai_id"
                        required
                        placeholder="Pilih pegawai"
                    >
                        @foreach($pegawaiOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:input
                        label="Tanggal"
                        type="date"
                        wire:model="tanggal"
                        required
                    />

                    <flux:select
                        label="Status"
                        wire:model="status"
                        required
                    >
                        @foreach($statusOptions as $option)
                            <flux:select.option :value="$option['id']">{{ $option['name'] }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:textarea
                        label="Keterangan"
                        wire:model="keterangan"
                        placeholder="Keterangan tambahan (opsional)"
                        rows="3"
                    />
                </div>

                <flux:spacer />

                <div class="flex justify-end gap-2">
                    <flux:button
                        type="button"
                        variant="ghost"
                        href="/admin/absensis/{{ $absensi->id }}/details"
                    >
                        Batal
                    </flux:button>
                    <flux:button
                        type="submit"
                        variant="primary"
                    >
                        Simpan Perubahan
                    </flux:button>
                </div>
            </form>
        </x-mary-card>
    </div>
</div>
