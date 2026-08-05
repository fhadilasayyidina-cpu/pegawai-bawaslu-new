<?php

namespace App\Livewire\Admin\HariLibur;

use App\Models\HariLibur;
use App\Services\HariLibur\HariLiburService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showImportModal = false;

    public ?int $editingId = null;

    public array $importResult = [];

    public string $date = '';

    public string $description = '';

    public string $importYear = '';

    public ?string $search = null;

    public ?string $tanggal_dari = null;

    public ?string $tanggal_sampai = null;

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->reset(['date', 'description']);
        $this->resetErrorBag();
    }

    public function openEditModal(int $id): void
    {
        $hariLibur = HariLibur::findOrFail($id);
        $this->editingId = $id;
        $this->date = $hariLibur->date->format('Y-m-d');
        $this->description = $hariLibur->description;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingId', 'date', 'description']);
        $this->resetErrorBag();
    }

    public function openImportModal(): void
    {
        $this->showImportModal = true;
        $this->importYear = (string) now()->year;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->importResult = [];
        $this->reset('importYear');
    }

    public function importFromStorage(): void
    {
        $this->validate([
            'importYear' => ['required', 'integer', 'min:2000', 'max:2099'],
        ]);

        $service = new HariLiburService;
        try {
            $tahun = new \DateTime($this->importYear.'-01-01');
            $this->importResult = $service->importDataLibur($tahun);

            if ($this->importResult['success']) {
                session()->flash('status', $this->importResult['message']);
            } else {
                session()->flash('error', $this->importResult['message']);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import: '.$e->getMessage());
        }

        $this->closeImportModal();
    }

    /**
     * Save a new holiday.
     */
    public function save(): void
    {
        $this->validate([
            'date' => ['required', 'date', 'unique:hari_liburs,date'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $service = new HariLiburService;
        $service->create([
            'date' => $this->date,
            'description' => $this->description,
            'is_imported' => false,
        ]);

        $this->closeCreateModal();

        session()->flash('status', 'Hari libur berhasil ditambahkan.');
    }

    /**
     * Update an existing holiday.
     */
    public function update(): void
    {
        $this->validate([
            'date' => ['required', 'date', 'unique:hari_liburs,date,'.$this->editingId],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $service = new HariLiburService;
        $service->update($this->editingId, [
            'date' => $this->date,
            'description' => $this->description,
        ]);

        $this->closeEditModal();

        session()->flash('status', 'Hari libur berhasil diperbarui.');
    }

    /**
     * Delete a holiday.
     */
    public function delete(int $id): void
    {
        $service = new HariLiburService;
        $service->delete($id);

        session()->flash('status', 'Hari libur berhasil dihapus.');
    }

    /**
     * Reset all filters.
     */
    public function resetFilter(): void
    {
        $this->reset(['search', 'tanggal_dari', 'tanggal_sampai']);
        $this->resetPage();
    }

    /**
     * Generate all weekend holidays (Saturday & Sunday) for the current year.
     */
    public function generateWeekendHolidays(): void
    {
        $currentYear = now()->year;
        $startDate = Carbon::createFromDate($currentYear, 1, 1);
        $endDate = Carbon::createFromDate($currentYear, 12, 31);

        $generated = 0;
        $skipped = 0;

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Check if Saturday (6) or Sunday (0/7)
            if ($date->isSaturday() || $date->isSunday()) {
                $existing = HariLibur::where('date', $date->format('Y-m-d'))->first();

                if (! $existing) {
                    HariLibur::create([
                        'date' => $date->format('Y-m-d'),
                        'description' => 'Libur Akhir Pekan',
                        'is_imported' => false,
                    ]);
                    $generated++;
                } else {
                    $skipped++;
                }
            }
        }

        session()->flash('status', "Selesai: {$generated} hari akhir pekan ditambahkan, {$skipped} sudah ada.");
    }

    /**
     * Get calendar events property.
     *
     * @return array<int, array{date: string, label: string, css: string}>
     */
    public function getCalendarEventsProperty(): array
    {
        return HariLibur::query()
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->when($this->tanggal_dari, fn ($q) => $q->where('date', '>=', $this->tanggal_dari))
            ->when($this->tanggal_sampai, fn ($q) => $q->where('date', '<=', $this->tanggal_sampai))
            ->get()
            ->map(fn ($h) => [
                'date' => $h->date->format('Y-m-d'),
                'label' => $h->description,
                'css' => '!bg-red-500 text-white font-semibold',
            ])
            ->toArray();
    }

    public function render(): View
    {
        $query = HariLibur::query()
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->when($this->tanggal_dari, fn ($q) => $q->where('date', '>=', $this->tanggal_dari))
            ->when($this->tanggal_sampai, fn ($q) => $q->where('date', '<=', $this->tanggal_sampai));

        return view('livewire.admin.hari-libur.index', [
            'hariLiburs' => $query->orderBy('date', 'desc')->paginate(10),
        ]);
    }
}
