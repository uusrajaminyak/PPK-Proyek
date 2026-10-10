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
                'name' => 'Admin Utama',
                'password' => $password,
                'role' => 'admin',
                'status_akun' => 'verified',
                'nomor_identitas' => '19850912201012',
                'kategori' => 'Staf',
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
                    'name' => $off['name'],
                    'password' => $password,
                    'role' => 'petugas',
                    'status_akun' => $off['status'],
                    'nomor_identitas' => $off['id'],
                    'kategori' => 'Staf',
                ]
            );
        }

        // 3. Seed Standard Pengguna
        $pengguna = User::firstOrCreate(
            ['email' => 'ahmed@student.undip.ac.id'],
            [
                'name' => 'Ahmed Fauzi (HMTI)',
                'password' => $password,
                'role' => 'pengguna',
                'status_akun' => 'verified',
                'nomor_identitas' => '21120122140089',
                'kategori' => 'Mahasiswa',
            ]
        );

        // 4. Seed Facilities
        $facilities = [
            [
                'nama_fasilitas' => 'Ruang Kelas E101',
                'tipe' => 'Ruang Kelas',
                'lokasi' => 'FSM - Gedung E - Lt. 1',
                'kapasitas' => 60,
                'deskripsi' => 'Ruang kelas standar perkuliahan FSM.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Aula Imam Bardjo',
                'tipe' => 'Aula Serbaguna',
                'lokasi' => 'Undip Pleburan',
                'kapasitas' => 500,
                'deskripsi' => 'Aula utama untuk seminar dan wisuda.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Lab Komputer Terpadu',
                'tipe' => 'Laboratorium',
                'lokasi' => 'FSM - Gedung Lab - Lt. 3',
                'kapasitas' => 40,
                'deskripsi' => 'Lab komputer praktikum mahasiswa.',
                'status_fasilitas' => 'in_repair',
            ],
            [
                'nama_fasilitas' => 'Lapangan Basket FSM',
                'tipe' => 'Lapangan Olahraga',
                'lokasi' => 'FSM - Area Outdoor',
                'kapasitas' => 100,
                'deskripsi' => 'Lapangan olahraga outdoor.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Ruang Sidang Utama',
                'tipe' => 'Ruang Rapat',
                'lokasi' => 'Rektorat - Gedung Widya Puraya',
                'kapasitas' => 30,
                'deskripsi' => 'Ruang rapat eksekutif.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Lab Kimia Analitik',
                'tipe' => 'Laboratorium',
                'lokasi' => 'FSM - Gedung Lab - Lt. 1',
                'kapasitas' => 25,
                'deskripsi' => 'Laboratorium sains terpadu.',
                'status_fasilitas' => 'active',
            ],
        ];

        $facilityModels = [];
        foreach ($facilities as $fac) {
            $facilityModels[] = Facility::firstOrCreate(
                ['nama_fasilitas' => $fac['nama_fasilitas']],
                $fac
            );
        }

        // 5. Seed Reservations (Spanning Sept & Oct 2026)
        $sept2026 = Carbon::create(2026, 9, 15, 9, 0, 0);
        $oct2026  = Carbon::create(2026, 10, 5, 10, 0, 0);

        // September Reservations
        $reservationCountsSept = [14, 10, 8, 6, 4, 2];
        foreach ($facilityModels as $index => $fac) {
            $count = $reservationCountsSept[$index] ?? 2;
            for ($i = 0; $i < $count; $i++) {
                Reservation::create([
                    'user_id' => $pengguna->id,
                    'facility_id' => $fac->id,
                    'tujuan_penggunaan' => "Kegiatan Akademik & Ormawa #{$i}",
                    'start_time' => $sept2026->copy()->addDays($i)->setTime(8 + ($i % 8), 0),
                    'end_time' => $sept2026->copy()->addDays($i)->setTime(10 + ($i % 8), 0),
                    'status_reservasi' => 'approved',
                    'created_at' => $sept2026->copy()->subDays(2),
                ]);
            }
        }

        // October Reservations
        foreach ($facilityModels as $index => $fac) {
            Reservation::create([
                'user_id' => $pengguna->id,
                'facility_id' => $fac->id,
                'tujuan_penggunaan' => "Sewa Oktober - " . $fac->nama_fasilitas,
                'start_time' => $oct2026->copy()->addDays($index),
                'end_time' => $oct2026->copy()->addDays($index)->addHours(2),
                'status_reservasi' => 'approved',
                'created_at' => $oct2026,
            ]);
        }

        // 6. Seed Reports (Spanning Sept & Oct 2026)
        $reportCountsSept = [12, 9, 7, 5, 3, 1];
        foreach ($facilityModels as $index => $fac) {
            $count = $reportCountsSept[$index] ?? 1;
            for ($i = 0; $i < $count; $i++) {
                Report::create([
                    'reporter_id' => $pengguna->id,
                    'facility_id' => $fac->id,
                    'kategori_laporan' => 'Fasilitas umum / Perangkat',
                    'deskripsi' => "Laporan kendala fasilitas #{$i}",
                    'status_laporan' => $i % 2 == 0 ? 'selesai' : 'diproses',
                    'created_at' => $sept2026->copy()->addDays($i),
                ]);
            }
        }
    }
}
