<?php
use function Livewire\Volt\{state};
// Logika PHP kamu di sini
state(['count' => 0]);
?>

@volt
<div>
    <h1>Testing Folio + Volt</h1>
    <p>Skor: {{ $count }}</p>
    <button wire:click="$inc('count')" class="bg-blue-500 text-white p-2">
        Tambah
    </button>
</div>
@endvolt