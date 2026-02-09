@php
use function Laravel\Folio\{middleware, name};

name('admin.kgbs');
middleware(['auth', 'verified']);
@endphp

<x-layouts.app title="Kenaikan Gaji Berkala">
    <livewire:admin.kgbs.index />
</x-layouts.app>
