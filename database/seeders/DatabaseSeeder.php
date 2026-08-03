<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {


        // 1. Admin Pusat
        User::create([
            'name' => 'Super Admin Bawaslu',
            'email' => 'admin@gmail.com',
            'password' => 'password', // Otomatis di-hash oleh Model Casts
            'role' => Role::ADMIN,
            'access_scope' => null,
        ]);

        // 2. Operator Wilayah A
        User::create([
            'name' => 'Operator Makassar',
            'email' => 'operator_mks@gmail.com',
            'password' => 'password',
            'role' => Role::OPERATOR,
            'access_scope' => 'Makassar',
        ]);

        // 3. Operator Wilayah B
        User::create([
            'name' => 'Operator Jakarta',
            'email' => 'operator_jkt@gmail.com',
            'password' => 'password',
            'role' => Role::OPERATOR,
            'access_scope' => 'Jakarta',
        ]);

        // 4. Pegawai Biasa
        User::create([
            'name' => 'Budi Pegawai',
            'email' => 'pegawai@gmail.com',
            'password' => 'password',
            'role' => Role::PEGAWAI,
            'access_scope' => null,
        ]);

        $this->call(PegawaiSeeder::class);
    }
}
