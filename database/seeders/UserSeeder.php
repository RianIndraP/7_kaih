<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users for testing
        \App\Models\User::create([
            'name' => 'Andi Saputra',
            'nisn' => '00823117233',
            'email' => 'andi@7kaih.sch.id',
            'password' => Hash::make('password123'),
            'birth_date' => '2005-03-15',
        ]);

        \App\Models\User::create([
            'name' => 'Admin System',
            'username' => 'admin',
            'email' => 'admin@7kaih.sch.id',
            'password' => Hash::make('admin123'),
            'birth_date' => '1990-01-01',
        ]);

        \App\Models\User::create([
            'name' => 'Guru Budi',
            'nip' => '1234567890123456',
            'email' => 'budi@7kaih.sch.id',
            'password' => Hash::make('guru123'),
            'birth_date' => '1985-06-20',
        ]);
    }
}
