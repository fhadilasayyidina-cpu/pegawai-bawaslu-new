<?php

declare(strict_types=1);

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('shows birthday reminder on dashboard when an employee has a birthday today', function () {
    $today = Carbon::today();

    Pegawai::factory()->create([
        'nama' => 'Budi Santoso',
        'tgl_lahir' => Carbon::create(1990, $today->month, $today->day),
    ]);

    Livewire::test(\App\Livewire\Admin\Dashboard::class)
        ->assertSee('Ulang Tahun')
        ->assertSee('Budi Santoso');
});

it('does not show birthday reminder on dashboard when no employee has a birthday today', function () {
    $tomorrow = Carbon::tomorrow();

    Pegawai::factory()->create([
        'tgl_lahir' => Carbon::create(1990, $tomorrow->month, $tomorrow->day),
    ]);

    Livewire::test(\App\Livewire\Admin\Dashboard::class)
        ->assertDontSee('Ulang Tahun');
});

it('shows birthday reminder on pegawai index when an employee has a birthday today', function () {
    $today = Carbon::today();

    Pegawai::factory()->create([
        'nama' => 'Siti Aminah',
        'tgl_lahir' => Carbon::create(1985, $today->month, $today->day),
    ]);

    Livewire::test(\App\Livewire\Admin\Pegawai\Index::class)
        ->assertSee('Selamat Ulang Tahun Hari Ini')
        ->assertSee('Siti Aminah');
});

it('does not show birthday reminder on pegawai index when no employee has a birthday today', function () {
    $tomorrow = Carbon::tomorrow();

    Pegawai::factory()->create([
        'tgl_lahir' => Carbon::create(1985, $tomorrow->month, $tomorrow->day),
    ]);

    Livewire::test(\App\Livewire\Admin\Pegawai\Index::class)
        ->assertDontSee('Selamat Ulang Tahun Hari Ini');
});
