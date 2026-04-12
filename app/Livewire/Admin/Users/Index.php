<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;

class Index extends Component
{
    public ?string $search = '';
    public array $tableHeaders = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'name', 'label' => 'Nama User'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'role', 'label' => 'Role'],
        ['key' => 'access_scope', 'label' => 'Akses Wilayah'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'User Management', 'link' => '#'],
    ];

    public function users()
    {
        return app(\App\Services\User\UserService::class)->getAllUser(
            nameOrEmail: $this->search
        );
    }

    public function delete($id)
    {
        app(\App\Services\User\UserService::class)->deleteUser($id);
        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'User berhasil dihapus!'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => $this->users()
        ]);
    }
}
