<div>
    <x-header-page title="Import Data Absensi" :breadcrumbs="$breadcrumbs">
        <x-slot:actions>
            <flux:button
                variant="ghost"
                icon="x-mark"
                href="/admin/absensis"
            >
                Kembali
            </flux:button>
        </x-slot:actions>
    </x-header-page>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Upload -->
        <div class="lg:col-span-2">
            <x-mary-card>
                <div class="mb-4">
                    <flux:heading size="lg" level="2">
                        Upload File Excel
                    </flux:heading>
                    <flux:text class="text-gray-500">
                        Upload file Excel (.xlsx, .xls, .csv) berisi data absensi
                    </flux:text>
                </div>

                <form wire:submit="import">
                    <div class="space-y-4">
                        <flux:input
                            label="Pilih File"
                            type="file"
                            wire:model="file"
                            accept=".xlsx,.xls,.csv"
                            required
                        />

                        @error('file')
                            <flux:text color="danger">{{ $message }}</flux:text>
                        @enderror
                    </div>

                    <flux:spacer />

                    <div class="flex justify-end gap-2">
                        <flux:button
                            type="button"
                            variant="ghost"
                            href="/admin/absensis"
                        >
                            Batal
                        </flux:button>
                        <flux:button
                            type="submit"
                            variant="primary"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Import Data</span>
                            <span wire:loading>Memproses...</span>
                        </flux:button>
                    </div>
                </form>
            </x-mary-card>
        </div>

        <!-- Info Format & Hasil -->
        <div class="space-y-6">
            <!-- Info Format -->
            <x-mary-card>
                <flux:heading size="md" level="3" class="mb-4">
                    Format File Excel
                </flux:heading>

                <div class="text-sm space-y-3">
                    <div>
                        <flux:text class="font-medium">Kolom Wajib:</flux:text>
                        <ul class="list-disc list-inside text-gray-600 ml-2 mt-1">
                            <li>Nama</li>
                            <li>Tanggal (DD/MM/YYYY)</li>
                        </ul>
                    </div>

                    <div>
                        <flux:text class="font-medium">Kolom Opsional:</flux:text>
                        <ul class="list-disc list-inside text-gray-600 ml-2 mt-1">
                            <li>Scan Masuk (HH:MM)</li>
                            <li>Scan Pulang (HH:MM)</li>
                        </ul>
                    </div>

                    <flux:callout variant="info">
                        <div class="text-sm">
                            <strong>Aturan:</strong> Jika ada Scan Masuk, status otomatis di-set menjadi "Hadir".
                        </div>
                    </flux:callout>
                </div>
            </x-mary-card>

            <!-- Hasil Import -->
            @if($result)
                <x-mary-card>
                    <flux:heading size="md" level="3" class="mb-4">
                        Hasil Import
                    </flux:heading>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <flux:text class="font-medium text-green-700">Berhasil</flux:text>
                            <flux:text class="text-green-700 font-bold">{{ $result['imported'] }}</flux:text>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <flux:text class="font-medium text-yellow-700">Dilewati</flux:text>
                            <flux:text class="text-yellow-700 font-bold">{{ $result['skipped'] }}</flux:text>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <flux:text class="font-medium text-red-700">Gagal</flux:text>
                            <flux:text class="text-red-700 font-bold">{{ $result['failed'] }}</flux:text>
                        </div>
                    </div>

                    @if(!empty($result['errors']))
                        <flux:spacer />
                        <flux:heading size="sm" level="4" class="mb-2">
                            Detail Error
                        </flux:heading>
                        <div class="max-h-48 overflow-y-auto text-xs space-y-1">
                            @foreach($result['errors'] as $error)
                                <div class="text-red-600">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                </x-mary-card>
            @endif
        </div>
    </div>
</div>
