<div>
    <x-header-page title="Tambah Cuti" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Cuti', 'href' => '/admin/cutis'], ['label' => 'Tambah']]">
        <x-slot:actions>
            <x-mary-button
                label="Batal"
                link="/admin/cutis"
                variant="ghost"
                icon="o-x-mark"
            />
        </x-slot:actions>
    </x-header-page>

    <div class="max-w-4xl">
        <x-mary-card>
            <x-mary-form wire:submit="save">
                <!-- Pilih Pegawai -->
                <x-mary-select
                    label="Pegawai"
                    wire:model="pegawai_id"
                    :options="$pegawaiOptions"
                    placeholder="Pilih pegawai"
                    searchable
                    required
                    icon="o-user"
                />

                <x-mary-input
                    label="Nomor Surat"
                    wire:model="nomor_surat"
                    placeholder="Contoh: 001/BAWASLU/RI/00/2025"
                    required
                    icon="o-document"
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
                    icon="o-calendar"
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
                    <x-mary-button label="Batal" link="/admin/cutis" variant="ghost" />
                    <x-mary-button label="Simpan" type="submit" class="btn-primary" spinner="save" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-card>
    </div>
</div>
