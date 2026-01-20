<?php

namespace App\Livewire\Admin\Pegawai;

use App\Livewire\Forms\Pegawai\IdentitasForm;
use App\Models\Pegawai;
use Livewire\Component;

class Details extends Component
{
    public Pegawai $pegawai;

    public IdentitasForm $identitasForm;

    public string $selectedTab = 'users-tab';

    // Harus terima $id dari Folio
    public function mount($id)
    {
        $pegawai = \App\Models\Pegawai::where('id', (int) $id)->first();
        if (! $pegawai) {
            // Jika ini muncul, berarti variabel $id yang ditangkap Livewire
            // berbeda dengan yang kamu ketik di URL
            dd('Livewire menangkap ID: '.$id);
        }
        // Masukkan ke Form Object agar inputan terisi
        $this->pegawai = $pegawai;
        $this->identitasForm->setPegawai($this->pegawai);

    }

    public function save()
    {
        $this->identitasForm->validate();
        $this->pegawai->update($this->identitasForm->all());

        $this->success('Data berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.admin.pegawai.details');
    }
}
