<?php

namespace App\Livewire\Admin\Pegawai;

use App\Livewire\Forms\Pegawai\AdministrasiForm;
use App\Livewire\Forms\Pegawai\IdentitasForm;
use App\Livewire\Forms\Pegawai\JabatanForm;
use App\Models\Pegawai;
use Livewire\Component;

class Details extends Component
{
    public Pegawai $pegawai;

    public IdentitasForm $identitasForm;

    public JabatanForm $jabatanForm;

    public AdministrasiForm $administrasiForm;

    public string $selectedTab = 'identitas-tab';

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
        $this->jabatanForm->setPegawai($this->pegawai);
        $this->administrasiForm->setPegawai($this->pegawai);

    }

    public function saveIdentitas()
    {
        $this->identitasForm->validate();
        $this->pegawai->update($this->identitasForm->all());

        $this->success('Data identitas berhasil diperbarui!');
    }

    public function saveJabatan()
    {
        $this->jabatanForm->validate();
        $this->pegawai->update($this->jabatanForm->all());

        $this->success('Data jabatan berhasil diperbarui!');
    }

    public function saveAdministrasi()
    {
        $this->administrasiForm->validate();
        $this->pegawai->update($this->administrasiForm->all());

        $this->success('Data administrasi berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.admin.pegawai.details');
    }
}
