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
        $this->call([
            UserSeeder::class,
            FacilitySeeder::class,
        ]);

        $users = User::query()->where('role', 'pengguna')->get();
        $facilities = Facility::query()->orderBy('id')->get();

        if ($users->isEmpty() || $facilities->isEmpty()) {
            return;
        }

        $today = Carbon::today();
        $categories = [
            'Kerusakan/Kendala Fasilitas',
            'Kebersihan Fasilitas',
            'Gangguan Internet',
        ];
        $reportStatuses = ['baru', 'diproses', 'selesai', 'ditolak'];

        foreach ($facilities as $index => $facility) {
            $user = $users[$index % $users->count()];
            $reservationCases = [
                [
                    'day_offset' => $index + 1,
                    'status' => 'pending',
                    'purpose' => 'Kegiatan menunggu persetujuan - '.$facility->nama_fasilitas,
                    'cancellation_reason' => null,
                ],
                [
                    'day_offset' => $index + 15,
                    'status' => 'approved',
                    'purpose' => 'Kegiatan terjadwal - '.$facility->nama_fasilitas,
                    'cancellation_reason' => null,
                ],
                [
                    'day_offset' => -($index + 1),
                    'status' => 'approved',
                    'purpose' => 'Kegiatan selesai - '.$facility->nama_fasilitas,
                    'cancellation_reason' => null,
                ],
                [
                    'day_offset' => -($index + 8),
                    'status' => 'cancelled_by_user',
                    'purpose' => 'Kegiatan dibatalkan pengguna - '.$facility->nama_fasilitas,
                    'cancellation_reason' => 'Jadwal kegiatan berubah.',
                ],
                [
                    'day_offset' => -($index + 15),
                    'status' => 'cancelled_by_officer',
                    'purpose' => 'Kegiatan ditolak petugas - '.$facility->nama_fasilitas,
                    'cancellation_reason' => 'Fasilitas sedang tidak tersedia.',
                ],
            ];

            foreach ($reservationCases as $reservationCase) {
                $startTime = $today->copy()->addDays($reservationCase['day_offset'])->setTime(9, 0);

                Reservation::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'facility_id' => $facility->id,
                        'start_time' => $startTime,
                    ],
                    [
                        'tujuan_penggunaan' => $reservationCase['purpose'],
                        'end_time' => $startTime->copy()->addHours(2),
                        'status_reservasi' => $reservationCase['status'],
                        'alasan_pembatalan' => $reservationCase['cancellation_reason'],
                    ],
                );
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

            $reportCategory = $categories[$index % count($categories)];
            $reportStatus = $reportStatuses[$index % count($reportStatuses)];

            Report::firstOrCreate(
                [
                    'reporter_id' => $user->id,
                    'facility_id' => $facility->id,
                    'deskripsi' => 'Laporan kendala fasilitas - '.$facility->nama_fasilitas,
                ],
                [
                    'kategori_laporan' => $reportCategory,
                    'status_laporan' => $reportStatus,
                    'foto_paths' => [],
                    'catatan_resolusi' => in_array($reportStatus, ['selesai', 'ditolak'], true)
                        ? 'Tindak lanjut laporan telah dicatat untuk keperluan pengujian.'
                        : null,
                ],
            );
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
