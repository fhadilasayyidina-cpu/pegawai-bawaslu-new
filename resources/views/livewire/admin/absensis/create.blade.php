<div>
    <x-header-page title="Tambah Absensi" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="ghost"
                icon="x-mark"
                href="/admin/absensis"
            >
                Batal
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="max-w-2xl">
        <x-mary-card>
            <form wire:submit="save">
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
                        href="/admin/absensis"
                    >
                        Batal
                    </flux:button>
                    <flux:button
                        type="submit"
                        variant="primary"
                    >
                        Simpan
                    </flux:button>
                </div>
            </form>
        </x-mary-card>
    </div>
</div>
