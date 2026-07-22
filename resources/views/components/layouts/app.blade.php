<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="app-main-content">
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
