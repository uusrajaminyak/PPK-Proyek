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

        $petugas = User::create([
            'name' => 'Petugas Bambang',
            'email' => 'petugas@undip.ac.id',
            'password' => $password,
            'role' => 'petugas',
            'status_akun' => 'verified',
            'nomor_identitas' => '19900101201501',
            'kategori' => 'Staf',
        ]);

        $pengguna = User::create([
            'name' => 'Ahmed Fauzi (HMTI)',
            'email' => 'ahmed@student.undip.ac.id',
            'password' => $password,
            'role' => 'pengguna',
            'status_akun' => 'verified',
            'nomor_identitas' => '21120122140089',
            'kategori' => 'Mahasiswa',
        ]);

        $aula = Facility::create([
            'nama_fasilitas' => 'Aula Imam Bardjo',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung A, Lantai 1',
            'kapasitas' => 200,
            'deskripsi' => 'Aula utama untuk seminar dan acara besar.',
            'status_fasilitas' => 'active',
        ]);

        $lab = Facility::create([
            'nama_fasilitas' => 'Lab Komputer FSM',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung C, Lantai 2',
            'kapasitas' => 40,
            'deskripsi' => 'Lab komputer dengan spesifikasi tinggi.',
            'status_fasilitas' => 'in_repair',
        ]);

        $lapangan = Facility::create([
            'nama_fasilitas' => 'Lapangan Basket',
            'tipe' => 'Lapangan',
            'lokasi' => 'Area Olahraga Timur',
            'kapasitas' => 50,
            'deskripsi' => 'Lapangan basket outdoor.',
            'status_fasilitas' => 'active',
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
                    'facility_id' => $lab->id,
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
            'facility_id' => $lab->id,
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
    }
}
