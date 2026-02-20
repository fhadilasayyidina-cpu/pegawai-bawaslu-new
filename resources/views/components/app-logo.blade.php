@props([
    'sidebar' => false,
    'class' => '',
])

@php
    $appName = config('app_custom.name');
    $appLogo = config('app_custom.logo');
    $orgName = config('app_custom.organization.short_name');
@endphp

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }}>
        <x-slot name="logo" class="flex items-center gap-2">
            @if(file_exists(public_path($appLogo)))
                <img src="{{ asset($appLogo) }}" alt="{{ $orgName }}" class="h-14 w-auto object-contain" />
            @else
                <div class="flex aspect-square size-14 items-center justify-center rounded-md bg-primary text-primary-foreground">
                    <span class="text-sm font-bold">{{ mb_strtoupper(mb_substr($orgName, 0, 1)) }}</span>
                </div>
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$appName" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center gap-2">
            @if(file_exists(public_path($appLogo)))
                <img src="{{ asset($appLogo) }}" alt="{{ $orgName }}" class="max-h-12 w-auto" />
            @else
                <div class="flex aspect-square size-14 items-center justify-center rounded-md bg-primary text-primary-foreground">
                    <span class="text-sm font-bold">{{ mb_strtoupper(mb_substr($orgName, 0, 1)) }}</span>
                </div>
            @endif
        </x-slot>
    </flux:brand>
@endif
