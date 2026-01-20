<x-layouts.app title="Import Pegawai">
    @volt
    <?php
    use App\Services\Pegawai\ImportPegawaiService;
    use App\Models\Pegawai;
    use Livewire\Volt\Component;

    new class extends Component {
        public $file = '';

        public array $breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Manajemen Pegawai', 'link' => '/admin/pegawais'],
            ['label' => 'Import', 'link' => '#'],
        ];

        public ?array $result = null;

        public function import(): void
        {
            $this->validate([
                'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            ]);

            $service = app(ImportPegawaiService::class);
            $this->result = $service->import($this->file);

            if ($this->result['success']) {
                $this->dispatch('notyf:show', [
                    'type' => 'success',
                    'message' => $this->result['message']
                ]);
            } else {
                $this->dispatch('notyf:show', [
                    'type' => 'error',
                    'message' => $this->result['message']
                ]);
            }
        }

        public function getStatsProperty(): array
        {
            return [
                'total' => Pegawai::count(),
                'pns' => Pegawai::whereNotNull('nip_baru')->count(),
                'ppnpn' => Pegawai::whereNull('nip_baru')->count(),
                'with_nip' => Pegawai::whereNotNull('nip_baru')->count(),
            ];
        }
    };
    ?>

    <div>
        <x-header-page title="Import Data Pegawai" :breadcrumbs="$breadcrumbs" />

        <div class="max-w-2xl mx-auto">
            <x-mary-card>
                <form wire:submit="import" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">File Excel (xlsx, xls, csv)</label>
                            <input type="file" wire:model="file" accept=".xlsx,.xls,.csv" class="w-full" />
                            @error('file')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-info/10 p-4 rounded-md">
                            <p class="text-sm">
                                <strong>Info:</strong> Pastikan file Excel memiliki header kolom: nip_baru, nama, gelar_depan, gelar_blk, dll.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <x-mary-button label="Batal" link="/admin/pegawais" variant="ghost" />
                        <x-mary-button label="Import File" type="submit" class="btn-primary" icon="o-arrow-up-tray" />
                    </div>
                </form>
            </x-mary-card>

            @if($this->stats)
                <x-mary-card class="mt-4">
                    <h3 class="text-lg font-semibold mb-3">Statistik Pegawai</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-base-200 p-3 rounded">
                            <p class="text-sm opacity-70">Total Pegawai</p>
                            <p class="text-2xl font-bold">{{ $this->stats['total'] }}</p>
                        </div>
                        <div class="bg-base-200 p-3 rounded">
                            <p class="text-sm opacity-70">PNS</p>
                            <p class="text-2xl font-bold">{{ $this->stats['pns'] }}</p>
                        </div>
                        <div class="bg-base-200 p-3 rounded">
                            <p class="text-sm opacity-70">PPNPN</p>
                            <p class="text-2xl font-bold">{{ $this->stats['ppnpn'] }}</p>
                        </div>
                        <div class="bg-base-200 p-3 rounded">
                            <p class="text-sm opacity-70">Dengan NIP Baru</p>
                            <p class="text-2xl font-bold">{{ $this->stats['with_nip'] }}</p>
                        </div>
                    </div>
                </x-mary-card>
            @endif
        </div>
    </div>

    @endvolt
</x-layouts.app>
