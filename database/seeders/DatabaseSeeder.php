<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
        }
    }
}
