<div>
    <x-header-page title="Detail Pegawai" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Pegawai', 'href' => '/admin/pegawais'], ['label' => 'Detail']]">
    </x-header-page>

    <x-mary-tabs wire:model="selectedTab" selected="identitas-tab">
        <!-- Tab 1: Identitas -->
        <x-mary-tab name="identitas-tab" label="Identitas" icon="o-user">
            <x-mary-form wire:submit="saveIdentitas">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input label="NIP" wire:model="identitasForm.nip_baru" readonly />
                    <x-mary-input label="Nama" wire:model="identitasForm.nama" />
                    <x-mary-input label="NIK" wire:model="identitasForm.nik" />
                    <x-mary-input label="Gelar Depan" wire:model="identitasForm.gelar_depan" />
                    <x-mary-input label="Gelar Belakang" wire:model="identitasForm.gelar_blk" />
                    <x-mary-input label="Tempat Lahir" wire:model="identitasForm.tempat_lahir_nama" />
                    <flux:input label="Tanggal Lahir" type="date" wire:model="identitasForm.tgl_lahir" />
                    <x-mary-input label="Usia" wire:model="identitasForm.usia" readonly />
                    <flux:select label="Jenis Kelamin" wire:model="identitasForm.jenis_kelamin">
                        <flux:select.option value="">Pilih</flux:select.option>
                        <flux:select.option value="Pria">Laki-laki</flux:select.option>
                        <flux:select.option value="Wanita">Perempuan</flux:select.option>
                    </flux:select>
                    <flux:select label="Golongan Darah" wire:model="identitasForm.gol_darah">
                        <flux:select.option value="">Pilih</flux:select.option>
                        <flux:select.option value="A">A</flux:select.option>
                        <flux:select.option value="B">B</flux:select.option>
                        <flux:select.option value="AB">AB</flux:select.option>
                        <flux:select.option value="O">O</flux:select.option>
                    </flux:select>
                    <x-mary-input label="Agama" wire:model="identitasForm.agama_nama" />
                    <x-mary-input label="Status Kawin" wire:model="identitasForm.jenis_kawin_nama" />
                    <x-mary-input label="Nomor HP" wire:model="identitasForm.nomor_hp" />
                    <x-mary-input label="Email" wire:model="identitasForm.email" />
                    <x-mary-input label="Email Gov" wire:model="identitasForm.email_gov" />
                    <x-mary-textarea label="Alamat" wire:model="identitasForm.alamat" rows="3" />
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan Identitas" type="submit" class="btn-primary" spinner="saveIdentitas" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>

        <!-- Tab 2: Jabatan & Golongan -->
        <x-mary-tab name="jabatan-tab" label="Jabatan & Golongan" icon="o-briefcase">
            <x-mary-form wire:submit="saveJabatan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input label="Golongan Awal" wire:model="jabatanForm.gol_awal_nama" />
                    <x-mary-input label="Golongan" wire:model="jabatanForm.gol_nama" />
                    <flux:input label="TMT Golongan" type="date" wire:model="jabatanForm.tmt_golongan" />
                    <x-mary-input label="Masa Kerja Golongan" wire:model="jabatanForm.mkgol" />
                    <x-mary-input label="Jenis Jabatan" wire:model="jabatanForm.jenis_jabatan_nama" />
                    <x-mary-input label="Jabatan" wire:model="jabatanForm.jabatan_nama" />
                    <flux:input label="TMT Jabatan" type="date" wire:model="jabatanForm.tmt_jabatan" />
                    <x-mary-input label="Jabatan Non-Definitif" wire:model="jabatanForm.jabatan_non_definitif" />
                    <x-mary-input label="Jabatan Non-Definitif 1" wire:model="jabatanForm.jabatan_non_definitif_1" />
                    <x-mary-input label="Masa Kerja Jabatan" wire:model="jabatanForm.mkjab" />
                    <x-mary-input label="Kelas Jabatan" wire:model="jabatanForm.kelas_jabatan" />
                    <x-mary-input label="Kelompok Jabatan" wire:model="jabatanForm.kelompok_jabatan" />
                    <x-mary-input label="Pangkat" wire:model="jabatanForm.pangkat" />
                    <x-mary-input label="Proyeksi JF" wire:model="jabatanForm.proyeksi_jf" />
                    <x-mary-input label="Keterangan Status" wire:model="jabatanForm.keterangan_status" />
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan Jabatan" type="submit" class="btn-primary" spinner="saveJabatan" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>

        <!-- Tab 3: Administrasi -->
        <x-mary-tab name="administrasi-tab" label="Administrasi" icon="o-document">
            <x-mary-form wire:submit="saveAdministrasi">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input label="NPWP" wire:model="administrasiForm.npwp_nomor" />
                    <x-mary-input label="BPJS" wire:model="administrasiForm.bpjs" />
                    <x-mary-input label="Kartu Pegawai" wire:model="administrasiForm.kartu_pegawai" />
                    <x-mary-input label="Nomor SK CPNS" wire:model="administrasiForm.nomor_sk_cpns" />
                    <flux:input label="Tanggal SK CPNS" type="date" wire:model="administrasiForm.tgl_sk_cpns" />
                    <flux:input label="TMT CPNS" type="date" wire:model="administrasiForm.tmt_cpns" />
                    <x-mary-input label="Nomor SK PNS" wire:model="administrasiForm.nomor_sk_pns" />
                    <flux:input label="Tanggal SK PNS" type="date" wire:model="administrasiForm.tgl_sk_pns" />
                    <flux:input label="TMT PNS" type="date" wire:model="administrasiForm.tmt_pns" />
                    <x-mary-input label="No SK DPK Penugasan Kontrak" wire:model="administrasiForm.no_sk_dpk_penugasan_kontrak" />
                    <flux:input label="Tanggal SK DPK" type="date" wire:model="administrasiForm.tgl_sk_dpk_penugasan_kontrak" />
                    <x-mary-textarea label="Keterangan" wire:model="administrasiForm.keterangan" rows="3" />
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan Administrasi" type="submit" class="btn-primary" spinner="saveAdministrasi" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>
    </x-mary-tabs>
</div>
