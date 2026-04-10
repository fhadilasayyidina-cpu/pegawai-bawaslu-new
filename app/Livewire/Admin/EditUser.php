<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class EditUser extends Component
{
    public int $userId;

    public User $user;

    public string $name = '';

    public string $email = '';

    public ?string $password = '';

    public ?string $password_confirmation = '';

    public string $role = 'operator';

    public ?string $access_scope = null;

    public array $kabKotaOptions = [];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Manajemen User', 'link' => '/admin/users'],
        ['label' => 'Edit User', 'link' => '#'],
    ];

    public function mount(int $userId): void
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->role = $this->user->role->value;
        $this->access_scope = $this->user->access_scope;
        $this->kabKotaOptions = app(\App\Services\Pegawai\PegawaiService::class)->getKabKota()->toArray();
    }

    public function update(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$this->user->id],
            'role' => ['required', 'in:admin,operator,pegawai'],
        ];

        // access_scope required only for operator
        if ($this->role === 'operator') {
            $rules['access_scope'] = ['required', 'string'];
        } else {
            $rules['access_scope'] = ['nullable', 'string'];
        }

        // Only validate password if provided
        if (! empty($this->password)) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $this->validate($rules);

        app(\App\Services\User\UserService::class)->updateUser($this->user, $validated);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data user berhasil diperbarui.',
        ]);

        $this->redirect('/admin/users', navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.edit-user');
    }
}
