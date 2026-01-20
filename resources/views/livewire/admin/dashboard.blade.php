<div>
    <x-header-page title="Dashboard" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Dashboard']]" />

    <!-- Filter Kabupaten Kota -->
    <div class="my-4 bg-base-200 p-4 rounded-lg">
        <flux:select label="Filter Kabupaten Kota" wire:model.live="kabKota" placeholder="Semua Kabupaten/Kota">
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
                        <h3 class="card-title text-sm">Column Chart</h3>
                        <div style="height: 20rem;">
                            <livewire:livewire-column-chart :column-chart-model="$jenisKelaminColumnChart" :key="'jk-col-'.$kabKota" />
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-sm">Pie Chart</h3>
                        <div style="height: 20rem;">
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
                        <h3 class="card-title text-sm">Column Chart</h3>
                        <div style="height: 20rem;">
                            <livewire:livewire-column-chart :column-chart-model="$pendidikanColumnChart" :key="'pend-col-'.$kabKota" />
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-sm">Pie Chart</h3>
                        <div style="height: 20rem;">
                            <livewire:livewire-pie-chart :pie-chart-model="$pendidikanPieChart" :key="'pend-pie-'.$kabKota" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireChartsScripts
</div>
