<?php

use App\Livewire\Admin\Kgbs\CreatePns;
use App\Livewire\Admin\Kgbs\CreatePppk;
use App\Models\KgbRecord;
use App\Models\Pegawai;
use App\Models\User;

test('kgb pns form requires authentication', function () {
    $this->get('/admin/kgbs/create-pns')
        ->assertRedirect('/login');
});

test('kgb pppk form requires authentication', function () {
    $this->get('/admin/kgbs/create-pppk')
        ->assertRedirect('/login');
});

test('authenticated user can view kgb pns form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/kgbs/create-pns');
    $response->assertStatus(200);
});

test('authenticated user can view kgb pppk form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/kgbs/create-pppk');
    $response->assertStatus(200);
});

test('authenticated user can view kgb index page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/kgbs');
    $response->assertStatus(200);
});

test('submitting pns form updates pegawai and redirects to pdf', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create([
        'jenis_pegawai' => 'PNS',
        'nama' => 'PNS Test User',
        'tgl_kgb_terakhir' => '2024-01-01',
    ]);

    $this->actingAs($user);

    Livewire::test(CreatePns::class)
        ->set('pegawai_id', $pegawai->id)
        ->set('ibu_kota_provinsi', 'Makassar')
        ->set('sk_pejabat', 'Kepala Sekretariat')
        ->set('sk_tanggal', '2024-01-01')
        ->set('sk_nomor', 'SK-123')
        ->set('sk_tmt', '2024-01-01')
        ->set('sk_mkg_tahun', 10)
        ->set('sk_mkg_bulan', 0)
        ->set('gaji_pokok_lama', 'Rp. 3.000.000,-')
        ->set('gaji_pokok_baru', 'Rp. 3.200.000,-')
        ->set('masa_kerja_baru', '12 Tahun 0 Bulan')
        ->set('golongan_ruang_baru', 'III/b')
        ->set('nama_kasek', 'Awaluddin')
        ->call('save')
        ->assertRedirect();

    $pegawai->refresh();
    expect($pegawai->tgl_kgb_terakhir->format('Y-m-d'))->toBe('2026-01-01');
    expect(KgbRecord::query()->where('pegawai_id', $pegawai->id)->exists())->toBeTrue();
});

test('submitting pppk form updates pegawai and redirects to pdf', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create([
        'jenis_pegawai' => 'PPPK',
        'nama' => 'PPPK Test User',
        'tgl_kgb_terakhir' => '2024-01-01',
    ]);

    $this->actingAs($user);

    Livewire::test(CreatePppk::class)
        ->set('pegawai_id', $pegawai->id)
        ->set('nomor_naskah', '002/PPPK-KGB/2026')
        ->set('tanggal_naskah', '2026-07-16')
        ->set('ibu_kota_provinsi', 'Makassar')
        ->set('ni_pppk', '123456')
        ->set('jabatan_golongan', 'Ahli Pertama')
        ->set('masa_perjanjian_kerja', '5 Tahun')
        ->set('perpanjangan_perjanjian_kerja', '-')
        ->set('unit_kerja', 'Bawaslu Sulsel')
        ->set('gaji_lama', 'Rp. 3.000.000,-')
        ->set('sk_pejabat', 'Sekretaris Jenderal')
        ->set('sk_tanggal', '2024-01-01')
        ->set('sk_nomor', 'SK-456')
        ->set('sk_tmt', '2024-01-01')
        ->set('sk_mkg_tahun', 2)
        ->set('sk_mkg_bulan', 0)
        ->set('gaji_baru', 'Rp. 3.200.000,-')
        ->set('masa_kerja_baru', '4 Tahun 0 Bulan')
        ->set('tmt_baru', '2026-01-01')
        ->set('nama_kasek', 'Awaluddin')
        ->assertHasNoErrors()
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $pegawai->refresh();
    expect($pegawai->tgl_kgb_terakhir->format('Y-m-d'))->toBe('2026-01-01');
});

test('pns pdf route generates pdf', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.kgbs.pns-pdf', [
        'pegawai_id' => $pegawai->id,
        'nomor_naskah' => '001/PNS-KGB/2026',
        'tanggal_naskah' => '2026-07-16',
        'ibu_kota_provinsi' => 'Makassar',
        'sk_pejabat' => 'Kepala Sekretariat',
        'sk_tanggal' => '2024-01-01',
        'sk_nomor' => 'SK-123',
        'sk_tmt' => '2024-01-01',
        'sk_mkg' => '10 Tahun 0 Bulan',
        'gaji_pokok_lama' => 'Rp. 3.000.000,-',
        'gaji_pokok_baru' => 'Rp. 3.200.000,-',
        'masa_kerja_baru' => '12 Tahun 0 Bulan',
        'golongan_ruang_baru' => 'III/b',
        'tmt_baru' => '2026-01-01',
        'next_kgb_date' => '2028-01-01',
        'nama_kasek' => 'Awaluddin',
    ]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('pppk pdf route generates pdf', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.kgbs.pppk-pdf', [
        'pegawai_id' => $pegawai->id,
        'nomor_naskah' => '002/PPPK-KGB/2026',
        'tanggal_naskah' => '2026-07-16',
        'ibu_kota_provinsi' => 'Makassar',
        'ni_pppk' => '123456',
        'jabatan_golongan' => 'Ahli Pertama',
        'masa_perjanjian_kerja' => '5 Tahun',
        'perpanjangan_perjanjian_kerja' => '-',
        'unit_kerja' => 'Bawaslu Sulsel',
        'gaji_lama' => 'Rp. 3.000.000,-',
        'sk_pejabat' => 'Sekretaris Jenderal',
        'sk_tanggal' => '2024-01-01',
        'sk_nomor' => 'SK-456',
        'sk_tmt' => '2024-01-01',
        'sk_mkg' => '2 Tahun 0 Bulan',
        'gaji_baru' => 'Rp. 3.200.000,-',
        'masa_kerja_baru' => '4 Tahun 0 Bulan',
        'tmt_baru' => '2026-01-01',
        'nama_kasek' => 'Awaluddin',
    ]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('authenticated user can delete kgb record', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create();
    $kgb = KgbRecord::create([
        'pegawai_id' => $pegawai->id,
        'created_by' => $user->id,
        'jenis_kgb' => 'PNS',
        'nomor_naskah' => '001/PNS-KGB/2026',
        'tanggal_naskah' => '2026-07-16',
        'tmt_baru' => '2026-01-01',
        'next_kgb_date' => '2028-01-01',
        'data' => [],
    ]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Admin\Kgbs\Index::class)
        ->call('delete', $kgb->id)
        ->assertDispatched('notyf:show');

    expect(KgbRecord::find($kgb->id))->toBeNull();
});
