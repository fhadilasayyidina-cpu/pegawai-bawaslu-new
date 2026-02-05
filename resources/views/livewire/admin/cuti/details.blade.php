<div>
    <x-header-page title="Detail Cuti" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                icon="document-arrow-down"
                variant="primary"
                onclick="window.open('{{ route('cuti.pdf', ['id' => $cuti->id]) }}', '_blank')"
            >
                Cetak PDF
            </flux:button>
            <x-mary-button
                label="Edit"
                icon="o-pencil"
                link="/admin/cutis/{{ $cuti->id }}/edit"
                variant="secondary"
            />
            <x-mary-button
                label="Kembali"
                icon="o-arrow-left"
                link="/admin/cutis"
                variant="ghost"
            />
        </x-slot:actions>
    </x-header-page>

    <div class="max-w-4xl my-4 space-y-4">
        <!-- Info Pegawai -->
        <x-mary-card>
            <flux:heading size="sm" class="mb-4">Informasi Pegawai</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-input label="Nama" :value="$cuti->pegawai->nama" readonly />
                <x-mary-input label="NIP" :value="$cuti->pegawai->nip_baru" readonly />
                <x-mary-input label="Jabatan" :value="$cuti->pegawai->jabatan_nama ?: '-'" readonly />
                <x-mary-input label="Golongan" :value="$cuti->pegawai->gol_nama ?: '-'" readonly />
            </div>
        </x-mary-card>

        <!-- Info Cuti -->
        <x-mary-card>
            <flux:heading size="sm" class="mb-4">Informasi Cuti</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-input label="Nomor Surat" :value="$cuti->nomor_surat" readonly />
                <x-mary-input label="Jenis Cuti" :value="\Illuminate\Support\Str::title(str_replace('_', ' ', $cuti->jenis_cuti))" readonly />
                <x-mary-input label="Tanggal Mulai" :value="$cuti->tanggal_mulai->format('d F Y')" readonly />
                <x-mary-input label="Tanggal Selesai" :value="$cuti->tanggal_selesai->format('d F Y')" readonly />
                <x-mary-input label="Lama Hari" :value="$cuti->lama_hari . ' hari'" readonly class="md:col-span-2" />
                <x-mary-textarea label="Alasan" :value="$cuti->alasan" readonly rows="2" class="md:col-span-2" />

                {{-- Fields khusus Cuti Sakit --}}
                @if($cuti->jenis_cuti === 'sakit')
                    @if($cuti->status_dokter)
                        <x-mary-input label="Status Dokter" :value="ucfirst($cuti->status_dokter)" readonly />
                    @endif
                    @if($cuti->nama_dokter)
                        <x-mary-input label="Nama Dokter" :value="$cuti->nama_dokter" readonly />
                    @endif
                    @if($cuti->nomor_surat_dokter)
                        <x-mary-input label="Nomor Surat Dokter" :value="$cuti->nomor_surat_dokter" readonly class="md:col-span-2" />
                    @endif
                @endif

                {{-- Fields khusus Cuti Melahirkan --}}
                @if($cuti->jenis_cuti === 'melahirkan')
                    @if($cuti->jenis_melahirkan)
                        <x-mary-input label="Jenis Melahirkan" :value="ucfirst($cuti->jenis_melahirkan)" readonly />
                    @endif
                    @if($cuti->tanggal_perkiraan_lahir)
                        <x-mary-input label="Tanggal Perkiraan Lahir" :value="$cuti->tanggal_perkiraan_lahir->format('d F Y')" readonly />
                    @endif
                @endif

                {{-- Fields khusus Cuti Luar Tanggungan --}}
                @if($cuti->jenis_cuti === 'luar_tanggungan')
                    @if($cuti->alasan_luar_tanggungan)
                        <x-mary-textarea label="Alasan Luar Tanggungan" :value="$cuti->alasan_luar_tanggungan" readonly rows="2" class="md:col-span-2" />
                    @endif
                    <x-mary-input label="Tanpa Gaji" :value="$cuti->tanpa_gaji ? 'Ya' : 'Tidak'" readonly />
                @endif

                @if($cuti->keterangan)
                    <x-mary-textarea label="Keterangan" :value="$cuti->keterangan" readonly rows="2" class="md:col-span-2" />
                @endif
            </div>
        </x-mary-card>

        <!-- Informasi Penandatangan -->
        <x-mary-card>
            <flux:heading size="sm" class="mb-4">Informasi Penandatangan</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-input label="Kepala Sekretariat" :value="$cuti->nama_kepala_sekretariat" readonly />
                <x-mary-input label="NIP" :value="$cuti->nip_kepala_sekretariat ?: '-'" readonly />
                <x-mary-input label="Sekjen" :value="$cuti->nama_sekjen" readonly />
                <x-mary-input label="NIP" :value="$cuti->nip_sekjen ?: '-'" readonly />
                @if($cuti->nomor_surat_edaran)
                    <x-mary-input label="Nomor Surat Edaran" :value="$cuti->nomor_surat_edaran" readonly class="md:col-span-2" />
                @endif
            </div>
        </x-mary-card>
    </div>
</div>
