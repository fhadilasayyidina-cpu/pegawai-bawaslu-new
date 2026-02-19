@php
use function Laravel\Folio\{middleware, name};

name('operator.pimpinans');
middleware(['auth', 'verified', 'role:operator']);
@endphp

<x-layouts.app title="Data Pimpinan">
    <livewire:operator.pimpinan.index />
</x-layouts.app>
