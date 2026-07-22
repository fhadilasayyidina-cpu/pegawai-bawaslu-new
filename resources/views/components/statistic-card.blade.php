@props([
    'title',
    'value',
    'desc' => '',
    'color' => 'primary'
])

@php
    $colorClasses = match ($color) {
        'primary' => [
            'text' => 'text-amber-600 dark:text-amber-400',
            'bg' => 'bg-amber-100/80 dark:bg-amber-950/40',
            'border' => 'border-amber-200 dark:border-amber-900/50'
        ],
        'success' => [
            'text' => 'text-emerald-600 dark:text-emerald-400',
            'bg' => 'bg-emerald-100/80 dark:bg-emerald-950/40',
            'border' => 'border-emerald-200 dark:border-emerald-900/50'
        ],
        'info' => [
            'text' => 'text-blue-600 dark:text-blue-400',
            'bg' => 'bg-blue-100/80 dark:bg-blue-950/40',
            'border' => 'border-blue-200 dark:border-blue-900/50'
        ],
        'warning' => [
            'text' => 'text-orange-600 dark:text-orange-400',
            'bg' => 'bg-orange-100/80 dark:bg-orange-950/40',
            'border' => 'border-orange-200 dark:border-orange-900/50'
        ],
        'secondary' => [
            'text' => 'text-indigo-600 dark:text-indigo-400',
            'bg' => 'bg-indigo-100/80 dark:bg-indigo-950/40',
            'border' => 'border-indigo-200 dark:border-indigo-900/50'
        ],
        default => [
            'text' => 'text-zinc-600 dark:text-zinc-400',
            'bg' => 'bg-zinc-100/80 dark:bg-zinc-800/40',
            'border' => 'border-zinc-200 dark:border-zinc-800'
        ]
    };
@endphp

<div {{ $attributes->merge(['class' => "statistic-card hover-premium premium-shadow p-6 rounded-2xl bg-white dark:bg-slate-900/70 border " . $colorClasses['border'] . " flex flex-col justify-between relative overflow-hidden group"]) }}>
    <!-- Sparkle glow gradient effect inside the card on hover -->
    <div class="absolute -right-16 -top-16 w-32 h-32 rounded-full blur-2xl opacity-10 group-hover:opacity-20 transition-opacity duration-300 {{ $colorClasses['bg'] }}"></div>
    
    <div class="flex items-center justify-between gap-4">
        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $title }}</span>
        
        <div class="flex items-center justify-center p-2.5 rounded-xl {{ $colorClasses['bg'] }} {{ $colorClasses['text'] }} shrink-0">
            @if($slot->isEmpty())
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
    
    <div class="mt-4 z-10">
        <h3 class="statistic-value text-3xl font-black tracking-tight text-slate-900 dark:text-slate-50" data-stat-value="{{ $value }}">
            {{ $value }}
        </h3>
        
        @if(!empty($desc))
            <p class="text-xs font-medium text-slate-400 dark:text-slate-400 mt-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full {{ $colorClasses['bg'] }} inline-block"></span>
                {{ $desc }}
            </p>
        @endif
    </div>
</div>

{{-- Contoh --}}
{{-- <div class="stats shadow bg-base-100 border-primary">
    <div class="stat">
        <div class="stat-figure text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div class="stat-title">Total Pegawai</div>
        <div class="stat-value text-primary">12</div>
        <div class="stat-desc">aka</div>
    </div>
</div> --}}
