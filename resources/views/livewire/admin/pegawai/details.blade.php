<div>
    <x-header-page title="Detail Pegawai" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Pegawai', 'href' => '/admin/pegawais'], ['label' => 'Detail']]">
        <x-slot:actions>
            <x-mary-button
                label="Cuti"
                icon="o-calendar"
                link="/admin/cutis/create?pegawai_id={{ $pegawai->id }}"
                variant="secondary"
            />
            <flux:button
                variant="ghost"
                icon="calendar-days"
                href="/admin/pegawais/{{ $pegawai->id }}/absensis"
            >
                Lihat Absensi
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <x-mary-tabs wire:model="selectedTab" selected="ringkasan-tab">
        <!-- Tab 0: Ringkasan -->
        <x-mary-tab name="ringkasan-tab" label="Ringkasan" icon="o-home">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Foto Profil -->
                <div class="lg:col-span-1">
                    <x-mary-card>
                        <div class="flex flex-col items-center p-6">
                            @if($pegawai->foto)
                                <img
                                    src="{{ $pegawai->foto_url }}"
                                    alt="Foto {{ $pegawai->nama }}"
                                    class="w-48 h-48 object-cover rounded-full border-4 border-gray-200 dark:border-gray-700 shadow-lg"
                                />
                            @else
                                <flux:avatar
                                    :name="$pegawai->nama"
                                    size="full"
                                    class="w-48 h-48 rounded-full border-4 border-gray-200 dark:border-gray-700 shadow-lg text-6xl"
                                />
                            @endif

                            <flux:heading size="xl" class="mt-4 text-center">
                                {{ $pegawai->nama }}
                            </flux:heading>

                            @if($pegawai->gelar_depan || $pegawai->gelar_blk)
                                <flux:text class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $pegawai->gelar_depan }} {{ $pegawai->gelar_blk }}
                                </flux:text>
                            @endif

                            <flux:badge variant="primary" class="mt-2">
                                {{ $pegawai->nip_baru }}
                            </flux:badge>
                        </div>
                    </x-mary-card>
                </div>

                <!-- Right: Info Cards -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Jabatan & Golongan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-mary-card>
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                    <flux:icon name="briefcase" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div>
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Jabatan</flux:text>
                                    <flux:heading size="sm">{{ $pegawai->jabatan_nama ?: '-' }}</flux:heading>
                                </div>
                            </div>
                        </x-mary-card>

                        <x-mary-card>
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <flux:icon name="book-open" class="w-6 h-6 text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Golongan</flux:text>
                                    <flux:heading size="sm">{{ $pegawai->gol_nama ?: '-' }}</flux:heading>
                                </div>
                            </div>
                        </x-mary-card>
                    </div>

                    <!-- Unit Kerja -->
                    <x-mary-card>
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <flux:icon name="building-office" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <flux:text class="text-sm text-gray-500 dark:text-gray-400">Unit Kerja</flux:text>
                                <flux:heading size="sm">{{ $pegawai->unit_kerja ?: '-' }}</flux:heading>
                            </div>
                        </div>
                    </x-mary-card>

                    <!-- KGB Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-mary-card>
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                    <flux:icon name="calendar-date-range" class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                                </div>
                                <div>
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">KGB Terakhir</flux:text>
                                    @if($pegawai->tgl_kgb_terakhir)
                                        <flux:heading size="sm">{{ $pegawai->tgl_kgb_terakhir->format('d F Y') }}</flux:heading>
                                    @else
                                        <flux:text class="text-gray-400">Belum ada data</flux:text>
                                    @endif
                                </div>
                            </div>
                        </x-mary-card>

                        <x-mary-card>
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <flux:icon name="calendar-days" class="w-6 h-6 text-red-600 dark:text-red-400" />
                                </div>
                                <div>
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Perkiraan KGB Berikutnya</flux:text>
                                    @if($this->nextKgbDate)
                                        <flux:heading size="sm">{{ $this->nextKgbDate->format('d F Y') }}</flux:heading>
                                        <flux:text class="text-xs text-gray-500">(+2 tahun dari KGB terakhir)</flux:text>
                                    @else
                                        <flux:text class="text-gray-400">Belum dapat dihitung</flux:text>
                                    @endif
                                </div>
                            </div>
                        </x-mary-card>
                    </div>

                    <!-- Absensi Statistics -->
                    <x-mary-card>
                        <div class="p-4">
                            <flux:heading size="lg" class="mb-4">Ringkasan Absensi</flux:heading>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                <x-statistic-card title="Total" :value="$this->absensiStatistics['total']" color="primary" />
                                <x-statistic-card title="Hadir" :value="$this->absensiStatistics['hadir']" color="success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </x-statistic-card>
                                <x-statistic-card title="Izin" :value="$this->absensiStatistics['izin']" color="warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </x-statistic-card>
                                <x-statistic-card title="Cuti" :value="$this->absensiStatistics['cuti']" color="info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </x-statistic-card>
                                <x-statistic-card title="Tidak Hadir" :value="$this->absensiStatistics['tidak_hadir']" color="danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </x-statistic-card>
                            </div>
                        </div>
                    </x-mary-card>
                </div>
            </div>
        </x-mary-tab>

        <!-- Tab 1: Identitas -->
        <x-mary-tab name="identitas-tab" label="Identitas" icon="o-user">
            <x-mary-form wire:submit="saveIdentitas">
                <!-- Foto Profil Section -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-start gap-6">
                        <!-- Photo Display -->
                        <div class="flex-shrink-0">
                            @if($pegawai->foto)
                                <img
                                    src="{{ $pegawai->foto_url }}"
                                    alt="Foto {{ $pegawai->nama }}"
                                    class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700"
                                />
                            @else
                                <flux:avatar
                                    :name="$pegawai->nama"
                                    size="lg"
                                    class="w-32 h-32 rounded-lg border-2 border-gray-200 dark:border-gray-700"
                                />
                            @endif
                        </div>

                        <!-- Upload Controls -->
                        <div class="flex-1">
                            <flux:heading size="lg">Foto Profil</flux:heading>
                            <flux:text class="mb-3 text-sm">
                                Upload foto pegawai (format: JPG, PNG, max 2MB)
                            </flux:text>

                            <flux:input
                                type="file"
                                wire:model="identitasForm.foto"
                                accept="image/*"
                                class="mb-2"
                            />

                            @if($pegawai->foto)
                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    wire:click="deleteFoto"
                                >
                                    Hapus Foto
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input label="NIP" wire:model="identitasForm.nip_baru" readonly />
                    <x-mary-input label="NIP Lama" wire:model="identitasForm.nip_lama" />
                    <x-mary-input label="Nama" wire:model="identitasForm.nama" />
                    <x-mary-input label="NIK" wire:model="identitasForm.nik" />
                    <x-mary-input label="Gelar Depan" wire:model="identitasForm.gelar_depan" />
                    <x-mary-input label="Gelar Belakang" wire:model="identitasForm.gelar_blk" />
                    <x-mary-input label="Tempat Lahir" wire:model="identitasForm.tempat_lahir_nama" />
                    <flux:input label="Tanggal Lahir" type="date" wire:model="identitasForm.tgl_lahir" />
                    <x-mary-input label="Usia" wire:model="identitasForm.usia" readonly />
                    <x-mary-input label="Range Umur" wire:model="identitasForm.range_umur" />
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
                    <div>
                        <flux:input
                            label="Link Google Drive SK Golongan Awal"
                            wire:model="jabatanForm.sk_golongan_awal_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->sk_golongan_awal_drive_link)
                            <a href="{{ $pegawai->sk_golongan_awal_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link SK Golongan Awal
                            </a>
                        @endif
                    </div>
                    <x-mary-input label="Golongan" wire:model="jabatanForm.gol_nama" />
                    <div>
                        <flux:input
                            label="Link Google Drive SK Golongan Terakhir"
                            wire:model="jabatanForm.sk_golongan_terakhir_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->sk_golongan_terakhir_drive_link)
                            <a href="{{ $pegawai->sk_golongan_terakhir_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link SK Golongan Terakhir
                            </a>
                        @endif
                    </div>
                    <flux:input label="TMT Golongan" type="date" wire:model="jabatanForm.tmt_golongan" />
                    <flux:input label="Masa Kerja Golongan" type="number" wire:model="jabatanForm.mkgol" />
                    <x-mary-input label="Jenis Jabatan" wire:model="jabatanForm.jenis_jabatan_nama" />
                    <x-mary-input label="Jabatan" wire:model="jabatanForm.jabatan_nama" />
                    <div>
                        <flux:input
                            label="Link Google Drive SK Jabatan"
                            wire:model="jabatanForm.sk_jabatan_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->sk_jabatan_drive_link)
                            <a href="{{ $pegawai->sk_jabatan_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link SK Jabatan
                            </a>
                        @endif
                    </div>
                    <flux:input label="TMT Jabatan" type="date" wire:model="jabatanForm.tmt_jabatan" />
                    <x-mary-input label="Jabatan Non-Definitif" wire:model="jabatanForm.jabatan_non_definitif" />
                    <x-mary-input label="Jabatan Non-Definitif 1" wire:model="jabatanForm.jabatan_non_definitif_1" />
                    <flux:input label="Masa Kerja Jabatan" type="number" wire:model="jabatanForm.mkjab" />
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
                    <div>
                        <flux:input
                            label="Link Google Drive NPWP"
                            wire:model="administrasiForm.npwp_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->npwp_drive_link)
                            <a href="{{ $pegawai->npwp_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link NPWP
                            </a>
                        @endif
                    </div>
                    <x-mary-input label="BPJS" wire:model="administrasiForm.bpjs" />
                    <div>
                        <flux:input
                            label="Link Google Drive BPJS"
                            wire:model="administrasiForm.bpjs_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->bpjs_drive_link)
                            <a href="{{ $pegawai->bpjs_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link BPJS
                            </a>
                        @endif
                    </div>
                    <x-mary-input label="Kartu Pegawai" wire:model="administrasiForm.kartu_pegawai" />
                    <div>
                        <flux:input
                            label="Link Google Drive Karpeg"
                            wire:model="administrasiForm.karpeg_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->karpeg_drive_link)
                            <a href="{{ $pegawai->karpeg_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link Karpeg
                            </a>
                        @endif
                    </div>
                    <x-mary-input label="Nomor SK CPNS" wire:model="administrasiForm.nomor_sk_cpns" />
                    <div>
                        <flux:input
                            label="Link Google Drive SK CPNS"
                            wire:model="administrasiForm.sk_cpns_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->sk_cpns_drive_link)
                            <a href="{{ $pegawai->sk_cpns_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link SK CPNS
                            </a>
                        @endif
                    </div>
                    <flux:input label="Tanggal SK CPNS" type="date" wire:model="administrasiForm.tgl_sk_cpns" />
                    <flux:input label="TMT CPNS" type="date" wire:model="administrasiForm.tmt_cpns" />
                    <x-mary-input label="Nomor SK PNS" wire:model="administrasiForm.nomor_sk_pns" />
                    <div>
                        <flux:input
                            label="Link Google Drive SK PNS"
                            wire:model="administrasiForm.sk_pns_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->sk_pns_drive_link)
                            <a href="{{ $pegawai->sk_pns_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link SK PNS
                            </a>
                        @endif
                    </div>
                    <flux:input label="Tanggal SK PNS" type="date" wire:model="administrasiForm.tgl_sk_pns" />
                    <flux:input label="TMT PNS" type="date" wire:model="administrasiForm.tmt_pns" />
                    <x-mary-input label="No SK DPK Penugasan Kontrak" wire:model="administrasiForm.no_sk_dpk_penugasan_kontrak" />
                    <flux:input label="Tanggal SK DPK" type="date" wire:model="administrasiForm.tgl_sk_dpk_penugasan_kontrak" />
                    <x-mary-textarea label="Keterangan" wire:model="administrasiForm.keterangan" rows="3" />
                    <flux:input
                        label="Tanggal KGB Terakhir"
                        type="date"
                        wire:model="administrasiForm.tgl_kgb_terakhir"
                    />
                    <div>
                        <flux:input
                            label="Link Google Drive SK KGB"
                            wire:model="administrasiForm.sk_kgb_drive_link"
                            placeholder="https://drive.google.com/..."
                        />
                        @if($pegawai->sk_kgb_drive_link)
                            <a href="{{ $pegawai->sk_kgb_drive_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1">
                                <flux:icon name="link" class="w-4 h-4" />
                                Buka Link SK KGB
                            </a>
                        @endif
                    </div>
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan Administrasi" type="submit" class="btn-primary" spinner="saveAdministrasi" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>

        <!-- Tab 4: Pendidikan -->
        <x-mary-tab name="pendidikan-tab" label="Pendidikan" icon="o-academic-cap">
            <x-mary-form wire:submit="savePendidikan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-mary-input label="Tingkat Pendidikan" wire:model="pendidikanForm.tingkat_pendidikan_nama" />
                    <x-mary-input label="Pendidikan" wire:model="pendidikanForm.pendidikan_nama" />
                    <x-mary-input label="Pendidikan Tertinggi" wire:model="pendidikanForm.pendidikan_tertinggi_nama" />
                    <x-mary-input label="Jurusan" wire:model="pendidikanForm.jurusan" />
                    <x-mary-input label="Nama Sekolah" wire:model="pendidikanForm.nama_sekolah" />
                    <x-mary-input label="Nomor Ijazah" wire:model="pendidikanForm.nomor_ijazah" />
                    <x-mary-input label="Tahun Lulus" wire:model="pendidikanForm.tahun_lulus" />
                    <x-mary-textarea label="Riwayat Diklatpim" wire:model="pendidikanForm.riwayat_diklatpim" rows="3" class="md:col-span-2" />
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan Pendidikan" type="submit" class="btn-primary" spinner="savePendidikan" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>

        <!-- Tab 5: Unit & Organisasi -->
        <x-mary-tab name="unit-organisasi-tab" label="Unit & Organisasi" icon="o-building-office">
            <x-mary-form wire:submit="saveUnitOrganisasi">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Unit & Organisasi -->
                    <x-mary-input label="Satuan Kerja" wire:model="unitOrganisasiForm.satuan_kerja" />
                    <x-mary-input label="Unit Kerja" wire:model="unitOrganisasiForm.unit_kerja" />
                    <x-mary-input label="Unit Organisasi" wire:model="unitOrganisasiForm.unit_organisasi" />
                    <x-mary-input label="Nama Unor" wire:model="unitOrganisasiForm.unor_nama" />
                    <x-mary-input label="Instansi Induk" wire:model="unitOrganisasiForm.instansi_induk_nama" />
                    <x-mary-input label="Eselon" wire:model="unitOrganisasiForm.eselon" />
                    <x-mary-input label="Divisi" wire:model="unitOrganisasiForm.divisi" />
                    <x-mary-input label="UKM" wire:model="unitOrganisasiForm.ukm" />

                    <!-- Lokasi -->
                    <x-mary-input label="Provinsi" wire:model="unitOrganisasiForm.provinsi" />
                    <x-mary-input label="Kab/Kota" wire:model="unitOrganisasiForm.kab_kota" />

                    <!-- Status Pegawai -->
                    <x-mary-input label="Jenis Pegawai" wire:model="unitOrganisasiForm.jenis_pegawai" />
                    <x-mary-input label="Status Kepegawaian" wire:model="unitOrganisasiForm.status_kepegwaian" />
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan Unit & Organisasi" type="submit" class="btn-primary" spinner="saveUnitOrganisasi" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>
    </x-mary-tabs>
</div>
