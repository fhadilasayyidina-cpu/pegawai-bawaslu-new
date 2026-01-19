<?php
use App\Services\User\UserService;
use Livewire\WithPagination;   
use Livewire\Volt\Component;


?>

<x-layouts.app :title="__('Manajemen User')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    @php
        $breadcrumbs = [
            ['label' => 'Home', 'link' => '#' ],
            ['label' => 'Home', 'link' => '#' ],
            ['label' => 'Home', 'link' => '#' ],
            
        ];
    @endphp

            
    {{-- Memanggil Komponen Baru --}}
    <x-header-page 
        :title="$title ?? 'Tambah Dokumen'" 
        :breadcrumbs="$breadcrumbs"
    >
        {{-- Slot untuk Tombol Aksi --}}
        <x-slot:actions>
            <x-mary-button label="Kembali"  />
           
            <x-mary-button label="Tambah" @wire:click=""  class="btn-primary" />
            
        </x-slot:actions>
    </x-header-page>

           
  
        {{-- Statistic Wrapper --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-4 mb-5">
            {{-- Statistic Card --}}
            <x-statistic-card title="Total Pengguna" value="5" color="primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </x-statistic-card>
            <x-statistic-card title="Total Admin" value="5" color="primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </x-statistic-card>
            <x-statistic-card title="Total Operator" value="5" color="primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </x-statistic-card>


        </div>

        {{-- Searching and Filter --}}
        {{-- <div class="relative h-20 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        </div> --}}


        @volt
            <?php
            
            new class extends Component {
                use WithPagination;
                
                public ?string $nameOrEmail = null;
                public ?string $role = null;
                public ?string $accessScope = null;
                public function with(UserService $userService)
                {
                    return [
                        'users' => $userService->getAllUser($this->nameOrEmail, $this->role,$this->accessScope),
                    ];
                }

                public function resetFilters()
                {
                    $this->reset(['nameOrEmail', 'role', 'accessScope']);
                }
                  public function save()
                {
                    // 1. Logika simpan kamu di sini
                    // misal: User::create([...]);

                    // 2. Kirim sinyal (Dispatch) ke Notyf yang ada di Layout
                    $this->dispatch('notyf:show', 
                        type: 'success', 
                        message: 'User baru berhasil ditambahkan!'
                    );
                }
            };
            
            ?>

            
            <div>
                 <x-mary-button label="Tambah" wire:click="save"  class="btn-primary" />
                <!-- Form Filter -->
                <x-mary-form wire:submit="search" no-separator class="mb-5">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        {{-- Input Search --}}
                        <div class="md:col-span-2">
                            <x-mary-input label="Cari Pengguna" wire:model.live="nameOrEmail" placeholder="Nama atau email..."
                                icon="o-magnifying-glass" />
                        </div>

                        {{-- Filter Role (Contoh) --}}
                        <x-mary-select label="Role" wire:model.live="role" :options="[['id' => 'admin', 'name' => 'Admin'], ['id' => 'operator', 'name' => 'Operator']]" placeholder="Semua" />

                        <x-mary-select 
                            label="Wilayah Akses" 
                            wire:model.live="accessScope" 
                            :options="[['id' => 'pusat', 'name' => 'Pusat'], ['id' => 'daerah', 'name' => 'Daerah']]" 
                            placeholder="Semua Wilayah" 
                        />
                        {{-- Tombol (Aksi) --}}
                        <div class="flex gap-2">
                           
                        <x-mary-button 
                            label="Reset" 
                            icon="o-arrow-path" 
                            wire:click="resetFilters" 
                            type="button" 
                            class="btn-primary " 
                            
                        />
                            {{-- Tombol Reset jika butuh --}}

                        </div>
                    </div>
                </x-mary-form>

            
                {{-- Table Data --}}
                <div
                    class="relative  flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

                    @php
                       

                        $headers = [
                            ['key' => 'id', 'label' => '#'],
                            ['key' => 'name', 'label' => 'Nama'],
                            ['key' => 'email', 'label' => 'Alamat Email'],
                            ['key' => 'role', 'label' => 'Role'],
                            ['key' => 'access_scope', 'label' => 'Wilayah Akses'],
                            ['key' => 'created_at', 'label' => 'Dibuat Pada'],

                            # <---- nested attributes
                        ];
                    @endphp

                    {{-- You can use any `$wire.METHOD` on `@row-click` --}}
                    <x-mary-table :headers="$headers" :rows="$users" striped @row-click="alert($event.detail.name)" with-pagination />

                </div>

            </div>
        @endvolt




    </div>
</x-layouts.app>

<!-- {{-- dibawah ini gak hanya jadi acuan contoh jangan dihapus --}} -->
<!-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>  -->
