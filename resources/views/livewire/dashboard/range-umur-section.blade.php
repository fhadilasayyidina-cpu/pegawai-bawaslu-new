<div>
    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
        Distribusi Range Umur
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="dashboard-chart-card rounded-2xl premium-shadow p-6 hover-premium">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Kolom</h3>
                <button onclick="downloadChart('ru-col-chart')"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                    ⬇ Download
                </button>
            </div>
            <div style="height: 20rem;" id="ru-col-chart">
                <livewire:livewire-column-chart :column-chart-model="$rangeUmurColumnChart" :key="'ru-col-' . $kabKota" />
            </div>
        </div>

        <div class="dashboard-chart-card rounded-2xl premium-shadow p-6 hover-premium">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-300">Grafik Lingkaran</h3>
                <button onclick="downloadChart('ru-pie-chart')"
                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-1 cursor-pointer border border-slate-200/50 dark:border-slate-700/50">
                    ⬇ Download
                </button>
            </div>
            <div style="height: 20rem;" id="ru-pie-chart">
                <livewire:livewire-pie-chart :pie-chart-model="$rangeUmurPieChart" :key="'ru-pie-' . $kabKota" />
            </div>
        </div>
    </div>
</div>
