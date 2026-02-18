<?php

namespace App\Livewire\Admin\HariLibur;

use App\Models\HariLibur;
use App\Services\ImportHariLiburService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;

    public bool $showImportModal = false;

    public array $importResult = [];

    public string $date = '';

    public string $description = '';

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

    public function openImportModal(): void
    {
        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->importResult = [];
    }

    public function importFromApi(): void
    {
        $service = new ImportHariLiburService;
        $this->importResult = $service->importFromApi();

        $totalProcessed = $this->importResult['imported'] + $this->importResult['skipped'];

        if ($totalProcessed > 0) {
            session()->flash('status', "Selesai: {$this->importResult['imported']} ditambahkan, {$this->importResult['skipped']} dilewati.");
        } else {
            session()->flash('status', 'Import gagal. Coba lagi nanti.');
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

        HariLibur::create([
            'date' => $this->date,
            'description' => $this->description,
            'is_imported' => false,
        ]);

        $this->closeCreateModal();

        session()->flash('status', 'Hari libur berhasil ditambahkan.');
    }

    /**
     * Delete a holiday.
     */
    public function delete(int $id): void
    {
        HariLibur::findOrFail($id)->delete();

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
