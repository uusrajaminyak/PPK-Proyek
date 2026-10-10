<?php

namespace Database\Seeders;

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
        $password = Hash::make('password');

        // 1. Seed Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@undip.ac.id'],
            [
                'name'            => 'Admin Utama',
                'password'        => $password,
                'role'            => 'admin',
                'status_akun'     => 'verified',
                'nomor_identitas' => '19850912201012',
                'kategori'        => 'Staf',
            ]
        );

        // 2. Seed Officers
        $mockupOfficers = [
            ['name' => 'Budi Santoso', 'id' => 'PTG-001', 'email' => 'budi.santoso@staff.undip.ac.id', 'status' => 'verified'],
            ['name' => 'Siti Aminah', 'id' => 'PTG-002', 'email' => 'siti.aminah@staff.undip.ac.id', 'status' => 'verified'],
            ['name' => 'Bambang Wijaya', 'id' => 'PTG-003', 'email' => 'bambang.w@staff.undip.ac.id', 'status' => 'verified'],
            ['name' => 'Dewi Lestari', 'id' => 'PTG-004', 'email' => 'dewi.lestari@staff.undip.ac.id', 'status' => 'suspended'],
            ['name' => 'Hendra Wijaya', 'id' => 'PTG-005', 'email' => 'hendra.w@staff.undip.ac.id', 'status' => 'verified'],
            ['name' => 'Rina Kartika', 'id' => 'PTG-006', 'email' => 'rina.k@staff.undip.ac.id', 'status' => 'verified'],
        ];

        foreach ($mockupOfficers as $off) {
            User::firstOrCreate(
                ['email' => $off['email']],
                [
                    'name'            => $off['name'],
                    'password'        => $password,
                    'role'            => 'petugas',
                    'status_akun'     => $off['status'],
                    'nomor_identitas' => $off['id'],
                    'kategori'        => 'Staf',
                ]
            );
        }

        // 3. Seed Standard Pengguna
        $pengguna = User::firstOrCreate(
            ['email' => 'ahmed@student.undip.ac.id'],
            [
                'name'            => 'Ahmed Fauzi (HMTI)',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '21120122140089',
                'kategori'        => 'Mahasiswa',
            ]
        );

        // 4. Run FacilitySeeder to populate 12 FSM Campus Facilities
        $this->call(FacilitySeeder::class);

        $facilityModels = Facility::all();

        // 5. Seed Reservations across all facilities (Spanning Sept & Oct 2026)
        $sept2026 = Carbon::create(2026, 9, 15, 9, 0, 0);
        $oct2026  = Carbon::create(2026, 10, 5, 10, 0, 0);

        foreach ($facilityModels as $index => $fac) {
            // September Reservations
            for ($i = 0; $i < 2; $i++) {
                Reservation::create([
                    'user_id'           => $pengguna->id,
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
                'user_id'           => $pengguna->id,
                'facility_id'       => $fac->id,
                'tujuan_penggunaan' => "Sewa Oktober - " . $fac->nama_fasilitas,
                'start_time'        => $oct2026->copy()->addDays($index),
                'end_time'          => $oct2026->copy()->addDays($index)->addHours(2),
                'status_reservasi'  => 'approved',
                'created_at'        => $oct2026,
            ]);
        }

        // 6. Seed Reports across facilities
        foreach ($facilityModels as $index => $fac) {
            Report::create([
                'reporter_id'      => $pengguna->id,
                'facility_id'      => $fac->id,
                'kategori_laporan' => 'Fasilitas umum / Perangkat',
                'deskripsi'        => "Laporan kendala atau pemeliharaan di " . $fac->nama_fasilitas,
                'status_laporan'   => $index % 2 == 0 ? 'selesai' : 'diproses',
                'created_at'       => $sept2026->copy()->addDays($index),
            ]);
        }
    }
}
