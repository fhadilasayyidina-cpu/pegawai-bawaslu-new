<div>
    <x-header-page title="Tambah KGB PNS" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'KGB', 'href' => '/admin/kgbs'], ['label' => 'PNS']]">
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
                    label="Pegawai PNS"
                    wire:model.live="pegawai_id"
                    :options="$pegawaiOptions"
                    placeholder="Pilih pegawai"
                    searchable
                    required
                    icon="o-user"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input
                        label="Ibu Kota Provinsi"
                        wire:model="ibu_kota_provinsi"
                        required
                    />

                    <x-mary-input
                        label="Gaji Pokok Lama"
                        wire:model="gaji_pokok_lama"
                        placeholder="Contoh: Rp. 3.000.000,-"
                        required
                    />
                </div>

                <h3 class="text-lg font-semibold mt-6 mb-2 border-b pb-1">Atas Dasar SK/Pangkat Terakhir</h3>
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
                        label="Tanggal Mulai Berlaku (TMT SK)"
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
                        wire:model="sk_mkg_tahun"
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
                        label="Gaji Pokok Baru"
                        wire:model="gaji_pokok_baru"
                        placeholder="Contoh: Rp. 3.200.000,-"
                        required
                    />

                    <x-mary-select
                        label="Berdasarkan Masa Kerja Golongan Baru"
                        wire:model.live="masa_kerja_baru"
                        :options="$masaKerjaOptions"
                        placeholder="Pilih masa kerja golongan"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-mary-select
                        label="Dalam Golongan/Ruang"
                        wire:model.live="golongan_ruang_baru"
                        :options="$golonganOptions"
                        placeholder="Pilih golongan/ruang"
                        required
                    />

                    <flux:input
                        label="Mulai Tanggal (TMT Baru)"
                        type="date"
                        wire:model="tmt_baru"
                        readonly
                        required
                    />

                    <flux:input
                        label="Tanggal KGB Berikutnya"
                        type="date"
                        wire:model="next_kgb_date"
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
                        Cetak & Simpan
                    </flux:button>
                </x-slot:actions>
            </x-mary-form>
        </x-mary-card>
    </div>
</div>
