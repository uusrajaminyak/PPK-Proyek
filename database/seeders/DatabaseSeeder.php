<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users (16 Accounts across all roles)
        $this->call(UserSeeder::class);

        // 2. Seed Facilities (12 FSM Campus Facilities)
        $this->call(FacilitySeeder::class);

        $penggunaList = User::where('role', 'pengguna')->get();
        $facilityModels = Facility::all();

        for ($i = 1; $i <= 5; $i++) {
            Reservation::create([
                'user_id' => $pengguna->id,
                'facility_id' => $aula->id,
                'tujuan_penggunaan' => 'Kegiatan Mahasiswa Hari - '.$i,
                'start_time' => $today->copy()->subDays($i)->setTime(10, 0),
                'end_time' => $today->copy()->subDays($i)->setTime(12, 0),
                'status_reservasi' => 'approved',
                'created_at' => $today->copy()->subDays($i)->setTime(8, 0),
            ]);
        // 5. Seed Reservations across all facilities (Spanning Sept & Oct 2026)
        $sept2026 = Carbon::create(2026, 9, 15, 9, 0, 0);
        $oct2026  = Carbon::create(2026, 10, 5, 10, 0, 0);

            // 3. Seed Reservations across users and facilities
            foreach ($facilityModels as $index => $fac) {
                $user = $penggunaList[$index % $penggunaList->count()];

                // September Reservations
                for ($i = 0; $i < 2; $i++) {
                    Reservation::create([
                        'user_id'           => $user->id,
                        'facility_id'       => $fac->id,
                        'tujuan_penggunaan' => "Kegiatan Akademik & Ormawa #{$i} - {$fac->nama_fasilitas}",
                        'start_time'        => $sept2026->copy()->addDays($i)->setTime(8 + ($i % 8), 0),
                        'end_time'          => $sept2026->copy()->addDays($i)->setTime(10 + ($i % 8), 0),
                        'status_reservasi'  => 'approved',
                        'created_at'        => $sept2026->copy()->subDays(2),
                    ]);
                }

                // October Reservations
                Reservation::create([
                    'user_id'           => $user->id,
                    'facility_id'       => $fac->id,
                    'tujuan_penggunaan' => "Sewa Oktober - " . $fac->nama_fasilitas,
                    'start_time'        => $oct2026->copy()->addDays($index),
                    'end_time'          => $oct2026->copy()->addDays($index)->addHours(2),
                    'status_reservasi'  => 'approved',
                    'created_at'        => $oct2026,
                ]);
            }
        }

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $kelas205->id,
            'kategori_laporan' => 'Kerusakan/Kendala Fasilitas',
            'deskripsi' => 'AC tidak dingin dan proyektor berkedip.',
            'foto_paths' => ['reports/ac-bocor1.jpg'],
            'status_laporan' => 'diproses',
        ]);

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $aula->id,
            'kategori_laporan' => 'Kerusakan/Kendala Fasilitas',
            'deskripsi' => 'Proyektor utama mati total.',
            'foto_paths' => ['reports/proyektor-mati.jpg'],
            'status_laporan' => 'selesai',
            'catatan_resolusi' => 'Kabel power telah diganti oleh teknisi.',
        ]);

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $lapangan->id,
            'kategori_laporan' => 'Kerusakan/Kendala Fasilitas',
            'deskripsi' => 'Ring basket bengkok dan jaring terlepas.',
            'foto_paths' => [],
            'status_laporan' => 'baru',
        ]);

        // Akun test serbaguna
        $testUsers = [
            [
                'name' => 'Admin Kampus',
                'email' => 'admin@test.com',
                'password' => $password,
                'role' => 'admin',
                'status_akun' => 'verified',
            ],
            [
                'name' => 'Petugas Fasilitas',
                'email' => 'petugas@test.com',
                'password' => $password,
                'role' => 'petugas',
                'status_akun' => 'verified',
            ],
            [
                'name' => 'Mahasiswa User',
                'email' => 'user@test.com',
                'password' => $password,
                'role' => 'pengguna',
                'status_akun' => 'verified',
            ],
        ];

            // 4. Seed Reports across facilities
            foreach ($facilityModels as $index => $fac) {
                $user = $penggunaList[$index % $penggunaList->count()];

                Report::create([
                    'reporter_id'      => $user->id,
                    'facility_id'      => $fac->id,
                    'kategori_laporan' => 'Fasilitas umum / Perangkat',
                    'deskripsi'        => "Laporan kendala atau pemeliharaan di " . $fac->nama_fasilitas,
                    'status_laporan'   => $index % 2 == 0 ? 'selesai' : 'diproses',
                    'created_at'       => $sept2026->copy()->addDays($index),
                ]);
            }
        }
    }
}
