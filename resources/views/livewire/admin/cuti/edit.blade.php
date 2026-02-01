<div>
    <x-header-page title="Edit Cuti" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-mary-button
                label="Batal"
                link="/admin/cutis/{{ $cuti->id }}"
                variant="ghost"
                icon="o-x-mark"
            />
        </x-slot:actions>
    </x-header-page>

    <div class="max-w-4xl">
        <x-mary-card>
            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <flux:text class="text-sm">
                    Pegawai: <strong>{{ $cuti->pegawai->nama }}</strong> ({{ $cuti->pegawai->nip_baru }})
                </flux:text>
            </div>

            <x-mary-form wire:submit="update">
                <x-mary-input
                    label="Nomor Surat"
                    wire:model="nomor_surat"
                    placeholder="Contoh: 001/BAWASLU/RI/00/2025"
                    required
                    icon="document"
                />

                <flux:select label="Jenis Cuti" wire:model="jenis_cuti" required>
                    <flux:select.option value="tahunan">Cuti Tahunan</flux:select.option>
                </flux:select>

                <x-mary-textarea
                    label="Alasan Cuti"
                    wire:model="alasan"
                    placeholder="Masukkan alasan cuti"
                    required
                    rows="3"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input
                        label="Tanggal Mulai"
                        type="date"
                        wire:model="tanggal_mulai"
                        required
                    />
                    <flux:input
                        label="Tanggal Selesai"
                        type="date"
                        wire:model="tanggal_selesai"
                        required
                    />
                </div>

                <x-mary-input
                    label="Lama Hari"
                    wire:model="lama_hari"
                    type="number"
                    readonly
                    icon="calendar"
                />

                <x-mary-textarea
                    label="Keterangan"
                    wire:model="keterangan"
                    rows="2"
                    placeholder="Keterangan tambahan (opsional)"
                />

                <!-- Informasi Penandatangan -->
                <hr class="my-4">
                <flux:heading size="sm">Informasi Penandatangan</flux:heading>

                <x-mary-input
                    label="Nama Kepala Sekretariat"
                    wire:model="nama_kepala_sekretariat"
                    placeholder="Nama lengkap dengan gelar"
                    required
                />
                <x-mary-input
                    label="NIP Kepala Sekretariat"
                    wire:model="nip_kepala_sekretariat"
                    placeholder="NIP"
                />

                <x-mary-input
                    label="Nama Sekjen"
                    wire:model="nama_sekjen"
                    placeholder="Nama lengkap dengan gelar"
                    required
                />
                <x-mary-input
                    label="NIP Sekjen"
                    wire:model="nip_sekjen"
                    placeholder="NIP"
                />

                <x-mary-input
                    label="Nomor Surat Edaran"
                    wire:model="nomor_surat_edaran"
                    placeholder="Nomor surat edaran (opsional)"
                />

                <x-slot:actions>
                    <x-mary-button label="Batal" link="/admin/cutis/{{ $cuti->id }}" variant="ghost" />
                    <x-mary-button label="Simpan Perubahan" type="submit" class="btn-primary" spinner="update" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-card>
    </div>
</div>
