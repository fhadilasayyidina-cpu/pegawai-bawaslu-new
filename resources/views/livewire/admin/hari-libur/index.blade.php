<div>
    <x-header-page
        title="Manajemen Hari Libur"
        :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Hari Libur', 'href' => '/admin/hari-liburs']]"
    >
        <x-slot:actions>
            <flux:button
                wire:click="generateWeekendHolidays"
                wire:confirm="Apakah Anda yakin ingin generate semua hari Sabtu & Minggu tahun berjalan sebagai hari libur?"
                variant="ghost"
                icon="calendar-days"
            >
                Generate Weekend
            </flux:button>
            <flux:button wire:click="openImportModal" variant="ghost" icon="arrow-down-tray">
                Import
            </flux:button>
            <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
                Tambah Hari Libur
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="my-4 grid gap-2">
        <!-- Calendar View -->
        <x-mary-card>
            <div class="mt-1">
                <x-mary-calendar
                    months="3"
                    :events="$this->calendarEvents"
                    locale="id-ID"
                />
            </div>
        </x-mary-card>

        <!-- Filter Section -->
        <x-mary-card>
            <flux:heading size="lg">Filter</flux:heading>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:field label="Cari Deskripsi">
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama hari libur..."
                        icon="magnifying-glass"
                    />
                </flux:field>

                <flux:field label="Dari Tanggal">
                    <flux:input
                        type="date"
                        wire:model.live="tanggal_dari"
                    />
                </flux:field>

                <flux:field label="Sampai Tanggal">
                    <flux:input
                        type="date"
                        wire:model.live="tanggal_sampai"
                    />
                </flux:field>
            </div>

            @if($search || $tanggal_dari || $tanggal_sampai)
                <flux:button
                    wire:click="resetFilter"
                    variant="ghost"
                    icon="x-mark"
                    class="mt-4"
                >
                    Reset Filter
                </flux:button>
            @endif
        </x-mary-card>


        <!-- Table -->
        <x-mary-card>
            @if(session('status'))
                <flux:callout variant="success">{{ session('status') }}</flux:callout>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterangan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($hariLiburs as $index => $hariLibur)
                            <tr wire:key="hari-libur-{{ $hariLibur->id }}">
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $hariLiburs->firstItem() + $index }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $hariLibur->date->translatedFormat('d F Y') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $hariLibur->description }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    @if($hariLibur->is_imported)
                                        <flux:badge variant="info">Import</flux:badge>
                                    @elseif($hariLibur->description === 'Libur Akhir Pekan')
                                        <flux:badge variant="warning">Weekend</flux:badge>
                                    @else
                                        <flux:badge variant="neutral">Manual</flux:badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                                        <flux:menu>
                                            <flux:menu.item
                                                wire:click="openEditModal({{ $hariLibur->id }})"
                                                icon="pencil-square"
                                            >
                                                Ubah
                                            </flux:menu.item>
                                            <flux:menu.item
                                                wire:click="delete({{ $hariLibur->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus hari libur ini?"
                                                icon="trash"
                                                variant="danger"
                                            >
                                                Hapus
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada data hari libur
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($hariLiburs->hasPages())
                <div class="mt-4">
                    {{ $hariLiburs->onEachSide(1)->links() }}
                </div>
            @endif
        </x-mary-card>
    </div>

    <!-- Modal Tambah Hari Libur -->
    <flux:modal
        name="create-hari-libur-modal"
        class="max-w-md"
        wire:model="showCreateModal"
        @close="closeCreateModal"
    >
        <form wire:submit="save" class="space-y-4">
            <flux:heading size="lg">Tambah Hari Libur</flux:heading>

            <flux:field label="Tanggal">
                <flux:input type="date" wire:model="date" required />
                @error('date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </flux:field>

            <flux:field label="Keterangan">
                <flux:input
                    wire:model="description"
                    placeholder="Contoh: Hari Raya Idul Fitri"
                    required
                />
                @error('description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </flux:field>

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Simpan</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Modal Ubah Hari Libur -->
    <flux:modal
        name="edit-hari-libur-modal"
        class="max-w-md"
        wire:model="showEditModal"
        @close="closeEditModal"
    >
        <form wire:submit="update" class="space-y-4">
            <flux:heading size="lg">Ubah Hari Libur</flux:heading>

            <flux:field label="Tanggal">
                <flux:input type="date" wire:model="date" required />
                @error('date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </flux:field>

            <flux:field label="Keterangan">
                <flux:input
                    wire:model="description"
                    placeholder="Contoh: Hari Raya Idul Fitri"
                    required
                />
                @error('description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </flux:field>

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Simpan Perubahan</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Modal Import dari Storage JSON -->
    <flux:modal
        name="import-hari-libur-modal"
        class="max-w-md"
        wire:model="showImportModal"
        @close="closeImportModal"
    >
        <div class="space-y-4">
            <flux:heading size="lg">Import Hari Libur Nasional</flux:heading>

            <flux:text>
                Import data hari libur nasional dari file JSON di storage (contoh: <code>storage/app/DataLibur/2026.json</code>).
            </flux:text>

            <flux:field label="Tahun">
                <flux:input type="number" wire:model="importYear" placeholder="Contoh: 2026" required />
                @error('importYear') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </flux:field>

            @if(session('error'))
                <flux:callout variant="danger">{{ session('error') }}</flux:callout>
            @endif

            @if(!empty($importResult))
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <flux:text class="text-green-700">Berhasil ditambahkan/diperbarui</flux:text>
                        <flux:text class="text-green-700 font-bold">
                            {{ ($importResult['imported'] ?? 0) + ($importResult['skipped'] ?? 0) }}
                        </flux:text>
                    </div>
                </div>
            @endif

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">
                        @if(!empty($importResult)) Tutup @else Batal @endif
                    </flux:button>
                </flux:modal.close>
                @if(empty($importResult))
                    <flux:button wire:click="importFromStorage" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Import Sekarang</span>
                        <span wire:loading>Memproses...</span>
                    </flux:button>
                @endif
            </div>
        </div>
    </flux:modal>
</div>
