<div>
    <x-header-page title="Tambah KGB PPPK" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'KGB', 'href' => '/admin/kgbs'], ['label' => 'PPPK']]">
        <x-slot:actions>
            <x-mary-button
                label="Batal"
                link="/admin/kgbs"
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
                    label="Pegawai PPPK"
                    wire:model.live="pegawai_id"
                    :options="$pegawaiOptions"
                    placeholder="Pilih pegawai"
                    searchable
                    required
                    icon="o-user"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Nomor Keputusan KGB"
                        wire:model="nomor_naskah"
                        placeholder="Contoh: 002/BAWASLU/RI/00/2026"
                        required
                        icon="o-document"
                    />

                    <flux:input
                        label="Tanggal Keputusan"
                        type="date"
                        wire:model="tanggal_naskah"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Ibu Kota Provinsi"
                        wire:model="ibu_kota_provinsi"
                        required
                    />

                    <x-mary-input
                        label="Nomor Induk (NI) PPPK"
                        wire:model="ni_pppk"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-select
                        label="Jabatan / Golongan"
                        wire:model.live="jabatan_golongan"
                        :options="$jabatanGolonganOptions"
                        placeholder="Pilih atau cari jabatan/golongan"
                        searchable
                        required
                    />

                    <x-mary-input
                        label="Masa Perjanjian Kerja"
                        wire:model="masa_perjanjian_kerja"
                        placeholder="Contoh: 5 Tahun"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Perpanjangan Perjanjian Kerja"
                        wire:model="perpanjangan_perjanjian_kerja"
                        placeholder="Contoh: - (jika tidak ada)"
                        required
                    />

                    <x-mary-input
                        label="Unit Kerja"
                        wire:model="unit_kerja"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <x-mary-input
                        label="Gaji Pokok Lama"
                        wire:model="gaji_lama"
                        placeholder="Contoh: Rp. 3.000.000,-"
                        required
                    />
                </div>

                <h3 class="text-lg font-semibold mt-6 mb-2 border-b pb-1">Surat Keputusan Pangkat/Gaji Terakhir</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Ditetapkan Oleh Pejabat"
                        wire:model="sk_pejabat"
                        required
                    />

                    <flux:input
                        label="Tanggal SK"
                        type="date"
                        wire:model="sk_tanggal"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Nomor SK"
                        wire:model="sk_nomor"
                        required
                    />

                    <flux:input
                        label="Tanggal Berlaku Gaji Terakhir (TMT SK)"
                        type="date"
                        wire:model="sk_tmt"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input
                        label="Masa Kerja Golongan saat SK Terakhir (Tahun)"
                        type="number"
                        min="0"
                        wire:model.live="sk_mkg_tahun"
                        required
                    />

                    <flux:input
                        label="Masa Kerja Golongan saat SK Terakhir (Bulan)"
                        type="number"
                        min="0"
                        max="11"
                        wire:model="sk_mkg_bulan"
                        required
                    />
                </div>

                <h3 class="text-lg font-semibold mt-6 mb-2 border-b pb-1">Kenaikan Gaji Berkala Baru</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Gaji Baru"
                        wire:model="gaji_baru"
                        placeholder="Contoh: Rp. 3.200.000,-"
                        required
                    />

                    <x-mary-select
                        label="Berdasarkan Masa Kerja Baru"
                        wire:model.live="masa_kerja_baru"
                        :options="$masaKerjaOptions"
                        placeholder="Pilih masa kerja"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input
                        label="Mulai Tanggal (TMT Baru)"
                        type="date"
                        wire:model="tmt_baru"
                        required
                    />
                </div>

                <h3 class="text-lg font-semibold mt-6 mb-2 border-b pb-1">Tanda Tangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Nama Kepala Sekretariat"
                        wire:model="nama_kasek"
                        required
                    />

                    <x-mary-input
                        label="Catatan Tanda Tangan (Opsional)"
                        wire:model="ttd_pengirim"
                        placeholder="Contoh: Plt. Kepala Sekretariat"
                    />
                </div>

                <x-slot:actions>
                    <flux:button type="submit" variant="primary" class="w-full md:w-auto">
                        Cetak PDF & Simpan
                    </flux:button>
                </x-slot:actions>
            </x-mary-form>
        </x-mary-card>
    </div>
</div>
