@php
use function Laravel\Folio\{middleware, name};

name('admin.dashboard');
middleware(['auth', 'verified']);
@endphp

<x-layouts.app title="Dashboard">
    <livewire:admin.dashboard />
</x-layouts.app>
