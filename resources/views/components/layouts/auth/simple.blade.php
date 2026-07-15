<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-slate-950 text-white antialiased">
        @php
            $appName = config('app_custom.name');
            $orgName = config('app_custom.organization.short_name');
            $appLogo = config('app_custom.logo');
        @endphp

        <div class="aurora-bg flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md glass-card rounded-2xl shadow-2xl p-8 md:p-10 relative z-10 border border-white/10">
                <div class="flex flex-col gap-6">
                    <a href="#" class="flex flex-col items-center gap-2 mb-2" wire:navigate>
                        <span class="flex h-16 items-center justify-center">
                            @if (file_exists(public_path($appLogo)))
                                <img src="{{ asset($appLogo) }}" alt="{{ $orgName }}"
                                    class="h-16 w-auto object-contain drop-shadow-[0_4px_12px_rgba(245,158,11,0.25)]" />
                            @else
                                <x-app-logo-icon class="size-16 fill-current text-white" />
                            @endif
                        </span>
                        <span class="text-xs font-bold tracking-widest uppercase text-brand-gold-400 mt-2">
                            {{ $orgName }}
                        </span>
                    </a>
                    <div class="flex flex-col gap-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>

</html>
