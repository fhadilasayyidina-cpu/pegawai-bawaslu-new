<?php

use App\Models\HariLibur;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('hari libur page can be rendered', function () {
    $user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);

    $response = $this->actingAs($user)->get('/admin/hari-liburs');

    $response->assertStatus(200);
});

test('admin can create hari libur', function () {
    $user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Admin\HariLibur\Index::class)
        ->set('date', '2026-12-25')
        ->set('description', 'Hari Raya Natal')
        ->call('save')
        ->assertHasNoErrors();

    expect(HariLibur::where('date', '2026-12-25')->exists())->toBeTrue();
});

test('hari libur date must be unique', function () {
    $user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);

    HariLibur::factory()->create([
        'date' => '2026-12-25',
        'description' => 'Hari Raya Natal',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Admin\HariLibur\Index::class)
        ->set('date', '2026-12-25')
        ->set('description', 'Libur Lain')
        ->call('save')
        ->assertHasErrors(['date' => 'unique']);
});

test('admin can delete hari libur', function () {
    $user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);

    $hariLibur = HariLibur::factory()->create([
        'date' => '2026-12-25',
        'description' => 'Hari Raya Natal',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Admin\HariLibur\Index::class)
        ->call('delete', $hariLibur->id)
        ->assertHasNoErrors();

    expect(HariLibur::find($hariLibur->id))->toBeNull();
});

test('hari libur has correct fillable fields', function () {
    $hariLibur = HariLibur::factory()->create([
        'date' => '2026-12-25',
        'description' => 'Hari Raya Natal',
        'is_imported' => false,
    ]);

    expect($hariLibur->date->format('Y-m-d'))->toBe('2026-12-25')
        ->and($hariLibur->description)->toBe('Hari Raya Natal')
        ->and($hariLibur->is_imported)->toBeFalse();
});

test('admin can update hari libur', function () {
    $user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);
    $hariLibur = HariLibur::factory()->create([
        'date' => '2026-12-25',
        'description' => 'Hari Raya Natal',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Admin\HariLibur\Index::class)
        ->call('openEditModal', $hariLibur->id)
        ->set('date', '2026-12-25')
        ->set('description', 'Hari Raya Natal Updated')
        ->call('update')
        ->assertHasNoErrors();

    expect(HariLibur::find($hariLibur->id)->description)->toBe('Hari Raya Natal Updated');
});

test('admin can import hari libur from storage', function () {
    $user = User::factory()->create(['role' => \App\Enums\Role::ADMIN]);

    Storage::fake('local');

    $data = [
        [
            'tanggal' => '2026-08-17',
            'keterangan' => 'Hari Kemerdekaan RI',
        ],
    ];

    Storage::disk('local')->put('DataLibur/2026.json', json_encode($data));

    Livewire::actingAs($user)
        ->test(\App\Livewire\Admin\HariLibur\Index::class)
        ->set('importYear', '2026')
        ->call('importFromStorage')
        ->assertHasNoErrors();

    expect(HariLibur::where('date', '2026-08-17')->exists())->toBeTrue();
});
