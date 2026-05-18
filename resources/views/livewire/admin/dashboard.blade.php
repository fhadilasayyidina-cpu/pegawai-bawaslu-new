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
    <div class="my-4 bg-base-200 p-4 rounded-lg">
        <flux:select label="Filter" wire:model.live="kabKota" placeholder="Semua Kabupaten/Kota">
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
    <div class="space-y-6 mt-6">
        <!-- Section 1: Jenis Kelamin -->
        <div>
            <h2 class="text-xl font-bold mb-4">Distribusi Jenis Kelamin</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Column Chart</h3>
                            <button onclick="downloadChart('jk-col-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="jk-col-chart">
                            <livewire:livewire-column-chart :column-chart-model="$jenisKelaminColumnChart" :key="'jk-col-'.$kabKota" />
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Pie Chart</h3>
                            <button onclick="downloadChart('jk-pie-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="jk-pie-chart">
                            <livewire:livewire-pie-chart :pie-chart-model="$jenisKelaminPieChart" :key="'jk-pie-'.$kabKota" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Tingkat Pendidikan -->
        <div>
            <h2 class="text-xl font-bold mb-4">Distribusi Tingkat Pendidikan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Column Chart</h3>
                            <button onclick="downloadChart('pend-col-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="pend-col-chart">
                            <livewire:livewire-column-chart :column-chart-model="$pendidikanColumnChart" :key="'pend-col-'.$kabKota" />
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Pie Chart</h3>
                            <button onclick="downloadChart('pend-pie-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="pend-pie-chart">
                            <livewire:livewire-pie-chart :pie-chart-model="$pendidikanPieChart" :key="'pend-pie-'.$kabKota" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Jenis Jabatan -->
        <div>
            <h2 class="text-xl font-bold mb-4">Distribusi Jenis Jabatan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Column Chart</h3>
                            <button onclick="downloadChart('jj-col-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="jj-col-chart">
                            <livewire:livewire-column-chart :column-chart-model="$jenisJabatanColumnChart" :key="'jj-col-'.$kabKota" />
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Pie Chart</h3>
                            <button onclick="downloadChart('jj-pie-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="jj-pie-chart">
                            <livewire:livewire-pie-chart :pie-chart-model="$jenisJabatanPieChart" :key="'jj-pie-'.$kabKota" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Range Umur -->
        <div>
            <h2 class="text-xl font-bold mb-4">Distribusi Range Umur</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Column Chart</h3>
                            <button onclick="downloadChart('ru-col-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
                                ⬇ Download
                            </button>
                        </div>
                        <div style="height: 20rem;" id="ru-col-chart">
                            <livewire:livewire-column-chart :column-chart-model="$rangeUmurColumnChart" :key="'ru-col-'.$kabKota" />
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="card-title text-sm">Pie Chart</h3>
                            <button onclick="downloadChart('ru-pie-chart')" class="px-3 py-1.5 text-sm bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg cursor-pointer">
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
    </div>

    <script>
        // Override chart text colors to black
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update chart colors
            function updateChartColors() {
                document.querySelectorAll('.apexcharts-text, .apexcharts-datalabel, .apexcharts-datalabel-label, .apexcharts-datalabel-value').forEach(el => {
                    el.style.fill = '#374151';
                    el.style.textShadow = 'none';
                    el.style.filter = 'none';
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

            // Find the Alpine.js component (livewire chart component)
            // The chart component is nested inside the container
            const livewireComponent = container.querySelector('[x-data]');
            if (!livewireComponent) {
                console.error('Livewire chart component not found for:', elementId);
                return;
            }

            // Access the Alpine.js component data to get the chart instance
            const alpineData = Alpine.$data(livewireComponent);
            const chart = alpineData?.chart;

            if (!chart) {
                console.error('Chart instance not found for:', elementId);
                return;
            }

            // Use ApexCharts' built-in dataURI() method to export as PNG
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
