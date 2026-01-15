@props([
    'title',
    'value',
    'desc' => '',
    'color' => 'primary'
])

<div {{ $attributes->merge(['class' => "stats shadow bg-base-100 border-l-4 border-$color"]) }}>
    <div class="stat">
        <div class="stat-figure text-{{ $color }}">
            {{-- Jika kamu tidak isi apapun di antara tag komponen, SVG default ini yang muncul --}}
            @if($slot->isEmpty())
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            @else
                {{-- Jika kamu isi sesuatu (SVG lain), maka yang muncul adalah isi tersebut --}}
                {{ $slot }}
            @endif
        </div>
        <div class="stat-title">{{ $title }}</div>
        <div class="stat-value text-{{ $color }}">{{ $value }}</div>
        <div class="stat-desc">{{ $desc }}</div>
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