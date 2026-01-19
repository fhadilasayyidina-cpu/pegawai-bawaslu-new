
<?php
    use App\Services\User\UserService;
    use App\Enums\Role;


    $tableHeader = [
        'key' => 'id', 'label' => '#',
        'key' => 'name', 'label' => 'nama'
    ];
?>


<x-layouts.app title="Manajemen User">
    @volt
        <?php 
        use Livewire\Volt\Component;
         $myBreadcrumbs = [
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Server Monitoring', 'link' => '/servers'],
        ['label' => 'Detail Server', 'link' => '#'],
    ];
            new class extends Component{
                
                // public UserService $userService = new UserService();
                public ?string $nameOrEmail = null;
                //role seharunsya bernilai sesuai enums namun karena kebtuhan null maka pake type string
                public ?string $role = null;
                public ?string $accessScope = null; 

               
                public function autoSearch()
                {
                    // app() akan mengambil instance UserService secara otomatis
                    return app(UserService::class)->getAllUser(
                        $this->nameOrEmail, 
                        $this->role, 
                        $this->accessScope
                    );
                }
                

            }
        ?>
        <div>
            <!-- Header page -->
            <x-header-page title="Status Server Utama" :breadcrumbs="$myBreadcrumbs">
                <x-slot:actions>
                    <x-mary-button label='Tambah User' link='admin/users/create'/>
                </x-slot:actions>
            </x-header-page>

            <!-- Table Users -->
            <x-mary-table :headers="$tableHeaders" :rows="$this->autoSearch()" striped @row-click="alert($event.detail.name)" />




        </div>       
    @endvolt


 

</x-layouts.app>

