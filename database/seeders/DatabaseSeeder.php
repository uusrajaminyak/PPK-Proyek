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

        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@undip.ac.id',
            'password' => $password,
            'role' => 'admin',
            'status_akun' => 'verified',
            'nomor_identitas' => '19850912201012',
            'kategori' => 'Staf', 
        ]);

        $mockupOfficers = [
            ['name' => 'Budi Santoso', 'id' => 'PTG-001', 'email' => 'budi.santoso@staff.undip.ac.id', 'status' => 'verified', 'date' => '2026-01-15 10:00:00'],
            ['name' => 'Siti Aminah', 'id' => 'PTG-002', 'email' => 'siti.aminah@staff.undip.ac.id', 'status' => 'verified', 'date' => '2026-01-18 10:00:00'],
            ['name' => 'Bambang Wijaya', 'id' => 'PTG-003', 'email' => 'bambang.w@staff.undip.ac.id', 'status' => 'verified', 'date' => '2026-01-20 10:00:00'],
            ['name' => 'Dewi Lestari', 'id' => 'PTG-004', 'email' => 'dewi.lestari@staff.undip.ac.id', 'status' => 'suspended', 'date' => '2026-02-02 10:00:00'],
            ['name' => 'Hendra Wijaya', 'id' => 'PTG-005', 'email' => 'hendra.w@staff.undip.ac.id', 'status' => 'verified', 'date' => '2026-02-10 10:00:00'],
            ['name' => 'Rina Kartika', 'id' => 'PTG-006', 'email' => 'rina.k@staff.undip.ac.id', 'status' => 'verified', 'date' => '2026-02-14 10:00:00'],
        ];

        foreach ($mockupOfficers as $off) {
            User::create([
                'name' => $off['name'],
                'email' => $off['email'],
                'password' => $password,
                'role' => 'petugas',
                'status_akun' => $off['status'],
                'nomor_identitas' => $off['id'],
                'kategori' => 'Staf',
                'created_at' => $off['date'], // Hardcoded to match mockup exactly
                'updated_at' => $off['date'],
            ]);
        }

        $pengguna = User::create([
            'name' => 'Ahmed Fauzi (HMTI)',
            'email' => 'ahmed@student.undip.ac.id',
            'password' => $password,
            'role' => 'pengguna',
            'status_akun' => 'verified',
            'nomor_identitas' => '21120122140089',
            'kategori' => 'Mahasiswa',
        ]);

        $kelas = Facility::create([
            'nama_fasilitas' => 'Ruang Kelas E101',
            'tipe' => 'Ruang Kelas',
            'lokasi' => 'FSM - Gedung E - Lt. 1',
            'kapasitas' => 60,
            'deskripsi' => 'Ruang kelas standar.',
            'status_fasilitas' => 'active',
            'foto_path' => 'https://images.unsplash.com/photo-1571260899304-425dea5cfd5b?auto=format&fit=crop&w=800&q=80',
        ]);

        $aula = Facility::create([
            'nama_fasilitas' => 'Aula Imam Bardjo',
            'tipe' => 'Aula Serbaguna',
            'lokasi' => 'Undip Pleburan',
            'kapasitas' => 500,
            'deskripsi' => 'Aula utama untuk seminar.',
            'status_fasilitas' => 'active',
            'foto_path' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
        ]);

        $labKomputer = Facility::create([
            'nama_fasilitas' => 'Lab Komputer Terpadu',
            'tipe' => 'Laboratorium',
            'lokasi' => 'FSM - Gedung Lab - Lt. 3',
            'kapasitas' => 40,
            'deskripsi' => 'Lab komputer dengan spesifikasi tinggi.',
            'status_fasilitas' => 'in_repair',
            'foto_path' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80',
        ]);

        $lapangan = Facility::create([
            'nama_fasilitas' => 'Lapangan Basket FSM',
            'tipe' => 'Lapangan Olahraga',
            'lokasi' => 'FSM - Area Outdoor',
            'kapasitas' => 0, 
            'deskripsi' => 'Lapangan basket outdoor utama.',
            'status_fasilitas' => 'active',
            'foto_path' => 'https://images.unsplash.com/photo-1504450758481-7338eba7524a?auto=format&fit=crop&w=800&q=80',
        ]);

        $ruangRapat = Facility::create([
            'nama_fasilitas' => 'Ruang Sidang Utama',
            'tipe' => 'Ruang Rapat',
            'lokasi' => 'Rektorat - Gedung Widya Puraya',
            'kapasitas' => 30,
            'deskripsi' => 'Ruang rapat eksklusif rektorat.',
            'status_fasilitas' => 'inactive',
            'foto_path' => 'https://images.unsplash.com/photo-1577415124269-311451f28b3a?auto=format&fit=crop&w=800&q=80',
        ]);

        $labKimia = Facility::create([
            'nama_fasilitas' => 'Lab Kimia Analitik',
            'tipe' => 'Laboratorium',
            'lokasi' => 'FSM - Gedung Lab - Lt. 1',
            'kapasitas' => 25,
            'deskripsi' => 'Laboratorium kimia untuk praktikum.',
            'status_fasilitas' => 'active',
            'foto_path' => 'https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?auto=format&fit=crop&w=800&q=80',
        ]);

        $today = Carbon::today();

        Reservation::create([
            'user_id' => $pengguna->id,
            'facility_id' => $aula->id,
            'tujuan_penggunaan' => 'Seminar Teknologi Informasi',
            'start_time' => $today->copy()->setTime(9, 0),
            'end_time' => $today->copy()->setTime(12, 0),
            'status_reservasi' => 'approved',
        ]);

        Reservation::create([
            'user_id' => $pengguna->id,
            'facility_id' => $lapangan->id,
            'tujuan_penggunaan' => 'Latihan Rutin UKM Basket',
            'start_time' => $today->copy()->addDay()->setTime(15, 0),
            'end_time' => $today->copy()->addDay()->setTime(17, 0),
            'status_reservasi' => 'pending',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Reservation::create([
                'user_id' => $pengguna->id,
                'facility_id' => $aula->id,
                'tujuan_penggunaan' => 'Kegiatan Mahasiswa Hari - ' . $i,
                'start_time' => $today->copy()->subDays($i)->setTime(10, 0),
                'end_time' => $today->copy()->subDays($i)->setTime(12, 0),
                'status_reservasi' => 'approved',
                'created_at' => $today->copy()->subDays($i)->setTime(8, 0),
            ]);

            if ($i % 2 == 0) {
                Reservation::create([
                    'user_id' => $pengguna->id,
                    'facility_id' => $labKomputer->id,
                    'tujuan_penggunaan' => 'Praktikum Susulan',
                    'start_time' => $today->copy()->subDays($i)->setTime(13, 0),
                    'end_time' => $today->copy()->subDays($i)->setTime(15, 0),
                    'status_reservasi' => 'approved',
                    'created_at' => $today->copy()->subDays($i)->setTime(9, 0),
                ]);
            }
        }

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $labKomputer->id, 
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'AC di ruang lab bocor dan menetes ke komputer.',
            'foto_paths' => ['reports/ac-bocor1.jpg', 'reports/ac-bocor2.jpg'],
            'status_laporan' => 'diproses',
        ]);

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $aula->id,
            'kategori_laporan' => 'Fasilitas Umum',
            'deskripsi' => 'Proyektor utama mati total.',
            'foto_paths' => ['reports/proyektor-mati.jpg'],
            'status_laporan' => 'selesai',
            'catatan_resolusi' => 'Kabel power telah diganti oleh teknisi.',
        ]);

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $lapangan->id,
            'kategori_laporan' => 'Fasilitas Umum',
            'deskripsi' => 'Ring basket bengkok.',
            'foto_paths' => [],
            'status_laporan' => 'baru',
        ]);
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