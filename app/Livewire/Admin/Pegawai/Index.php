<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Pegawai;
use App\Services\Pegawai\ImportPegawaiService;
use App\Services\Pegawai\PegawaiService;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast, WithFileUploads, WithPagination;

    public ?string $search = null;

    public ?string $kabKota = null;

    public array $kabKotaOptions = [];

    public ?string $rangeUmur = null;

    public array $rangeUmurOptions = [];

    public ?string $jenisKelamin = null;

    public array $jenisKelaminOptions = [];

    public ?string $agama = null;

    public array $agamaOptions = [];

    public $file;

    public ?array $importResult = null;

    public bool $myModal3 = false;

    public bool $showBirthdayModal = false;

    public int $selectedBirthdayMonth = 0;

    public array $monthOptions = [];

    public array $tableHeaders = [
        ['key' => 'nomor', 'label' => 'No', 'class' => 'w-1'],
        ['key' => 'id', 'label' => 'ID', 'link' => true, 'hidden' => true],
        ['key' => 'nip_baru', 'label' => 'NIP'],
        ['key' => 'nama', 'label' => 'Nama'],
        ['key' => 'jenis_jabatan_nama', 'label' => 'Jenis Jabatan'],
        ['key' => 'jabatan_nama', 'label' => 'Jabatan'],
        ['key' => 'kab_kota', 'label' => 'Kabupaten Kota'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Manajemen Pegawai', 'link' => '#'],
    ];

    public function getPegawaisProperty()
    {
        return app(PegawaiService::class)
            ->getAllPegawai(
                $this->search,
                $this->kabKota,
                $this->rangeUmur,
                $this->jenisKelamin,
                $this->agama
            );
    }

    public function getBirthdayEmployeesProperty()
    {
        $today = Carbon::today();

        return Pegawai::query()
            ->whereNotNull('tgl_lahir')
            ->whereRaw('MONTH(tgl_lahir) = ?', [$today->month])
            ->whereRaw('DAY(tgl_lahir) = ?', [$today->day])
            ->orderBy('nama')
            ->get(['id', 'nama', 'jabatan_nama', 'tgl_lahir', 'foto']);
    }

    public function getMonthlyBirthdayEmployeesProperty()
    {
        $month = $this->selectedBirthdayMonth > 0 ? (int) $this->selectedBirthdayMonth : (int) Carbon::now()->month;

        $query = Pegawai::query()
            ->whereNotNull('tgl_lahir')
            ->whereRaw('MONTH(tgl_lahir) = ?', [$month]);

        if (auth()->check() && auth()->user()->role === \App\Enums\Role::OPERATOR && auth()->user()->access_scope) {
            $query->where('kab_kota', auth()->user()->access_scope);
        }

        if ($this->kabKota) {
            $query->where('kab_kota', $this->kabKota);
        }

        return $query->orderByRaw('DAY(tgl_lahir) ASC')
            ->orderBy('nama')
            ->get();
    }

    public function mount()
    {
        $this->kabKotaOptions = app(PegawaiService::class)->getKabKota()->toArray();
        $this->rangeUmurOptions = app(PegawaiService::class)->getRangeUmurOptions()->toArray();
        $this->jenisKelaminOptions = app(PegawaiService::class)->getJenisKelaminOptions()->toArray();
        $this->agamaOptions = app(PegawaiService::class)->getAgamaOptions()->toArray();

        $this->selectedBirthdayMonth = (int) Carbon::now()->month;
        $this->monthOptions = [
            ['id' => 1, 'name' => 'Januari'],
            ['id' => 2, 'name' => 'Februari'],
            ['id' => 3, 'name' => 'Maret'],
            ['id' => 4, 'name' => 'April'],
            ['id' => 5, 'name' => 'Mei'],
            ['id' => 6, 'name' => 'Juni'],
            ['id' => 7, 'name' => 'Juli'],
            ['id' => 8, 'name' => 'Agustus'],
            ['id' => 9, 'name' => 'September'],
            ['id' => 10, 'name' => 'Oktober'],
            ['id' => 11, 'name' => 'November'],
            ['id' => 12, 'name' => 'Desember'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedKabKota(): void
    {
        $this->resetPage();
    }

    public function updatedRangeUmur(): void
    {
        $this->resetPage();
    }

    public function updatedJenisKelamin(): void
    {
        $this->resetPage();
    }

    public function updatedAgama(): void
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        app(PegawaiService::class)->deletePegawai($id);

        $this->success('Pegawai berhasil dihapus!');
    }

    public function import(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        try {
            $this->importResult = app(ImportPegawaiService::class)->import($this->file->getRealPath());

            $this->success("Import selesai: {$this->importResult['created']} data baru, {$this->importResult['updated']} diperbarui.");

            $this->myModal3 = false;
            $this->reset('file');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->error('Gagal mengimport data: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pegawai.index', [
            'birthdayEmployees' => $this->birthdayEmployees,
        ]);
    }
}
