<?php

declare(strict_types=1);

use App\Livewire\Admin\Pegawai\Index;
use App\Models\Pegawai;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);
    $this->actingAs($this->user);
});

it('renders the component successfully', function () {
    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.admin.pegawai.index');
});

it('filters pegawais by search name', function () {
    $pegawai1 = Pegawai::factory()->create(['nama' => 'Budi Sudarsono']);
    $pegawai2 = Pegawai::factory()->create(['nama' => 'Ali Sadikin']);

    Livewire::test(Index::class)
        ->assertSee($pegawai1->nama)
        ->assertSee($pegawai2->nama)
        ->set('search', 'Budi')
        ->assertSee($pegawai1->nama)
        ->assertDontSee($pegawai2->nama);
});

it('resets page when search is updated', function () {
    Pegawai::factory()->count(15)->create(); // Create 15 pegawais to have 2 pages (10 per page)

    Livewire::test(Index::class)
        ->set('paginators.page', 2)
        ->set('search', 'Budi')
        ->assertSet('paginators.page', 1);
});

it('can navigate to next page', function () {
    Pegawai::factory()->count(15)->create();

    Livewire::test(Index::class)
        ->assertSet('paginators.page', 1)
        ->call('gotoPage', 2)
        ->assertSet('paginators.page', 2);
});
