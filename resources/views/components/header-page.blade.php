@props([
    'title' => '',
    'breadcrumbs' => []
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    {{-- 1. Render Breadcrumbs --}}
    @if(!empty($breadcrumbs))
       <x-mary-breadcrumbs :items="$breadcrumbs" />
    @endif

    {{-- 2. Baris Judul & Tombol Aksi --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-3">
        <div>
            <flux:heading size="xl" level="1" class="font-bold tracking-tight">
                {{ $title }}
            </flux:heading>
        </div>

        {{-- Slot untuk Tombol Aksi di ujung kanan --}}
        @if(isset($actions))
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>

    {{-- Garis pemisah opsional untuk estetika --}}
    <flux:separator variant="subtle" class="mt-4" />
</div>