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

                {{-- Info Jatah Cuti --}}
                @if($jatahCutiInfo)
                    <flux:callout variant="{{ $jatahCutiInfo['layak'] ? 'success' : 'warning' }}" class="mb-4">
                        @if($jatahCutiInfo['layak'])
                            @if($jenis_cuti === 'besar')
                                {{-- Info untuk Cuti Besar --}}
                                <div class="space-y-1">
                                    <p class="font-semibold">Kuota Cuti Besar</p>
                                    <p class="text-2xl font-bold">{{ $jatahCutiInfo['sisa_kuota'] }} dari {{ $jatahCutiInfo['kuota_maksimal'] }} kali</p>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Masa kerja: {{ $jatahCutiInfo['masa_kerja_tahun'] }} tahun ({{ $jatahCutiInfo['masa_kerja_bulan'] }} bulan)</p>
                                        <p>Sudah diambil: {{ $jatahCutiInfo['jumlah_sudah_diambil'] }} kali</p>
                                        @if($jatahCutiInfo['tanggal_cuti_besar_terakhir'])
                                            <p>Cuti besar terakhir: {{ $jatahCutiInfo['tanggal_cuti_besar_terakhir'] }}</p>
                                        @endif
                                        @if($jatahCutiInfo['ada_cuti_tahunan_tahun_ini'])
                                            <p class="text-amber-600 dark:text-amber-400 font-medium">⚠️ Sudah mengambil cuti tahunan tahun ini</p>
                                        @endif
                                    </div>
                                </div>
                            @elseif($jenis_cuti === 'sakit')
                                {{-- Info untuk Cuti Sakit --}}
                                <div class="space-y-1">
                                    <p class="font-semibold">Cuti Sakit</p>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Sakit 1-2 hari: Tidak perlu surat dokter</p>
                                        <p>Sakit 3-14 hari: Wajib surat dokter</p>
                                        <p>Sakit > 14 hari: Wajib surat dokter pemerintah</p>
                                    </div>
                                </div>
                            @elseif($jenis_cuti === 'melahirkan')
                                {{-- Info untuk Cuti Melahirkan --}}
                                <div class="space-y-1">
                                    <p class="font-semibold">Cuti Melahirkan</p>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Durasi maksimal: 6 bulan (180 hari)</p>
                                        <p>3 bulan sebelum + 3 bulan sesudah</p>
                                    </div>
                                </div>
                            @elseif($jenis_cuti === 'alasan_penting')
                                {{-- Info untuk Cuti Alasan Penting --}}
                                <div class="space-y-1">
                                    <p class="font-semibold">Cuti Alasan Penting</p>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Durasi maksimal: 2 bulan (60 hari)</p>
                                        <p>Contoh: Kematian keluarga, pernikahan anak, dll</p>
                                    </div>
                                </div>
                            @elseif($jenis_cuti === 'luar_tanggungan')
                                {{-- Info untuk Cuti Luar Tanggungan --}}
                                <div class="space-y-1">
                                    <p class="font-semibold">Cuti di Luar Tanggungan Negara</p>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Durasi maksimal: 3 tahun (1095 hari)</p>
                                        <p class="text-amber-600 dark:text-amber-400 font-medium">⚠️ Tidak menerima gaji selama cuti</p>
                                    </div>
                                </div>
                            @else
                                {{-- Info untuk Cuti Tahunan --}}
                                <div class="space-y-1">
                                    <p class="font-semibold">Jatah Cuti Tersedia</p>
                                    <p class="text-2xl font-bold">{{ $jatahCutiInfo['jatah_tersedia'] }} hari</p>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Tahun berjalan: {{ $jatahCutiInfo['rincian']['tahun_berjalan'] }} hari</p>
                                        <p>Sisa tahun lalu: {{ $jatahCutiInfo['rincian']['tahun_lalu'] }} hari (maks 6)</p>
                                        <p>Sisa 2 tahun lalu: {{ $jatahCutiInfo['rincian']['dua_tahun_lalu'] }} hari (maks 6)</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="space-y-1">
                                <p class="font-semibold">Tidak Layak @if($jenis_cuti === 'besar')Cuti Besar@elseif($jenis_cuti === 'sakit')Cuti Sakit@elseif($jenis_cuti === 'melahirkan')Cuti Melahirkan@elseif($jenis_cuti === 'alasan_penting')Cuti Alasan Penting@elseif($jenis_cuti === 'luar_tanggungan')Cuti Luar Tanggungan@elseCuti Tahunan@endif</p>
                                @foreach($jatahCutiInfo['alasan'] as $alasan)
                                    <p>{{ $alasan }}</p>
                                @endforeach
                            </div>
                        @endif
                    </flux:callout>
                @endif

                <x-mary-input
                    label="Nomor Surat"
                    wire:model="nomor_surat"
                    placeholder="Contoh: 001/BAWASLU/RI/00/2025"
                    required
                    icon="o-document"
                />

                <flux:select label="Jenis Cuti" wire:model.live="jenis_cuti" required>
                    <flux:select.option value="tahunan">Cuti Tahunan</flux:select.option>
                    <flux:select.option value="besar">Cuti Besar</flux:select.option>
                    <flux:select.option value="sakit">Cuti Sakit</flux:select.option>
                    <flux:select.option value="melahirkan">Cuti Melahirkan</flux:select.option>
                    <flux:select.option value="alasan_penting">Cuti Alasan Penting</flux:select.option>
                    <flux:select.option value="luar_tanggungan">Cuti Luar Tanggungan Negara</flux:select.option>
                </flux:select>

                <x-mary-textarea
                    label="Alasan Cuti"
                    wire:model="alasan"
                    placeholder="Masukkan alasan cuti"
                    required
                    rows="3"
                />

                {{-- Fields khusus Cuti Sakit --}}
                @if($jenis_cuti === 'sakit')
                    <flux:select label="Status Dokter" wire:model="status_dokter">
                        <flux:select.option value="">Pilih status dokter</flux:select.option>
                        <flux:select.option value="swasta">Dokter Swasta</flux:select.option>
                        <flux:select.option value="pemerintah">Dokter Pemerintah</flux:select.option>
                    </flux:select>

                    <x-mary-input
                        label="Nama Dokter"
                        wire:model="nama_dokter"
                        placeholder="Nama lengkap dokter"
                    />
                    <x-mary-input
                        label="Nomor Surat Dokter"
                        wire:model="nomor_surat_dokter"
                        placeholder="Nomor surat dokter"
                    />
                @endif

                {{-- Fields khusus Cuti Melahirkan --}}
                @if($jenis_cuti === 'melahirkan')
                    <flux:select label="Jenis Melahirkan" wire:model="jenis_melahirkan">
                        <flux:select.option value="">Pilih jenis melahirkan</flux:select.option>
                        <flux:select.option value="normal">Normal</flux:select.option>
                        <flux:select.option value="caesar">Caesar</flux:select.option>
                    </flux:select>

                    <flux:input
                        label="Tanggal Perkiraan Lahir"
                        type="date"
                        wire:model="tanggal_perkiraan_lahir"
                    />
                @endif

                {{-- Fields khusus Cuti Luar Tanggungan --}}
                @if($jenis_cuti === 'luar_tanggungan')
                    <x-mary-textarea
                        label="Alasan Luar Tanggungan"
                        wire:model="alasan_luar_tanggungan"
                        rows="2"
                        placeholder="Jelaskan alasan cuti luar tanggungan"
                    />

                    <flux:checkbox label="Tanpa Gaji" wire:model="tanpa_gaji" />
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        ⚠️ Pegawai tidak menerima gaji selama cuti luar tanggungan
                    </p>
                @endif

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

                {{-- Info durasi untuk cuti besar --}}
                @if($jenis_cuti === 'besar' && $lama_hari > 0)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Durasi: {{ floor($lama_hari / 30) }} bulan {{ $lama_hari % 30 }} hari (maksimal 3 bulan / 90 hari)
                    </p>
                @endif

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
