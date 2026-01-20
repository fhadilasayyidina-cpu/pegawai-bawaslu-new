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
</div>
