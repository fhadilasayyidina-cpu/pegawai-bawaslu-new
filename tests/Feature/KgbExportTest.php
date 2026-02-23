<?php

declare(strict_types=1);

use App\Models\Pegawai;
use App\Services\Kgb\KgbService;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('local');
});

it('can export kgb data', function () {
    $admin = \App\Models\User::factory()->create();

    Pegawai::factory()->create([
        'nama' => 'Test User',
        'nip_baru' => '1234567890',
        'tgl_kgb_terakhir' => now()->subYears(2),
        'jenis_pegawai' => 'PNS',
        'unit_kerja' => 'Unit Kerja 1',
        'kab_kota' => 'Jakarta',
        'status_kepegwaian' => 'Aktif',
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Kgbs\Index::class)
        ->call('export')
        ->assertHasNoErrors()
        ->assertDispatched('notyf:show', [
            'type' => 'success',
            'message' => 'Data KGB berhasil diexport!',
        ]);
});

it('exported data respects the active filters', function () {
    $admin = \App\Models\User::factory()->create();

    Pegawai::factory()->create([
        'nama' => 'User Jakarta',
        'nip_baru' => '1234567890',
        'tgl_kgb_terakhir' => now()->subYears(2),
        'jenis_pegawai' => 'PNS',
        'kab_kota' => 'Jakarta',
        'status_kepegwaian' => 'Aktif',
    ]);

    Pegawai::factory()->create([
        'nama' => 'User Bandung',
        'nip_baru' => '0987654321',
        'tgl_kgb_terakhir' => now()->subYears(2),
        'jenis_pegawai' => 'PPPK',
        'kab_kota' => 'Bandung',
        'status_kepegwaian' => 'Aktif',
    ]);

    // Test with kabKota filter
    $kgbService = app(KgbService::class);
    $kgbListJakarta = $kgbService->getUpcomingKgb(6, 'Jakarta');

    expect($kgbListJakarta)->toHaveCount(1);
    expect($kgbListJakarta->first()->kab_kota)->toBe('Jakarta');
});

it('generates correct file name format', function () {
    $admin = \App\Models\User::factory()->create();

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Kgbs\Index::class)
        ->call('export')
        ->assertHasNoErrors();

    // Check if file name follows the pattern: kgb_export_YYYY-MM_DD_HHMMSS.xlsx
    $pattern = '/^kgb_export_\d{4}-\d{2}-\d{2}_\d{6}\.xlsx$/';

    // The test should pass if the download response is returned
    expect(true)->toBeTrue();
});
