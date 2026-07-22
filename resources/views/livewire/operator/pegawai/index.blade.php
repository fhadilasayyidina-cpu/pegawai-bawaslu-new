<div>
    <x-header-page title="Data Pegawai" :breadcrumbs="$breadcrumbs">
        {{-- Hide import button for operator --}}
    </x-header-page>

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
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($birthdayEmployees as $emp)
                            <a href="/operator/pegawais/{{ $emp->id }}/details"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400 text-slate-900 font-bold border border-amber-300 shadow-md animate-pulse hover:scale-105 transition-all">
                                <span>🎉</span>
                                <span>{{ $emp->nama }}</span>
                                @if($emp->jabatan_nama)
                                    <span class="opacity-80">— {{ $emp->jabatan_nama }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="flex-shrink-0 text-white/30 text-5xl font-black leading-none">🎈</div>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="my-4 bg-base-200 p-4 rounded-lg space-y-4">
        <!-- Search Input -->
        <x-mary-input
            wire:model.live.debounce.300ms="search"
            placeholder="Cari berdasarkan nama atau NIP..."
            icon="o-magnifying-glass"
        />

        <!-- Filters Grid - hide kab_kota filter for operator since it's auto-filtered -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        link="/operator/pegawais/{id}/details"
    >
        @scope('cell_nomor', $pegawai)
            {{ ($this->pegawais->currentPage() - 1) * $this->pegawais->perPage() + $loop->iteration }}
        @endscope

        {{-- Hide delete button for operator --}}
    </x-mary-table>
</div>
