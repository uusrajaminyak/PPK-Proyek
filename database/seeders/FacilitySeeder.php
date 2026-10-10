<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'nama_fasilitas'   => 'Ruang Kelas E101',
                'tipe'             => 'Ruang Kelas',
                'lokasi'           => 'FSM - Gedung E - Lt. 1',
                'kapasitas'        => 60,
                'deskripsi'        => 'Ruang kelas standar perkuliahan FSM, dilengkapi proyektor HD, AC, dan whiteboard.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1571260899304-425dea5cfd5b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Aula Imam Bardjo',
                'tipe'             => 'Aula',
                'lokasi'           => 'Undip Pleburan',
                'kapasitas'        => 500,
                'deskripsi'        => 'Aula utama untuk seminar nasional, wisuda, dan pameran. Dilengkapi sound system profesional.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Lab Komputer Terpadu',
                'tipe'             => 'Laboratorium',
                'lokasi'           => 'FSM - Gedung Lab - Lt. 3',
                'kapasitas'        => 40,
                'deskripsi'        => 'Lab komputer praktikum Informatika. Sedang dalam pemeliharaan berkala jaringan.',
                'status_fasilitas' => 'in_repair',
                'foto_path'        => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Lapangan Basket FSM',
                'tipe'             => 'Lapangan',
                'lokasi'           => 'FSM - Area Outdoor',
                'kapasitas'        => 100,
                'deskripsi'        => 'Lapangan olahraga outdoor untuk basket dan voli mahasiswa.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Ruang Sidang Utama',
                'tipe'             => 'Ruang Rapat',
                'lokasi'           => 'Rektorat - Gedung Widya Puraya',
                'kapasitas'        => 30,
                'deskripsi'        => 'Ruang rapat eksekutif rektorat dengan fasilitas video conference terpadu.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Lab Kimia Analitik',
                'tipe'             => 'Laboratorium',
                'lokasi'           => 'FSM - Gedung Lab - Lt. 1',
                'kapasitas'        => 25,
                'deskripsi'        => 'Laboratorium kimia untuk praktikum dan penelitian instrumen analisis.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Aula Prof. Soedarto',
                'tipe'             => 'Aula',
                'lokasi'           => 'Undip Tembalang',
                'kapasitas'        => 1000,
                'deskripsi'        => 'Gedung serbaguna utama Universitas Diponegoro untuk wisuda dan konsumsi publik.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Ruang Kelas A201',
                'tipe'             => 'Ruang Kelas',
                'lokasi'           => 'FSM - Gedung A - Lt. 2',
                'kapasitas'        => 45,
                'deskripsi'        => 'Ruang perkuliahan terpadu dengan kursi lipat dan pendingin udara.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Lab Rekayasa Perangkat Lunak',
                'tipe'             => 'Laboratorium',
                'lokasi'           => 'FSM - Gedung C - Lt. 2',
                'kapasitas'        => 35,
                'deskripsi'        => 'Lab riset software engineering, AI, dan sistem terdistribusi.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Ruang Rapat Senat FSM',
                'tipe'             => 'Ruang Rapat',
                'lokasi'           => 'FSM - Dekanat Lt. 2',
                'kapasitas'        => 20,
                'deskripsi'        => 'Ruang rapat dekanat untuk koordinasi pimpinan dan rapat akademik.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Lapangan Futsal Outdoor',
                'tipe'             => 'Lapangan',
                'lokasi'           => 'FSM - Area Olahraga',
                'kapasitas'        => 80,
                'deskripsi'        => 'Lapangan futsal jaring terpadu dengan pencahayaan malam.',
                'status_fasilitas' => 'active',
                'foto_path'        => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama_fasilitas'   => 'Ruang Seminar FSM B102',
                'tipe'             => 'Ruang Kelas',
                'lokasi'           => 'FSM - Gedung B - Lt. 1',
                'kapasitas'        => 35,
                'deskripsi'        => 'Sedang dalam perbaikan sistem proyektor dan instalasi listrik.',
                'status_fasilitas' => 'in_repair',
                'foto_path'        => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['nama_fasilitas' => $facility['nama_fasilitas']],
                $facility
            );
        }
    }
}
