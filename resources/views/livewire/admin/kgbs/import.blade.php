<div>
    <x-header-page title="Import Data KGB" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button variant="ghost" icon="arrow-left" href="/admin/kgbs">
                Kembali
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="my-4">
        <x-mary-card>
            <div class="mb-6">
                <flux:heading size="lg">Format File Import</flux:heading>
                <flux:text class="mt-2">
                    File harus berupa Excel (.xlsx, .xls) atau CSV dengan 2 kolom:
                </flux:text>
                <ul class="list-disc list-inside mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <li><strong>NIP</strong> - Nomor Induk Pegawai</li>
                    <li><strong>tgl_kgb_terakhir</strong> - Tanggal KGB terakhir (format: DD/MM/YYYY)</li>
                </ul>

                <div class="mt-4">
                    <flux:button variant="secondary" icon="document-arrow-down" wire:click="downloadTemplate">
                        Download Template
                    </flux:button>
                </div>
            </div>

            <flux:separator />

            @if(empty($importResult))
                <div class="mt-6">
                    <flux:heading size="lg">Upload File</flux:heading>

                    <form wire:submit="submit" class="mt-4">
                        <div>
                            <flux:input
                                type="file"
                                wire:model="file"
                                label="Pilih File Excel/CSV"
                                accept=".xlsx,.xls,.csv"
                            />
                            @error('file')
                                <flux:text color="danger">{{ $message }}</flux:text>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <flux:button
                                type="submit"
                                variant="primary"
                                icon="arrow-up-tray"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>Import Data</span>
                                <span wire:loading>Memproses...</span>
                            </flux:button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-6">
                    <flux:heading size="lg">Hasil Import</flux:heading>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <div class="text-green-600 dark:text-green-400 text-sm font-medium">Berhasil</div>
                            <div class="text-2xl text-green-600 dark:text-green-400 font-bold">{{ $importResult['imported'] }}</div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                            <div class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Dilewati</div>
                            <div class="text-2xl text-yellow-600 dark:text-yellow-400 font-bold">{{ $importResult['skipped'] }}</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                            <div class="text-red-600 dark:text-red-400 text-sm font-medium">Gagal</div>
                            <div class="text-2xl text-red-600 dark:text-red-400 font-bold">{{ $importResult['failed'] }}</div>
                        </div>
                    </div>

                    @if(!empty($importResult['errors']))
                        <div class="mt-4">
                            <flux:heading size="md">Detail Error</flux:heading>
                            <div class="max-h-64 overflow-y-auto bg-red-50 dark:bg-red-900/20 rounded-lg p-4 mt-2">
                                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                    @foreach($importResult['errors'] as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 flex gap-2">
                        <flux:button variant="primary" icon="arrow-left" href="/admin/kgbs">
                            Kembali ke Daftar KGB
                        </flux:button>
                        <flux:button
                            variant="secondary"
                            icon="arrow-counter-clockwise"
                            wire:click="$set('importResult', [])"
                        >
                            Import Lagi
                        </flux:button>
                    </div>
                </div>
            @endif
        </x-mary-card>
    </div>
</div>
