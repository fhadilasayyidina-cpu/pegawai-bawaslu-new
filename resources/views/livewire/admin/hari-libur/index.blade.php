<div>
    <x-header-page title="Manajemen Hari Libur" :breadcrumbs="[['label' => 'Admin', 'href' => '#'], ['label' => 'Hari Libur', 'href' => '/admin/hari-liburs']]">
    </x-header-page>

    <div class="my-4 grid gap-4">
        <!-- Form Tambah -->
        <x-mary-card>
            <flux:heading size="lg">Tambah Hari Libur</flux:heading>

            <form wire:submit="save" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <flux:field label="Tanggal">
                        <flux:input
                            type="date"
                            wire:model="date"
                            required
                        />
                    </flux:field>
                    @error('date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:field label="Keterangan">
                        <flux:input
                            wire:model="description"
                            placeholder="Contoh: Hari Raya Idul Fitri"
                            required
                        />
                    </flux:field>
                    @error('description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-end">
                    <flux:button wire:click="save" variant="primary" class="w-full">
                        Simpan
                    </flux:button>
                </div>
            </form>
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
                                    @else
                                        <flux:badge variant="neutral">Manual</flux:badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                                        <flux:menu>
                                            <flux:menu.item
                                                wire:click="delete({{ $hariLibur->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus hari libur ini?"
                                                icon="trash"
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
</div>
