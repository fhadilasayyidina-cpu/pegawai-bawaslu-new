@php
use function Laravel\Folio\{middleware, name};

name('operator.pegawais.details');
middleware(['auth', 'verified', 'role:operator']);
@endphp

<x-layouts.app title="Data Detail Pegawai">
    <livewire:operator.pegawai.details :id="$id" />
</x-layouts.app>
