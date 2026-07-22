<?php

use App\Models\SalaryMatrix;
use App\Models\User;
use App\Services\Kgb\PnsSalaryTable;
use App\Services\Kgb\PppkSalaryTable;

test('imported salary matrix updates pns and pppk salary calculations', function () {
    // Create custom salary matrix records
    SalaryMatrix::create([
        'jenis_pegawai' => 'PNS',
        'golongan' => 'III/a',
        'mkg_tahun' => 0,
        'gaji_pokok' => 3500000,
    ]);

    SalaryMatrix::create([
        'jenis_pegawai' => 'PPPK',
        'golongan' => 'Golongan IX',
        'mkg_tahun' => 0,
        'gaji_pokok' => 4200000,
    ]);

    $pnsTable = app(PnsSalaryTable::class);
    $pppkTable = app(PppkSalaryTable::class);

    expect($pnsTable->salary('III/a', '0 Tahun 0 Bulan'))->toBe(3500000);
    expect($pppkTable->salary('Golongan IX', '0 Tahun 0 Bulan'))->toBe(4200000);
});

test('authenticated user can access kgb index page with import salary modal', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/kgbs');
    $response->assertStatus(200);
    $response->assertSee('Import Data Gaji');
});
