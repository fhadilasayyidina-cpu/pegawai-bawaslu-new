<div>
    <x-header-page title="Detail Pegawai">

    </x-header-page>
    <x-mary-tabs wire:model="selectedTab" selected="users-tab">
        <x-mary-tab name="users-tab" label="Users" icon="o-users">
            <x-mary-form wire:submit="save">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Perhatikan wire:model-nya --}}
                    <x-mary-input label="Nama" wire:model="identitasForm.nama" />
                    <x-mary-input label="NIP" wire:model="identitasForm.nip_baru" readonly />
                    <x-mary-input label="NIK" wire:model="identitasForm.nik" />
                    <x-mary-input label="Email" wire:model="identitasForm.email" />
                </div>

                <x-slot:actions>
                    <x-mary-button label="Simpan" type="submit" class="btn-primary" spinner="save" />
                </x-slot:actions>
            </x-mary-form>
        </x-mary-tab>
        <x-mary-tab name="tricks-tab" label="Tricks" icon="o-sparkles">
            <div>Tricks</div>
        </x-mary-tab>
        <x-mary-tab name="musics-tab" label="Musics" icon="o-musical-note">
            <div>Musics</div>
        </x-mary-tab>
    </x-mary-tabs>

</div>
