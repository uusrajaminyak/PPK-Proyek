<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 3 User Dummy
        $users = [
            [
                'name' => 'Admin Kampus',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status_akun' => 'verified',
            ],
            [
                'name' => 'Petugas Fasilitas',
                'email' => 'petugas@test.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'status_akun' => 'verified',
            ],
            [
                'name' => 'Mahasiswa User',
                'email' => 'user@test.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
                'status_akun' => 'verified',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Panggil FacilitySeeder
        $this->call(FacilitySeeder::class);
    }
}