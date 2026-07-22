<?php

use App\Models\KgbRecord;
use App\Models\Pegawai;
use App\Services\Kgb\KgbService;
use Carbon\Carbon;

beforeEach(function () {
    $p1 = Pegawai::factory()->create([
        'nama' => 'Pegawai KGB Bulan Ini',
        'nip_baru' => '198001012000121001',
        'status_kepegwaian' => 'Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->subYears(2)->subDays(5)->format('Y-m-d'),
        'kab_kota' => 'Jakarta',
    ]);

    KgbRecord::create([
        'pegawai_id' => $p1->id,
        'jenis_kgb' => 'PNS',
        'nomor_naskah' => '001/PNS/2026',
        'tanggal_naskah' => Carbon::now(),
        'tmt_baru' => Carbon::now()->subDays(5),
        'next_kgb_date' => Carbon::now()->addYears(2),
        'data' => [],
    ]);

    $p2 = Pegawai::factory()->create([
        'nama' => 'Pegawai KGB 2 Tahun Lagi',
        'nip_baru' => '198001022000122002',
        'status_kepegwaian' => 'Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->format('Y-m-d'),
        'kab_kota' => 'Bandung',
    ]);

    KgbRecord::create([
        'pegawai_id' => $p2->id,
        'jenis_kgb' => 'PNS',
        'nomor_naskah' => '002/PNS/2026',
        'tanggal_naskah' => Carbon::now(),
        'tmt_baru' => Carbon::now()->addMonths(18),
        'next_kgb_date' => Carbon::now()->addMonths(18),
        'data' => [],
    ]);

    $p3 = Pegawai::factory()->create([
        'nama' => 'Pegawai KGB 1.5 Tahun Lagi',
        'nip_baru' => '198001032000123003',
        'status_kepegwaian' => 'Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->subMonths(6)->format('Y-m-d'),
        'kab_kota' => 'Jakarta',
    ]);

    KgbRecord::create([
        'pegawai_id' => $p3->id,
        'jenis_kgb' => 'PPPK',
        'nomor_naskah' => '003/PPPK/2026',
        'tanggal_naskah' => Carbon::now(),
        'tmt_baru' => Carbon::now()->addMonths(2),
        'next_kgb_date' => Carbon::now()->addMonths(2),
        'data' => [],
    ]);
});

test('kgb service shows all employees when monthsAhead is 0', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(0);

    expect($result)->toHaveCount(3);
});

test('kgb service filters by 6 months default', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(6);

    expect($result)->toHaveCount(2);
});

test('kgb service filters by 24 months (2 years)', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(24);

    expect($result)->toHaveCount(3);
});

test('kgb service filters by kabupaten kota', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(0, 'Jakarta');

    expect($result)->toHaveCount(2);
});

test('kgb service statistics returns correct counts', function () {
    $service = app(KgbService::class);
    $stats = $service->getStatistics(0);

    expect($stats['total'])->toBe(3)
        ->and($stats['pns'])->toBe(2)
        ->and($stats['pppk'])->toBe(1);
});
