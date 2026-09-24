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
        ]);

        $petugas = User::create([
            'name' => 'Petugas Bambang',
            'email' => 'petugas@undip.ac.id',
            'password' => $password,
            'role' => 'petugas',
            'status_akun' => 'verified',
        ]);

        $pengguna = User::create([
            'name' => 'Ahmed Fauzi (HMTI)',
            'email' => 'ahmed@student.undip.ac.id',
            'password' => $password,
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        // Panggil FacilitySeeder (6 fasilitas terstandar)
        $this->call(FacilitySeeder::class);

        $aula = Facility::where('nama_fasilitas', 'Aula Utama Kampus')->first();
        $kelas101 = Facility::where('nama_fasilitas', 'Ruang Kelas 101')->first();
        $lab = Facility::where('nama_fasilitas', 'Laboratorium Komputer 1')->first();
        $ruangRapat = Facility::where('nama_fasilitas', 'Ruang Rapat Dekan')->first();
        $lapangan = Facility::where('nama_fasilitas', 'Lapangan Olahraga')->first();
        $kelas205 = Facility::where('nama_fasilitas', 'Ruang Kelas 205 (Renovasi)')->first();

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
            'tujuan_penggunaan' => 'Latihan Rutin UKM Olahraga',
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
                    'facility_id' => $lab->id,
                    'tujuan_penggunaan' => 'Praktikum Pemrograman',
                    'start_time' => $today->copy()->subDays($i)->setTime(13, 0),
                    'end_time' => $today->copy()->subDays($i)->setTime(15, 0),
                    'status_reservasi' => 'approved',
                    'created_at' => $today->copy()->subDays($i)->setTime(9, 0),
                ]);
            }
        }

        Report::create([
            'reporter_id' => $pengguna->id,
            'facility_id' => $kelas205->id, 
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'AC tidak dingin dan proyektor berkedip.',
            'foto_paths' => ['reports/ac-bocor1.jpg'],
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

        foreach ($testUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
