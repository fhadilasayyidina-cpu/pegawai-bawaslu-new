<div>
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
    </style>

    <x-header-page title="Dashboard" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Dashboard']]" />

    <!-- Filter Kabupaten Kota -->
    <div class="my-4 p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 premium-shadow">
        <flux:select label="Filter Kabupaten/Kota" wire:model.live="kabKota" placeholder="Semua Kabupaten/Kota" class="max-w-md">
            @foreach($kabKotaOptions as $option)
                <flux:select.option :value="$option->id">{{ $option->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <x-statistic-card
            title="Total Pegawai"
            :value="$statistics['total']"
            desc="Total seluruh pegawai"
            color="primary"
        />

        <x-statistic-card
            title="PPPK"
            :value="$statistics['pppk']"
            desc="Pegawai PPPK"
            color="success"
        />

        <x-statistic-card
            title="PNS Organik"
            :value="$statistics['organik']"
            desc="PNS Organik"
            color="info"
        />

        <x-statistic-card
            title="PNS DPK"
            :value="$statistics['dpk']"
            desc="PNS DPK"
            color="warning"
        />

        <x-statistic-card
            title="PPNPN"
            :value="$statistics['ppnpn']"
            desc="PPNPN"
            color="secondary"
        />
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
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Kolom</h3>
                        <button onclick="downloadChart('jk-col-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="jk-col-chart">
                        <livewire:livewire-column-chart :column-chart-model="$jenisKelaminColumnChart" :key="'jk-col-'.$kabKota" />
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Lingkaran</h3>
                        <button onclick="downloadChart('jk-pie-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="jk-pie-chart">
                        <livewire:livewire-pie-chart :pie-chart-model="$jenisKelaminPieChart" :key="'jk-pie-'.$kabKota" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Tingkat Pendidikan -->
        <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Distribusi Tingkat Pendidikan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Kolom</h3>
                        <button onclick="downloadChart('pend-col-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="pend-col-chart">
                        <livewire:livewire-column-chart :column-chart-model="$pendidikanColumnChart" :key="'pend-col-'.$kabKota" />
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Lingkaran</h3>
                        <button onclick="downloadChart('pend-pie-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="pend-pie-chart">
                        <livewire:livewire-pie-chart :pie-chart-model="$pendidikanPieChart" :key="'pend-pie-'.$kabKota" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Jenis Jabatan -->
        <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                Distribusi Jenis Jabatan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Kolom</h3>
                        <button onclick="downloadChart('jj-col-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="jj-col-chart">
                        <livewire:livewire-column-chart :column-chart-model="$jenisJabatanColumnChart" :key="'jj-col-'.$kabKota" />
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Lingkaran</h3>
                        <button onclick="downloadChart('jj-pie-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="jj-pie-chart">
                        <livewire:livewire-pie-chart :pie-chart-model="$jenisJabatanPieChart" :key="'jj-pie-'.$kabKota" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Range Umur -->
        <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                Distribusi Range Umur
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Kolom</h3>
                        <button onclick="downloadChart('ru-col-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="ru-col-chart">
                        <livewire:livewire-column-chart :column-chart-model="$rangeUmurColumnChart" :key="'ru-col-'.$kabKota" />
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="rounded-2xl border border-slate-200/50 dark:border-slate-800/50 bg-white dark:bg-slate-900/60 premium-shadow p-6 hover-premium">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Lingkaran</h3>
                        <button onclick="downloadChart('ru-pie-chart')" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                            ⬇ Download
                        </button>
                    </div>
                    <div style="height: 20rem;" id="ru-pie-chart">
                        <livewire:livewire-pie-chart :pie-chart-model="$rangeUmurPieChart" :key="'ru-pie-'.$kabKota" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update chart colors dynamically based on light/dark mode
            function updateChartColors() {
                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#cbd5e1' : '#475569';
                
                document.querySelectorAll('.apexcharts-text, .apexcharts-datalabel, .apexcharts-datalabel-label, .apexcharts-datalabel-value').forEach(el => {
                    el.style.fill = textColor;
                    el.style.textShadow = 'none';
                    el.style.filter = 'none';
                });
                
                document.querySelectorAll('.apexcharts-tooltip-text').forEach(el => {
                    el.style.color = '#1e293b';
                });
            }

            // Run immediately and after chart updates
            updateChartColors();

            // Use MutationObserver to catch dynamically added chart elements
            const observer = new MutationObserver(function(mutations) {
                updateChartColors();
            });

            // Observe all chart containers
            document.querySelectorAll('[id*="-chart"]').forEach(function(container) {
                observer.observe(container, { childList: true, subtree: true });
            });

            // Also run on Livewire updates
            if (window.Livewire) {
                window.Livewire.hook('message.processed', () => {
                    setTimeout(updateChartColors, 100);
                });
            }
        });

        function downloadChart(elementId) {
            const container = document.getElementById(elementId);
            const livewireComponent = container.querySelector('[x-data]');
            if (!livewireComponent) {
                console.error('Livewire chart component not found for:', elementId);
                return;
            }

            const alpineData = Alpine.$data(livewireComponent);
            const chart = alpineData?.chart;

            if (!chart) {
                console.error('Chart instance not found for:', elementId);
                return;
            }

            chart.dataURI().then(({ imgURI }) => {
                const link = document.createElement('a');
                link.href = imgURI;
                link.download = elementId + '-' + new Date().getTime() + '.png';
                link.click();
            }).catch((error) => {
                console.error('Failed to export chart:', error);
            });
        }
    </script>

    @livewireChartsScripts
</div>
