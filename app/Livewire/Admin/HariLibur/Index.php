<?php

namespace App\Livewire\Admin\HariLibur;

use App\Models\HariLibur;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $date = '';

    public string $description = '';

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

    public function render()
    {
        return view('livewire.admin.hari-libur.index', [
            'hariLiburs' => HariLibur::orderBy('date', 'desc')->paginate(10),
        ]);
    }
}
