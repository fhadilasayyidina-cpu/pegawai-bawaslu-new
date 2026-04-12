<div>
    {{-- Header Page (Anonymous Component) --}}
    <x-header-page title="Manajemen Akses User" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <x-mary-button label="Tambah Baru" icon="o-plus" link="/admin/users/create" class="btn-primary" />
        </x-slot:actions>
    </x-header-page>

    {{-- Filter Section --}}
    <div class="my-4 bg-base-200 p-4 rounded-lg">
        <x-mary-input 
            wire:model.live.debounce.300ms="search" 
            placeholder="Cari berdasarkan nama atau email..." 
            icon="o-magnifying-glass" 
        />
    </div>

    {{-- Tabel Utama --}}
    <x-mary-table
        :headers="$tableHeaders"
        :rows="$users"
        striped
        with-pagination
        per-page="10"
    >
        {{-- Slot untuk aksi per baris --}}
        @scope('actions', $user)
            <x-mary-button icon="o-pencil" link="/admin/users/{{ $user->id }}/edit" class="btn-ghost btn-sm" />
            <x-mary-button icon="o-trash" wire:click="delete({{ $user->id }})" class="btn-ghost text-error btn-sm" wire:confirm="Yakin mau hapus?" />
        @endscope
    </x-mary-table>
</div>
