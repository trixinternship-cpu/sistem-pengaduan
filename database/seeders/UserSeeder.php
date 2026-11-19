<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT',
            'phone' => '081234567890',
        ]);

        User::create([
            'name' => 'Budi Teknisi',
            'email' => 'it@example.com',
            'password' => Hash::make('password'),
            'role' => 'it',
            'department' => 'IT',
            'phone' => '081234567891',
        ]);

        User::create([
            'name' => 'Andi Teknisi',
            'email' => 'it2@example.com',
            'password' => Hash::make('password'),
            'role' => 'it',
            'department' => 'IT',
            'phone' => '081234567892',
        ]);

        User::create([
            'name' => 'Siti Karyawan',
            'email' => 'pelapor@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'department' => 'Finance',
            'phone' => '081234567893',
        ]);

        User::create([
            'name' => 'Dedi Karyawan',
            'email' => 'pelapor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelapor',
            'department' => 'HRD',
            'phone' => '081234567894',
        ]);
    }
}