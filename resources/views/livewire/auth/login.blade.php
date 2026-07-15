<x-layouts.auth>
    <div class="flex flex-col gap-5">
        <x-auth-header title="Sign In" description="Silakan masuk untuk mengakses Sistem Kepegawaian" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center text-sm font-medium text-emerald-400" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <flux:input name="email" label="Alamat Email" :value="old('email')" type="email" required autofocus
                autocomplete="email" placeholder="nama@email.com" class="dark:bg-slate-900/50 dark:border-white/10" />

            <!-- Password -->
            <div class="relative">
                <flux:input name="password" label="Password" type="password" required autocomplete="current-password"
                    placeholder="Password Anda" viewable class="dark:bg-slate-900/50 dark:border-white/10" />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-xs text-brand-gold-400 hover:text-brand-gold-300 transition-colors end-0" :href="route('password.request')" wire:navigate>
                        Lupa Password?
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mt-1">
                <flux:checkbox name="remember" label="Ingat Saya" :checked="old('remember')" class="text-xs text-zinc-400" />
            </div>

            <div class="flex items-center justify-end mt-2">
                <flux:button variant="primary" type="submit" class="w-full bg-brand-gold-500 hover:bg-brand-gold-600 border-none text-slate-950 font-bold transition-all shadow-lg hover:shadow-brand-gold-500/20 cursor-pointer" data-test="login-button">
                    Masuk ke Sistem
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.auth>
