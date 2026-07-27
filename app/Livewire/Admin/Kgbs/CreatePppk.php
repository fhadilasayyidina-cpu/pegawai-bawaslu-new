<?php

namespace App\Livewire\Admin\Kgbs;

use App\Models\KgbRecord;
use App\Models\Pegawai;
use App\Services\Kgb\PppkSalaryTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePppk extends Component
{
    public int $pegawai_id = 0;

    // Naskah metadata
    public string $nomor_naskah = '';

    public string $tanggal_naskah = '';

    public string $ibu_kota_provinsi = 'Makassar';

    // PPPK-specific fields
    public string $ni_pppk = '';

    public string $jabatan_golongan = '';

    public string $masa_perjanjian_kerja = ''; // e.g. "5 Tahun"

    public string $perpanjangan_perjanjian_kerja = '-';

    public string $unit_kerja = '';

    public string $gaji_lama = '';

    // SK terakhir
    public string $sk_pejabat = 'Sekretaris Jenderal Bawaslu';

    public string $sk_tanggal = '';

    public string $sk_nomor = '';

    public string $sk_tmt = '';

    public ?int $sk_mkg_tahun = null;

    public ?int $sk_mkg_bulan = null;

    // Gaji baru
    public string $gaji_baru = '';

    public string $masa_kerja_baru = ''; // e.g. "4 Tahun 0 Bulan"

    public string $tmt_baru = '';

    // Signature
    public ?string $ttd_pengirim = '';

    public string $nama_kasek = '';

    public array $pegawaiOptions = [];

    public array $jabatanGolonganOptions = [];

    public array $masaKerjaOptions = [];

    protected function rules(): array
    {
        return [
            'pegawai_id' => 'required|exists:pegawais,id',
            'ibu_kota_provinsi' => 'required|string',
            'ni_pppk' => 'required|string',
            'jabatan_golongan' => 'required|string',
            'masa_perjanjian_kerja' => 'required|string',
            'perpanjangan_perjanjian_kerja' => 'required|string',
            'unit_kerja' => 'required|string',
            'gaji_lama' => 'required|string',
            'sk_pejabat' => 'required|string',
            'sk_tanggal' => 'required|date',
            'sk_nomor' => 'required|string',
            'sk_tmt' => 'required|date',
            'sk_mkg_tahun' => 'required|integer|min:0',
            'sk_mkg_bulan' => 'required|integer|between:0,11',
            'gaji_baru' => 'required|string',
            'masa_kerja_baru' => 'required|string',
            'tmt_baru' => 'required|date',
            'nama_kasek' => 'required|string',
            'ttd_pengirim' => 'nullable|string',
        ];
    }

    public function mount(): void
    {
        // Load only PPPK employees
        $this->pegawaiOptions = Pegawai::where('jenis_pegawai', 'PPPK')
            ->orWhere('jenis_pegawai', 'like', '%PPPK%')
            ->orderBy('nama')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => "{$p->nama} - {$p->nip_baru}",
            ])
            ->toArray();

        $this->jabatanGolonganOptions = app(PppkSalaryTable::class)->jabatanGolonganOptions();

        // Default kasek name if exists
        $pimpinan = \App\Models\Pimpinan::where('jabatan', 'like', '%Kepala Sekretariat%')->first();
        if ($pimpinan) {
            $this->nama_kasek = $pimpinan->nama;
        }

        $this->tanggal_naskah = date('Y-m-d');
    }

    public function updatedPegawaiId(int $id): void
    {
        if ($id > 0) {
            $pegawai = Pegawai::find($id);
            if ($pegawai) {
                // Prepopulate
                $this->ni_pppk = $pegawai->nip_baru ?? '';

                $jabatan = $pegawai->jabatan_nama ?? '';
                $golongan = $pegawai->gol_nama ?? '';
                if ($jabatan && $golongan) {
                    $this->jabatan_golongan = "{$jabatan} / {$golongan}";
                } elseif ($golongan) {
                    $this->jabatan_golongan = $golongan;
                } else {
                    $this->jabatan_golongan = $jabatan;
                }

                $this->unit_kerja = $pegawai->unit_kerja ?? '';

                if ($pegawai->mkgol !== null) {
                    $this->sk_mkg_tahun = (int) $pegawai->mkgol;
                    $this->sk_mkg_bulan = 0;
                }

                $this->refreshMasaKerjaOptions();

                if ($pegawai->mkgol !== null) {
                    $this->masa_kerja_baru = app(PppkSalaryTable::class)->formatMasaKerja($pegawai->mkgol + 2);
                    $this->updatedMasaKerjaBaru($this->masa_kerja_baru);
                }

                $this->updatedSkMkgTahun();

                if ($pegawai->tgl_kgb_terakhir) {
                    $this->sk_tanggal = $pegawai->tgl_kgb_terakhir->format('Y-m-d');
                    $this->sk_tmt = $pegawai->tgl_kgb_terakhir->format('Y-m-d');

                    $this->syncTmtBaruFromSkTmt();
                } else {
                    $this->sk_tanggal = '';
                    $this->sk_tmt = '';
                    $this->tmt_baru = '';
                }
            }
        }
    }

    public function updatedJabatanGolongan(): void
    {
        $this->refreshMasaKerjaOptions();
        $this->updatedMasaKerjaBaru($this->masa_kerja_baru);
        $this->updatedSkMkgTahun();
    }

    public function updatedMasaKerjaBaru(string|int|null $masaKerja): void
    {
        $gaji = app(PppkSalaryTable::class)->salary($this->jabatan_golongan, $masaKerja);

        if ($gaji !== null) {
            $this->gaji_baru = 'Rp. '.number_format($gaji, 0, ',', '.').',-';
        }
    }

    public function updatedSkMkgTahun(): void
    {
        if ($this->sk_mkg_tahun === null) {
            return;
        }

        $gaji = app(PppkSalaryTable::class)->salary($this->jabatan_golongan, $this->sk_mkg_tahun);
        if ($gaji !== null) {
            $this->gaji_lama = 'Rp. '.number_format($gaji, 0, ',', '.').',-';
        }
    }

    public function updatedSkTmt(): void
    {
        $this->syncTmtBaruFromSkTmt();
    }

    private function syncTmtBaruFromSkTmt(): void
    {
        if (! $this->sk_tmt) {
            $this->tmt_baru = '';

            return;
        }

        $tmtBaru = Carbon::parse($this->sk_tmt)->addYears(2);
        $this->tmt_baru = $tmtBaru->format('Y-m-d');
    }

    private function refreshMasaKerjaOptions(): void
    {
        $this->masaKerjaOptions = app(PppkSalaryTable::class)->masaKerjaOptions($this->jabatan_golongan);
    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['sk_mkg'] = sprintf(
            '%d Tahun %d Bulan',
            $validatedData['sk_mkg_tahun'],
            $validatedData['sk_mkg_bulan'],
        );
        unset($validatedData['sk_mkg_tahun'], $validatedData['sk_mkg_bulan']);

        $validatedData['nomor_naskah'] = sprintf(
            'KGB-PPPK/%d/%s',
            $this->pegawai_id,
            Carbon::parse($validatedData['tmt_baru'])->format('Ymd'),
        );
        $validatedData['tanggal_naskah'] = now()->toDateString();

        DB::transaction(function () use ($validatedData) {
            $pegawai = Pegawai::findOrFail($this->pegawai_id);
            $pegawai->tgl_kgb_terakhir = Carbon::parse($this->tmt_baru);
            $pegawai->save();

            KgbRecord::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'nomor_naskah' => $validatedData['nomor_naskah'],
                ],
                [
                    'created_by' => auth()->id(),
                    'jenis_kgb' => 'PPPK',
                    'tanggal_naskah' => $validatedData['tanggal_naskah'],
                    'tmt_baru' => $validatedData['tmt_baru'],
                    'next_kgb_date' => Carbon::parse($validatedData['tmt_baru'])->addYears(2),
                    'data' => $validatedData,
                ],
            );
        });

        session()->flash('message', 'Data KGB PPPK berhasil disimpan.');

        return redirect()->route('admin.kgbs.pppk-pdf', $validatedData);
    }

    public function render()
    {
        return view('livewire.admin.kgbs.create-pppk');
    }
}
