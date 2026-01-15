<?php 
use function Laravel\Folio\middleware;
middleware(['auth', 'verified', 'role:admin']);
?>

<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        {{-- Grid Placeholder tetap di luar Volt tidak apa-apa --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
             <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            {{-- ... placeholder lainnya --}}
        </div>

        {{-- BAGIAN TABLE HARUS DI DALAM VOLT --}}
        @volt
        @php
            $users = App\Models\User::all();
        
            $headers = [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Nice Name'],
                
            ];
        @endphp
        
        {{-- You can use any `$wire.METHOD` on `@row-click` --}}
        <x-mary-table :headers="$headers" :rows="$users" striped @row-click="alert($event.detail.name)" />
        @endvolt
    </div>
</x-layouts.app>