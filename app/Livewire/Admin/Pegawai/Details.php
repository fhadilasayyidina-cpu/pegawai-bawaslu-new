<?php

namespace App\Livewire\Admin\Pegawai;

use App\Livewire\Forms\Pegawai\AdministrasiForm;
use App\Livewire\Forms\Pegawai\IdentitasForm;
use App\Livewire\Forms\Pegawai\JabatanForm;
use App\Livewire\Forms\Pegawai\PendidikanForm;
use App\Livewire\Forms\Pegawai\UnitOrganisasiForm;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Details extends Component
{
    use WithFileUploads;

    public Pegawai $pegawai;

    public IdentitasForm $identitasForm;

    public JabatanForm $jabatanForm;

    public AdministrasiForm $administrasiForm;

    public PendidikanForm $pendidikanForm;

    public UnitOrganisasiForm $unitOrganisasiForm;

    public string $selectedTab = 'ringkasan-tab';

    public function getNextKgbDateProperty(): ?\Carbon\Carbon
    {
        if (! $this->pegawai->tgl_kgb_terakhir) {
            return null;
        }

        return $this->pegawai->tgl_kgb_terakhir->addYears(2);
    }

    public function getAbsensiStatisticsProperty(): array
    {
        return app(\App\Services\Absensi\AbsensiStatisticService::class)->getStatistics(
            pegawaiId: $this->pegawai->id
        );
    }

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
        $this->pendidikanForm->setPegawai($this->pegawai);
        $this->unitOrganisasiForm->setPegawai($this->pegawai);

    }

    public function saveIdentitas()
    {
        $this->identitasForm->validate();

        $data = $this->identitasForm->except(['foto']);

        // Check if foto is a newly uploaded file (not just the existing path string)
        if ($this->identitasForm->foto && is_object($this->identitasForm->foto)) {
            if ($this->pegawai->foto) {
                Storage::disk('public')->delete($this->pegawai->foto);
            }

            $path = $this->identitasForm->foto->store('pegawai/foto/'.$this->pegawai->id, 'public');
            $data['foto'] = $path;
        }

        $this->pegawai->update($data);
        $this->dispatch('toast',
            type: 'success',
            message: 'Data berhasil disimpan!'
        );
    }

    public function deleteFoto()
    {
        if ($this->pegawai->foto) {
            Storage::disk('public')->delete($this->pegawai->foto);
            $this->pegawai->update(['foto' => null]);
            $this->identitasForm->foto = null;
        }
    }

    public function saveJabatan()
    {
        $this->jabatanForm->validate();
        $this->pegawai->update($this->jabatanForm->all());
        $this->dispatch('toast',
            type: 'success',
            message: 'Data Jabatan & Golongan berhasil disimpan!'
        );
    }

    public function saveAdministrasi()
    {
        $this->administrasiForm->validate();
        $this->pegawai->update($this->administrasiForm->all());
        $this->dispatch('toast',
            type: 'success',
            message: 'Data Administrasi berhasil disimpan!'
        );
    }

    public function savePendidikan()
    {
        $this->pendidikanForm->validate();
        $this->pegawai->update($this->pendidikanForm->all());
        $this->dispatch('toast',
            type: 'success',
            message: 'Data Pendidikan berhasil disimpan!'
        );
    }

    public function saveUnitOrganisasi()
    {
        $this->unitOrganisasiForm->validate();
        $this->pegawai->update($this->unitOrganisasiForm->all());
        $this->dispatch('toast',
            type: 'success',
            message: 'Data Unit & Organisasi berhasil disimpan!'
        );
    }

    public function render()
    {
        return view('livewire.admin.pegawai.details');
    }
}
