@php
use function Laravel\Folio\{middleware, name};

name('operator.pimpinans.details');
middleware(['auth', 'verified', 'role:operator']);
@endphp

<x-layouts.app title="Detail Pimpinan">
    <livewire:operator.pimpinan.details :id="$id" />
</x-layouts.app>
