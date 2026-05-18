@php
use function Laravel\Folio\{middleware, name};

name('admin.users.edit');
middleware(['auth', 'verified']);
@endphp

<x-layouts.app title="Edit User">
    <livewire:admin.edit-user :userId="$user->id" />
</x-layouts.app>
