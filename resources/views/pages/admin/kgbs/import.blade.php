@php
use function Laravel\Folio\{middleware, name};

name('admin.kgbs.import');
middleware(['auth', 'verified']);
@endphp

<x-layouts.app title="Import Data KGB">
    <livewire:admin.kgbs.import />
</x-layouts.app>
