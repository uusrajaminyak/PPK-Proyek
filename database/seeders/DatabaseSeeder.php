<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

        if ($penggunaList->isNotEmpty() && $facilityModels->isNotEmpty()) {
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
