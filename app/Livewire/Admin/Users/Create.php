<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'operator';
    public ?string $access_scope = null;

    public array $kabKotaOptions = [];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Manajemen User', 'link' => '/admin/users'],
        ['label' => 'Tambah User', 'link' => '#'],
    ];

    public function mount()
    {
        $this->kabKotaOptions = app(\App\Services\Pegawai\PegawaiService::class)->getKabKota()->toArray();
    }

    public function save()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:operator,pegawai'],
        ];

        // access_scope required only for operator
        if ($this->role === 'operator') {
            $rules['access_scope'] = ['required', 'string'];
        } else {
            $rules['access_scope'] = ['nullable', 'string'];
        }

        $validated = $this->validate($rules);

        app(\App\Services\User\UserService::class)->createUser($validated);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'User baru berhasil ditambahkan.'
        ]);

        return $this->redirect('/admin/users');
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
