<div>
    <x-header-page title="Manajemen Pegawai" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-modal wire:model="myModal3" title="Import Data Pegawai" persistent separator>
                <form wire:submit="import" enctype="multipart/form-data" class="space-y-5">
                    <div class="rounded-xl border border-brand-gold-500/20 bg-brand-gold-500/5 p-4 text-sm text-slate-700">
                        <p class="font-semibold text-brand-navy-800">Unggah data pegawai dari Excel</p>
                        <p class="mt-1">Gunakan format <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong> (maksimal 10 MB). Kolom wajib: <strong>NIP</strong> dan <strong>Nama</strong>.</p>
                    </div>

                    <div>
                        <label for="file-pegawai" class="mb-2 block text-sm font-semibold text-slate-700">Pilih file data pegawai</label>
                        <input id="file-pegawai" type="file" wire:model="file" accept=".xlsx,.xls,.csv" class="block w-full cursor-pointer rounded-xl border border-slate-300 bg-white text-sm file:mr-4 file:border-0 file:bg-brand-navy-800 file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-brand-navy-700" />
                        <div wire:loading wire:target="file" class="mt-2 text-sm text-brand-navy-700">Mengunggah file…</div>
                        @error('file') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-1">
                        <x-mary-button label="Batal" @click="$wire.myModal3 = false" />
                        <x-mary-button label="Import ke Sistem" icon="o-arrow-up-tray" class="btn-primary" type="submit" spinner="import" />
                    </div>
                </form>
            </x-modal>

            <x-mary-button
                label="Ulang Tahun Pegawai"
                icon="o-cake"
                wire:click="$set('showBirthdayModal', true)"
                class="btn-primary"
            />
            <x-mary-button
                label="Import Data"
                icon="o-arrow-up-tray"
                wire:click="$set('myModal3', true)"
                class="btn-secondary"
            />
        </x-slot:actions>
    </x-header-page>

    <!-- Search and Filters -->
    {{-- Birthday Reminder --}}
    @if($birthdayEmployees->isNotEmpty())
        <div class="birthday-banner my-4 rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #a6192e 0%, #7b1822 40%, #e5ad25 100%); box-shadow: 0 8px 32px rgba(166,25,46,0.35);">
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="flex-shrink-0 text-4xl animate-bounce">🎂</div>
                <div class="flex-1">
                    <h3 class="text-white font-bold text-base mb-1 flex items-center gap-2">
                        🎉 Selamat Ulang Tahun Hari Ini!
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30">
                            {{ $birthdayEmployees->count() }} Pegawai
                        </span>
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($birthdayEmployees as $emp)
                            <a href="/admin/pegawais/{{ $emp->id }}/details"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 hover:bg-white/30 text-white transition-all duration-200 border border-white/20 hover:border-white/40 hover:scale-105">
                                <span class="text-yellow-300">✨</span>
                                <span>{{ $emp->nama }}</span>
                                @if($emp->jabatan_nama)
                                    <span class="opacity-75">— {{ $emp->jabatan_nama }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="flex-shrink-0 text-white/30 text-5xl font-black leading-none">🎈</div>
            </div>
        </div>
    @endif

    <div class="my-4 bg-base-200 p-4 rounded-lg space-y-4">
        <!-- Search Input -->
        <x-mary-input
            wire:model.live.debounce.300ms="search"
            placeholder="Cari berdasarkan nama atau NIP atau nama jabatan ..."
            icon="o-magnifying-glass"
        />

        <!-- Filters Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-mary-select
                label="Kabupaten Kota"
                wire:model.live="kabKota"
                :options="$kabKotaOptions"
                icon="o-map"
                placeholder="Semua Kabupaten/Kota"
            />

            <x-mary-select
                label="Rentang Umur"
                wire:model.live="rangeUmur"
                :options="$rangeUmurOptions"
                icon="o-calendar"
                placeholder="Semua Umur"
            />

            <x-mary-select
                label="Jenis Kelamin"
                wire:model.live="jenisKelamin"
                :options="$jenisKelaminOptions"
                icon="o-user"
                placeholder="Semua Jenis Kelamin"
            />

            <x-mary-select
                label="Agama"
                wire:model.live="agama"
                :options="$agamaOptions"
                icon="o-academic-cap"
                placeholder="Semua Agama"
            />
        </div>
    </div>

    <x-mary-table
        :headers="$tableHeaders"
        :rows="$this->pegawais"
        striped
        with-pagination
        link="/admin/pegawais/{id}/details"
    >
        @scope('cell_nomor', $pegawai)
            {{ ($this->pegawais->currentPage() - 1) * $this->pegawais->perPage() + $loop->iteration }}
        @endscope

        @scope('actions', $pegawai)
            <x-mary-button
                icon="o-trash"
                wire:click="delete({{ $pegawai->id }})"
                class="btn-ghost text-error btn-sm"
                wire:confirm="Yakin mau hapus?"
            />
        @endscope
    </x-mary-table>

    @if($importResult)
        <div class="mt-5 rounded-2xl border border-brand-gold-500/20 bg-white p-5 premium-shadow">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-bold text-brand-navy-800">Hasil impor terakhir</h2>
                    <p class="text-sm text-slate-500">Data dengan NIP yang sama diperbarui secara otomatis.</p>
                </div>
                <div class="flex gap-3 text-sm font-semibold">
                    <span class="rounded-lg bg-emerald-50 px-3 py-2 text-emerald-700">Baru: {{ $importResult['created'] }}</span>
                    <span class="rounded-lg bg-amber-50 px-3 py-2 text-amber-700">Diperbarui: {{ $importResult['updated'] }}</span>
                    @if($importResult['skipped'] || $importResult['failed'])
                        <span class="rounded-lg bg-red-50 px-3 py-2 text-red-700">Dilewati/Gagal: {{ $importResult['skipped'] + $importResult['failed'] }}</span>
                    @endif
                </div>
            </div>
            @if(!empty($importResult['errors']))
                <details class="mt-4 text-sm text-slate-600">
                    <summary class="cursor-pointer font-semibold">Lihat detail baris yang tidak diimpor</summary>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach(array_slice($importResult['errors'], 0, 10) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </div>
    @endif

    <!-- Modal Ulang Tahun Pegawai Per Bulan -->
    <x-modal wire:model="showBirthdayModal" title="🎂 Daftar Ulang Tahun Pegawai Per Bulan" class="backdrop-blur" box-class="max-w-4xl">
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                <div class="w-full sm:w-64">
                    <x-mary-select
                        label="Pilih Bulan"
                        wire:model.live="selectedBirthdayMonth"
                        :options="$monthOptions"
                        icon="o-calendar"
                    />
                </div>
                <div class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    Total: <span class="font-bold text-amber-600 dark:text-amber-400 text-base">{{ $this->monthlyBirthdayEmployees->count() }}</span> Pegawai Ulang Tahun
                </div>
            </div>

            <div class="overflow-x-auto max-h-[60vh]">
                <table class="table table-zebra w-full text-sm">
                    <thead class="sticky top-0 bg-base-100 z-10 shadow-xs">
                        <tr>
                            <th class="w-1">No</th>
                            <th>Nama / NIP</th>
                            <th>Tanggal Ulang Tahun</th>
                            <th>Jabatan</th>
                            <th>Kabupaten / Kota</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->monthlyBirthdayEmployees as $index => $emp)
                            @php
                                $isToday = $emp->tgl_lahir && $emp->tgl_lahir->day === now()->day && $emp->tgl_lahir->month === now()->month;
                                $age = $emp->tgl_lahir ? $emp->tgl_lahir->age : null;
                            @endphp
                            <tr class="{{ $isToday ? 'bg-amber-500/10 font-medium' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                        <span>{{ $emp->nama }}</span>
                                        @if($isToday)
                                            <span class="badge badge-warning text-xs font-bold animate-pulse">Hari Ini 🎉</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $emp->nip_baru ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-amber-700 dark:text-amber-400">
                                        {{ $emp->tgl_lahir?->translatedFormat('d F Y') ?? '-' }}
                                    </div>
                                    @if($age !== null)
                                        <div class="text-xs text-slate-500">{{ $age }} Tahun</div>
                                    @endif
                                </td>
                                <td>{{ $emp->jabatan_nama ?? '-' }}</td>
                                <td>{{ $emp->kab_kota ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="/admin/pegawais/{{ $emp->id }}/details"
                                       class="btn btn-ghost btn-xs text-info"
                                       title="Lihat Detail">
                                        <x-mary-icon name="o-eye" class="w-4 h-4" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-500">
                                    Tidak ada pegawai yang berulang tahun pada bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Tutup" @click="$wire.showBirthdayModal = false" />
        </x-slot:actions>
    </x-modal>
</div>
