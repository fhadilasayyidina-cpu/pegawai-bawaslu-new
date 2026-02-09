<?php

use App\Models\Pegawai;
use App\Services\Kgb\KgbService;
use Carbon\Carbon;

beforeEach(function () {
    // Create pegawai with various KGB dates for testing
    Pegawai::factory()->create([
        'nama' => 'Pegawai KGB Bulan Ini',
        'nip_baru' => '198001012000121001',
        'status_kepegwaian' => 'Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->subYears(2)->subDays(5)->format('Y-m-d'),
        'kab_kota' => 'Jakarta',
    ]);

    Pegawai::factory()->create([
        'nama' => 'Pegawai KGB 2 Tahun Lagi',
        'nip_baru' => '198001022000122002',
        'status_kepegwaian' => 'Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->format('Y-m-d'),
        'kab_kota' => 'Bandung',
    ]);

    Pegawai::factory()->create([
        'nama' => 'Pegawai KGB 1.5 Tahun Lagi',
        'nip_baru' => '198001032000123003',
        'status_kepegwaian' => 'Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->subMonths(6)->format('Y-m-d'),
        'kab_kota' => 'Jakarta',
    ]);

    Pegawai::factory()->create([
        'nama' => 'Pegawai Non-Aktif',
        'nip_baru' => '198001042000124004',
        'status_kepegwaian' => 'Non-Aktif',
        'tgl_kgb_terakhir' => Carbon::now()->subYears(2)->format('Y-m-d'),
        'kab_kota' => 'Jakarta',
    ]);
});

test('kgb service shows all employees when monthsAhead is 0', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(0);

    // Should return all active pegawai with KGB dates (3 out of 4, excluding non-active)
    expect($result)->toHaveCount(3);

    // Verify the employee with KGB in 2 years is included
    $twoYearsKgb = $result->first(fn ($p) => $p->nama === 'Pegawai KGB 2 Tahun Lagi');
    expect($twoYearsKgb)->not->toBeNull()
        ->and($twoYearsKgb->days_until_kgb)->toBeGreaterThan(700); // ~2 years in days
});

test('kgb service filters by 6 months default', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(6);

    // Should include employees whose KGB is overdue OR within the next 6 months
    // "Pegawai KGB Bulan Ini" has KGB 5 days ago (overdue) - included
    // "Pegawai KGB 1.5 Tahun Lagi" will have KGB in 1.5 years - NOT included (beyond 6 months)
    // "Pegawai KGB 2 Tahun Lagi" will have KGB in 2 years - NOT included (beyond 6 months)
    expect($result)->toHaveCount(1);

    // Verify it's the overdue KGB employee
    expect($result->first()->nama)->toBe('Pegawai KGB Bulan Ini')
        ->and($result->first()->days_until_kgb)->toBeLessThan(0); // Negative means overdue
});

test('kgb service filters by 24 months (2 years)', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(24);

    // Should include all 3 active employees (all have KGB within 2 years)
    expect($result)->toHaveCount(3);
});

test('kgb service filters by kabupaten kota', function () {
    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(0, 'Jakarta');

    // Should only return employees from Jakarta (2 active, excluding non-active)
    expect($result)->toHaveCount(2);

    expect($result->pluck('kab_kota')->unique())->each->toBe('Jakarta');
});

test('kgb service does not mutate original date values', function () {
    $pegawai = Pegawai::where('nama', 'Pegawai KGB 2 Tahun Lagi')->first();
    $originalDate = $pegawai->tgl_kgb_terakhir->format('Y-m-d');

    $service = app(KgbService::class);
    $result = $service->getUpcomingKgb(0);

    // Refresh from database to ensure no mutation occurred
    $pegawai->refresh();
    $dbDate = $pegawai->tgl_kgb_terakhir->format('Y-m-d');

    expect($dbDate)->toBe($originalDate);

    // Verify the result has correct values
    $kgbData = $result->first(fn ($p) => $p->nama === 'Pegawai KGB 2 Tahun Lagi');
    expect($kgbData->tgl_kgb_terakhir->format('Y-m-d'))->toBe($originalDate)
        ->and($kgbData->next_kgb_date->format('Y-m-d'))->toBe(Carbon::parse($originalDate)->addYears(2)->format('Y-m-d'));
});

test('kgb service statistics returns correct counts', function () {
    $service = app(KgbService::class);
    $stats = $service->getStatistics(0);

    expect($stats['total'])->toBe(3)
        ->and($stats['sudah_lewat'])->toBe(1) // 'Pegawai KGB Bulan Ini' has KGB 5 days ago
        ->and($stats['bulan_ini'])->toBeGreaterThanOrEqual(0);
});
