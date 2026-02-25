@php
use function Laravel\Folio\{middleware, name};

name('operator.dashboard');
middleware(['auth', 'verified', 'role:operator']);
@endphp

<x-layouts.app title="Dashboard Operator">
    <livewire:operator.dashboard />
</x-layouts.app>
