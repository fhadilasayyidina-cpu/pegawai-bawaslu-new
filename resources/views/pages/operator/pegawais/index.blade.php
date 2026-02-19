@php
use function Laravel\Folio\{middleware, name};

name('operator.pegawais');
middleware(['auth', 'verified', 'role:operator']);
@endphp

<x-layouts.app title="Data Pegawai">
    <livewire:operator.pegawai.index />
</x-layouts.app>
