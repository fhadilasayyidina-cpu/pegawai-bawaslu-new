<div>
    <x-header-page title="Detail Absensi" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                icon="pencil"
                variant="secondary"
                href="/admin/absensis/{{ $absensi->id }}/edit"
            >
                Edit
            </flux:button>
            <flux:button
                icon="arrow-left"
                variant="ghost"
                href="/admin/absensis"
            >
                Kembali
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="max-w-4xl my-4 space-y-4">
        <!-- Info Pegawai -->
        <x-mary-card>
            <flux:heading size="sm" class="mb-4">Informasi Pegawai</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    label="Nama"
                    :value="$absensi->pegawai->nama"
                    readonly
                />
                <flux:input
                    label="NIP"
                    :value="$absensi->pegawai->nip_baru"
                    readonly
                />
                <flux:input
                    label="Jabatan"
                    :value="$absensi->pegawai->jabatan_nama ?: '-'"
                    readonly
                />
                <flux:input
                    label="Golongan"
                    :value="$absensi->pegawai->gol_nama ?: '-'"
                    readonly
                />
            </div>
        </x-mary-card>

        <!-- Info Absensi -->
        <x-mary-card>
            <flux:heading size="sm" class="mb-4">Informasi Absensi</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input
                    label="Tanggal"
                    :value="$absensi->tanggal->format('d F Y')"
                    readonly
                />
                <flux:input
                    label="Status"
                    :value="$absensi->status"
                    readonly
                >
                    @switch($absensi->status)
                        @case('Hadir')
                            <flux:badge slot="suffix" variant="success">Hadir</flux:badge>
                            @break
                        @case('Izin')
                            <flux:badge slot="suffix" variant="warning">Izin</flux:badge>
                            @break
                        @case('Sakit')
                            <flux:badge slot="suffix" variant="danger">Sakit</flux:badge>
                            @break
                        @case('Cuti')
                            <flux:badge slot="suffix" variant="info">Cuti</flux:badge>
                            @break
                        @case('Bolos')
                            <flux:badge slot="suffix" variant="danger">Bolos</flux:badge>
                            @break
                    @endswitch
                </flux:input>
                @if($absensi->keterangan)
                    <flux:textarea
                        label="Keterangan"
                        :value="$absensi->keterangan"
                        readonly
                        rows="3"
                        class="md:col-span-2"
                    />
                @endif
                <flux:input
                    label="Dibuat Oleh"
                    :value="$absensi->createdBy?->name ?? '-'"
                    readonly
                />
                <flux:input
                    label="Tanggal Dibuat"
                    :value="$absensi->created_at->format('d F Y H:i')"
                    readonly
                />
            </div>
        </x-mary-card>

        <!-- Delete Confirmation -->
        <x-mary-card>
            <flux:heading size="sm" class="mb-4">Hapus Data</flux:heading>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Menghapus data absensi tidak dapat dibatalkan.
            </p>
            <flux:button
                variant="danger"
                icon="trash"
                wire:click="delete"
                wire:confirm="Yakin ingin menghapus data absensi ini?"
            >
                Hapus Absensi
            </flux:button>
        </x-mary-card>
    </div>
</div>
