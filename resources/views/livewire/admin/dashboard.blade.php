<div class="dashboard-shell">
    <style>
        /* Override chart text colors to dark gray */
        .apexcharts-text,
        .apexcharts-datalabel,
        .apexcharts-datalabel-label,
        .apexcharts-datalabel-value {
            fill: #374151 !important;
            text-shadow: none !important;
            filter: none !important;
        }

        .apexcharts-tooltip-text {
            color: #374151 !important;
            text-shadow: none !important;
        }

        .apexcharts-datalabels text {
            filter: none !important;
        }

        /* Use a legible default size across axes, legends, and values. */
        .apexcharts-xaxis-label,
        .apexcharts-yaxis-label {
            font-size: 12px !important;
            font-weight: 500 !important;
        }

        .apexcharts-legend-text {
            font-size: 12px !important;
            font-weight: 500 !important;
        }

        .apexcharts-datalabel,
        .apexcharts-datalabel-label,
        .apexcharts-datalabel-value {
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        /* The job-category pie has many legend items: keep its legend compact
           so the chart itself remains the visual focus. */
        #jj-pie-chart .apexcharts-legend-text {
            font-size: 10px !important;
        }
    </style>

    <x-header-page title="Dashboard" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Dashboard']]" />

    {{-- Birthday Reminder --}}
    @if ($pegawaiUlangTahun->isNotEmpty())
        <div class="birthday-banner my-4 rounded-2xl overflow-hidden"
            style="background: linear-gradient(135deg, #a6192e 0%, #7b1822 40%, #e5ad25 100%); box-shadow: 0 8px 32px rgba(166,25,46,0.35);">
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="flex-shrink-0 text-4xl animate-bounce">🎂</div>
                <div class="flex-1">
                    <h3 class="text-white font-bold text-base mb-1 flex items-center gap-2">
                        🎉 Selamat Ulang Tahun Hari Ini!
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30">
                            {{ $pegawaiUlangTahun->count() }} Pegawai
                        </span>
                    </h3>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($pegawaiUlangTahun as $emp)
                            <a href="/admin/pegawais/{{ $emp->id }}/details"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400 text-slate-900 border border-amber-300 shadow-md transition-all duration-200 hover:scale-105 animate-pulse">
                                <span>🎉</span>
                                <span>{{ $emp->nama }}</span>
                                <span class="opacity-90">({{ $emp->tgl_lahir?->format('d/m') }})</span>
                                @if ($emp->unit_kerja)
                                    <span class="opacity-75">— {{ $emp->unit_kerja }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="flex-shrink-0 text-white/30 text-5xl font-black leading-none">🎈</div>
            </div>
        </div>
    @endif

    <!-- Filter Kabupaten Kota -->
    <div class="dashboard-filter-card my-4 p-5 rounded-2xl premium-shadow">
        <flux:select label="Filter Kabupaten/Kota" wire:model.live="kabKota" placeholder="Semua Kabupaten/Kota"
            class="max-w-md">
            @foreach ($kabKotaOptions as $option)
                <flux:select.option :value="$option->id">{{ $option->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <x-statistic-card title="Total Pegawai" :value="$statistics['total']" desc="Total seluruh pegawai" color="primary" />

        <x-statistic-card title="PPPK" :value="$statistics['pppk']" desc="Pegawai PPPK" color="success" />

        <x-statistic-card title="PNS Organik" :value="$statistics['organik']" desc="PNS Organik" color="info" />

        <x-statistic-card title="PNS DPK" :value="$statistics['dpk']" desc="PNS DPK" color="warning" />

    </div>

    <!-- Charts Section -->
    <div class="space-y-8 mt-8">
        <!-- Section 1: Jenis Kelamin -->
        <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                Distribusi Jenis Kelamin
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="dashboard-chart-card rounded-2xl premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Kolom</h3>
                        <button onclick="downloadChart('jk-col-chart')"
                            class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="jk-col-chart">
                        <livewire:livewire-column-chart :column-chart-model="$jenisKelaminColumnChart" :key="'jk-col-' . $kabKota" />
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="dashboard-chart-card rounded-2xl premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Lingkaran</h3>
                        <button onclick="downloadChart('jk-pie-chart')"
                            class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="jk-pie-chart">
                        <livewire:livewire-pie-chart :pie-chart-model="$jenisKelaminPieChart" :key="'jk-pie-' . $kabKota" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Tingkat Pendidikan -->
        <livewire:dashboard.pendidikan-section :kab-kota="$kabKota" :key="'pendidikan-' . $kabKota" lazy />

        <livewire:dashboard.jabatan-section :kab-kota="$kabKota" :key="'pendidikan-' . $kabKota" lazy />

        <livewire:dashboard.range-umur-section :kab-kota="$kabKota" :key="'pendidikan-' . $kabKota" lazy />
    </div>

</div>
