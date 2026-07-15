<?php
use App\Enums\Role;
use Illuminate\Support\Facades\Auth;

$user = Auth::user();

$map = [];

if ($user->role == Role::ADMIN) {
    $map = [
        [
            'label' => 'Platform',
            'items' => [
                [
                    'icon' => 'home',
                    'label' => 'Dashboard ',
                    'route' => 'admin/dashboard',
                ],
                [
                    'icon' => 'home',
                    'label' => 'Manajemen User',
                    'route' => 'admin/users',
                ],
                [
                    'icon' => 'home',
                    'label' => 'Data Pegawai',
                    'route' => 'admin/pegawais',
                ],
                [
                    'icon' => 'calendar',
                    'label' => 'Absensi',
                    'route' => 'admin/absensis',
                ],
                [
                    'icon' => 'user-group',
                    'label' => 'Data Pimpinan',
                    'route' => 'admin/pimpinans',
                ],
                [
                    'icon' => 'calendar',
                    'label' => 'KGB',
                    'route' => 'admin/kgbs',
                ],
                [
                    'icon' => 'calendar',
                    'label' => 'Hari Libur',
                    'route' => 'admin/hari-liburs',
                ],
            ],
        ],
    ];
} elseif ($user->role == Role::OPERATOR) {
    $map = [
        [
            'label' => 'Platform',
            'items' => [
                [
                    'icon' => 'home',
                    'label' => 'Dashboard',
                    'route' => 'operator/dashboard',
                ],
                [
                    'icon' => 'users',
                    'label' => 'Data Pegawai',
                    'route' => 'operator/pegawais',
                ],
                [
                    'icon' => 'user-group',
                    'label' => 'Data Pimpinan',
                    'route' => 'operator/pimpinans',
                ],
            ],
        ],
    ];
} elseif ($user->role == Role::PEGAWAI) {
    $map = [
        [
            'label' => 'Platform',
            'items' => [
                [
                    'icon' => 'home',
                    'label' => 'Dashboard',
                    'route' => 'pegawai/dashboard',
                ],
            ],
        ],
    ];
} else {
    abort(403);
}

// Tentukan dashboard URL berdasarkan role
$dashboardUrl = match ($user->role) {
    Role::ADMIN => '/admin/dashboard',
    Role::OPERATOR => '/operator/dashboard',
    Role::PEGAWAI => '/pegawai/dashboard',
    default => '/',
};

?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-200">
        <x-mary-toast />
        <flux:sidebar sticky collapsible="mobile"
            class="border-e border-slate-200/50 bg-slate-50 dark:border-slate-800/40 dark:bg-slate-900/90 backdrop-blur-md">
            <flux:sidebar.header class="flex items-center justify-between py-4">
                <x-app-logo :sidebar="true" href="{{ $dashboardUrl }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="px-2">
                @foreach ($map as $navGroup)
                    <flux:sidebar.group :heading="__($navGroup['label'])" class="grid gap-1 mt-4">
                        @foreach ($navGroup['items'] as $item)
                            @php
                                $isActive = request()->is($item['route']) || request()->is($item['route'] . '/*');
                            @endphp
                            <flux:sidebar.item :icon="$item['icon']" :href="url($item['route'])"
                                :current="$isActive" wire:navigate 
                                class="mb-1 transition-all duration-200 rounded-xl px-3 py-2 text-sm font-medium hover:bg-slate-200/40 dark:hover:bg-slate-800/40 {{ $isActive ? 'text-amber-500 dark:text-amber-400 font-semibold bg-slate-200/60 dark:bg-slate-800/60 shadow-xs' : 'text-slate-600 dark:text-slate-400' }}">
                                {{ __($item['label']) }}
                            </flux:sidebar.item>
                        @endforeach
                    </flux:sidebar.group>
                @endforeach
            </flux:sidebar.nav>


            {{-- <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                  
                    <flux:sidebar.item icon="home" :href="route('users')" :current="request()->routeIs('users')" wire:navigate>
                        {{ __('Manajemen User') }}
                    </flux:sidebar.item>
                   
                    
                </flux:sidebar.group>
                
            </flux:sidebar.nav> --}}

            <flux:spacer />



            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        <script src="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@2.9.6/build/vanilla-calendar.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@2.9.6/build/vanilla-calendar.min.css"
            rel="stylesheet">

    </body>

</html>
