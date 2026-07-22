@props([
    'sidebar' => false,
    'class' => '',
    'href' => null,
])

@php
    $appName = config('app_custom.name');
    $appLogo = config('app_custom.logo');
    $orgName = config('app_custom.organization.short_name');
@endphp

@if($sidebar)
    <a href="{{ $href ?? '#' }}" wire:navigate class="brand-lockup" aria-label="{{ $appName }}">
        @if(file_exists(public_path($appLogo)))
            <img src="{{ asset($appLogo) }}" alt="{{ $orgName }}" class="brand-lockup__logo" />
        @else
            <div class="flex aspect-square size-12 items-center justify-center rounded-xl bg-brand-gold-500 text-brand-navy-950">
                <span class="text-sm font-bold">{{ mb_strtoupper(mb_substr($orgName, 0, 1)) }}</span>
            </div>
            <span class="brand-lockup__fallback">{{ $orgName }}</span>
        @endif
    </a>
@else
    <flux:brand :name="$appName" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center gap-2">
            @if(file_exists(public_path($appLogo)))
                <img src="{{ asset($appLogo) }}" alt="{{ $orgName }}" class="max-h-12 w-auto" />
            @else
            <div class="flex aspect-square size-14 items-center justify-center rounded-xl bg-brand-gold-500 text-brand-navy-950">
                    <span class="text-sm font-bold">{{ mb_strtoupper(mb_substr($orgName, 0, 1)) }}</span>
                </div>
            @endif
        </x-slot>
    </flux:brand>
@endif
