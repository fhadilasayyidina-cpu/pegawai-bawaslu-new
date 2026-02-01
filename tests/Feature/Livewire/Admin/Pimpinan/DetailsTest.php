<?php

declare(strict_types=1);

use App\Models\Pimpinan;
use Livewire\Livewire;

beforeEach(function () {
    $this->pimpinan = Pimpinan::factory()->create();
});

it('renders details page and displays pimpinan data', function () {
    Livewire::test(\App\Livewire\Admin\Pimpinan\Details::class, ['id' => $this->pimpinan->id])
        ->assertSee('Detail Pimpinan')
        ->assertSee($this->pimpinan->nama)
        ->assertSee($this->pimpinan->kab_kota);
});

it('returns 404 for non-existent pimpinan id', function () {
    Livewire::test(\App\Livewire\Admin\Pimpinan\Details::class, ['id' => 99999])
        ->assertStatus(404);
});

it('displays jabatan correctly', function () {
    Livewire::test(\App\Livewire\Admin\Pimpinan\Details::class, ['id' => $this->pimpinan->id])
        ->assertSee($this->pimpinan->jabatan->value === 'ketua' ? 'Ketua' : 'Anggota');
});
