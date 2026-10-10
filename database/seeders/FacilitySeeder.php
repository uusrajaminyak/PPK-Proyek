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
                'nama_fasilitas' => 'Aula Utama Kampus',
                'tipe' => 'Aula',
                'lokasi' => 'Gedung A, Lt. 1',
                'kapasitas' => 500,
                'deskripsi' => 'Aula serbaguna untuk acara besar, wisuda, seminar nasional. Dilengkapi sound system, proyektor, dan AC central.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Ruang Kelas 101',
                'tipe' => 'Ruang Kelas',
                'lokasi' => 'Gedung B, Lt. 1',
                'kapasitas' => 40,
                'deskripsi' => 'Ruang kelas standar dengan whiteboard, proyektor, dan AC. Cocok untuk perkuliahan reguler.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Laboratorium Komputer 1',
                'tipe' => 'Laboratorium',
                'lokasi' => 'Gedung C, Lt. 2',
                'kapasitas' => 30,
                'deskripsi' => 'Lab komputer dengan 30 unit PC spec tinggi, licensi software development, jaringan LAN 1Gbps.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Ruang Rapat Dekan',
                'tipe' => 'Ruang Rapat',
                'lokasi' => 'Gedung A, Lt. 3',
                'kapasitas' => 20,
                'deskripsi' => 'Ruang rapat eksekutif dengan meja oval, video conference system, dan pantry mini.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Lapangan Olahraga',
                'tipe' => 'Lapangan',
                'lokasi' => 'Area Outdoor Barat',
                'kapasitas' => 100,
                'deskripsi' => 'Lapangan serbaguna (futsal, basket,voli) dengan lampu sorot standar turnamen dan tribun penonton.',
                'status_fasilitas' => 'active',
            ],
            [
                'nama_fasilitas' => 'Ruang Kelas 205 (Renovasi)',
                'tipe' => 'Ruang Kelas',
                'lokasi' => 'Gedung B, Lt. 2',
                'kapasitas' => 35,
                'deskripsi' => 'Sedang dalam perbaikan AC dan penggantian proyektor. Diperkirakan selesai 2 minggu.',
                'status_fasilitas' => 'in_repair',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}