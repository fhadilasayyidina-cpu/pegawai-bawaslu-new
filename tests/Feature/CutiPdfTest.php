<?php

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\User;

test('pdf generation requires authentication', function () {
    $pegawai = Pegawai::factory()->create();
    $cuti = Cuti::factory()->for($pegawai)->create();

    $this->get(route('cuti.pdf', ['id' => $cuti->id]))
        ->assertRedirect(route('login'));
});

test('authenticated user can generate pdf', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create();
    $cuti = Cuti::factory()->for($pegawai)->create();

    $response = $this->actingAs($user)
        ->get(route('cuti.pdf', ['id' => $cuti->id]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('pdf returns 404 for non existent cuti', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('cuti.pdf', ['id' => 99999]))
        ->assertNotFound();
});

test('pdf contains cuti data', function () {
    $user = User::factory()->create();
    $pegawai = Pegawai::factory()->create([
        'nama' => 'Test Employee',
        'nip_baru' => '1234567890',
        'jabatan_nama' => 'Staff Admin',
    ]);
    $cuti = Cuti::factory()->for($pegawai)->create([
        'nomor_surat' => '001/2025/TEST',
        'jenis_cuti' => 'tahunan',
        'alasan' => 'Cuti tahunan untuk liburan keluarga',
        'lama_hari' => 5,
    ]);

    $response = $this->actingAs($user)
        ->get(route('cuti.pdf', ['id' => $cuti->id]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
