<?php

namespace App\Livewire\Admin\HariLibur;

use App\Models\HariLibur;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $date = '';

    public string $description = '';

    public ?string $search = null;

    public ?string $tanggal_dari = null;

    public ?string $tanggal_sampai = null;

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

        $this->reset(['date', 'description']);

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
                'css' => 'bg-red-500 text-white font-semibold',
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
        ])->layout('layouts.app');
    }
}
