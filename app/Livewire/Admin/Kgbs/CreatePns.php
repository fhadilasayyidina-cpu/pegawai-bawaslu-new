<?php

namespace App\Livewire\Admin\Kgbs;

use App\Models\KgbRecord;
use App\Models\Pegawai;
use App\Services\Kgb\PnsSalaryTable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePns extends Component
{
    public int $pegawai_id = 0;

    public string $ibu_kota_provinsi = 'Makassar';

    // SK terakhir
    public string $sk_pejabat = 'Kepala Sekretariat Bawaslu Provinsi Sulawesi Selatan';

    public string $sk_tanggal = '';

    public string $sk_nomor = '';

    public string $sk_tmt = '';

    public ?int $sk_mkg_tahun = null;

    public ?int $sk_mkg_bulan = null;

    // Gaji lama (prefilled/inputted)
    public string $gaji_pokok_lama = '';

    // Gaji baru
    public string $gaji_pokok_baru = '';

    public string $masa_kerja_baru = ''; // Masa kerja baru (e.g. "14 Tahun 4 Bulan")

    public string $golongan_ruang_baru = '';

    public string $tmt_baru = '';

    public string $next_kgb_date = '';

    // Signature
    public ?string $ttd_pengirim = ''; // For space/text signature

    public string $nama_kasek = '';

    public array $pegawaiOptions = [];

    public array $golonganOptions = [];

    public array $masaKerjaOptions = [];

    protected function rules(): array
    {
        return [
            'pegawai_id' => 'required|exists:pegawais,id',
            'ibu_kota_provinsi' => 'required|string',
            'sk_pejabat' => 'required|string',
            'sk_tanggal' => 'required|date',
            'sk_nomor' => 'required|string',
            'sk_tmt' => 'required|date',
            'sk_mkg_tahun' => 'required|integer|min:0',
            'sk_mkg_bulan' => 'required|integer|between:0,11',
            'gaji_pokok_lama' => 'required|string',
            'gaji_pokok_baru' => 'required|string',
            'masa_kerja_baru' => 'required|string',
            'golongan_ruang_baru' => 'required|string',
            'tmt_baru' => 'required|date',
            'next_kgb_date' => 'required|date',
            'nama_kasek' => 'required|string',
            'ttd_pengirim' => 'nullable|string',
        ];
    }

    public function mount(): void
    {
        // Load only PNS/Organik/DPK employees
        $this->pegawaiOptions = Pegawai::where(function ($query) {
            $query->where('jenis_pegawai', 'PNS')
                ->orWhere('jenis_pegawai', 'like', '%organik%')
                ->orWhere('jenis_pegawai', 'like', '%dpk%');
        })
            ->orderBy('nama')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => "{$p->nama} - {$p->nip_baru}",
            ])
            ->toArray();

        // Default kasek name if exists
        $pimpinan = \App\Models\Pimpinan::where('jabatan', 'like', '%Kepala Sekretariat%')->first();
        if ($pimpinan) {
            $this->nama_kasek = $pimpinan->nama;
        }

        $this->golonganOptions = app(PnsSalaryTable::class)->golonganOptions();
    }

    public function updatedPegawaiId(int $id): void
    {
        if ($id > 0) {
            $pegawai = Pegawai::find($id);
            if ($pegawai) {
                $this->golongan_ruang_baru = $pegawai->gol_nama ?? '';
                // Try to format current mkgol
                if ($pegawai->mkgol !== null) {
                    $this->sk_mkg_tahun = (int) $pegawai->mkgol;
                    $this->sk_mkg_bulan = 0;
                }

                $this->refreshMasaKerjaOptions();

                if ($pegawai->mkgol !== null) {
                    $this->masa_kerja_baru = app(PnsSalaryTable::class)->formatMasaKerja($pegawai->mkgol + 2);
                    $this->updatedMasaKerjaBaru($this->masa_kerja_baru);
                }

                // If there's CPNS/PNS date or previous KGB date
                if ($pegawai->tgl_kgb_terakhir) {
                    $this->sk_tanggal = $pegawai->tgl_kgb_terakhir->format('Y-m-d');
                    $this->sk_tmt = $pegawai->tgl_kgb_terakhir->format('Y-m-d');

                    $this->syncKgbDatesFromSkTmt();
                } else {
                    $this->sk_tanggal = '';
                    $this->sk_tmt = '';
                    $this->tmt_baru = '';
                    $this->next_kgb_date = '';
                }
            }
        }
    }

    public function updatedGolonganRuangBaru(): void
    {
        $this->refreshMasaKerjaOptions();

        if (app(PnsSalaryTable::class)->salary($this->golongan_ruang_baru, $this->masa_kerja_baru) === null) {
            $this->masa_kerja_baru = '';
            $this->gaji_pokok_baru = '';

            return;
        }

        $this->updatedMasaKerjaBaru($this->masa_kerja_baru);
    }

    public function updatedMasaKerjaBaru(string|int|null $masaKerja): void
    {
        $gaji = app(PnsSalaryTable::class)->salary($this->golongan_ruang_baru, $masaKerja);

        $this->gaji_pokok_baru = $gaji === null
            ? ''
            : 'Rp. '.number_format($gaji, 0, ',', '.').',-';
    }

    public function updatedSkTmt(): void
    {
        $this->syncKgbDatesFromSkTmt();
    }

    private function syncKgbDatesFromSkTmt(): void
    {
        if (! $this->sk_tmt) {
            $this->tmt_baru = '';
            $this->next_kgb_date = '';

            return;
        }

        $tmtBaru = \Carbon\Carbon::parse($this->sk_tmt)->addYears(2);
        $this->tmt_baru = $tmtBaru->format('Y-m-d');
        $this->next_kgb_date = $tmtBaru->copy()->addYears(2)->format('Y-m-d');
    }

    private function refreshMasaKerjaOptions(): void
    {
        $this->masaKerjaOptions = app(PnsSalaryTable::class)->masaKerjaOptions($this->golongan_ruang_baru);
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

        $validatedData['nomor_naskah'] = '-';
        $validatedData['tanggal_naskah'] = now()->toDateString();

        DB::transaction(function () use ($validatedData) {
            $pegawai = Pegawai::findOrFail($this->pegawai_id);
            $pegawai->tgl_kgb_terakhir = \Carbon\Carbon::parse($this->tmt_baru);
            $pegawai->save();

            KgbRecord::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'tmt_baru' => $validatedData['tmt_baru'],
                ],
                [
                    'created_by' => auth()->id(),
                    'jenis_kgb' => 'PNS',
                    'nomor_naskah' => '-',
                    'tanggal_naskah' => $validatedData['tanggal_naskah'],
                    'tmt_baru' => $validatedData['tmt_baru'],
                    'next_kgb_date' => $validatedData['next_kgb_date'],
                    'data' => $validatedData,
                ],
            );
        });

        session()->flash('message', 'Data KGB PNS berhasil disimpan.');

        // Redirect to generate PDF route (will download/stream PDF)
        return redirect()->route('admin.kgbs.pns-pdf', $validatedData);
    }

    public function render()
    {
        return view('livewire.admin.kgbs.create-pns');
    }
}
