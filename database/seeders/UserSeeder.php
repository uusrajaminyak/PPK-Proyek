<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            // --- 1. ADMINS (2 Accounts) ---
            [
                'name'            => 'Admin Utama',
                'email'           => 'admin@undip.ac.id',
                'password'        => $password,
                'role'            => 'admin',
                'status_akun'     => 'verified',
                'nomor_identitas' => '19850912201012',
                'kategori'        => 'Staf',
                'no_telepon'      => '081122334455',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],
            [
                'name'            => 'Admin Sekunder FSM',
                'email'           => 'admin.fsm@undip.ac.id',
                'password'        => $password,
                'role'            => 'admin',
                'status_akun'     => 'verified',
                'nomor_identitas' => '198704152012011002',
                'kategori'        => 'Staf',
                'no_telepon'      => '081122334456',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],

            // --- 2. OFFICERS / PETUGAS (6 Accounts) ---
            [
                'name'            => 'Budi Santoso',
                'email'           => 'budi.santoso@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'petugas',
                'status_akun'     => 'verified',
                'nomor_identitas' => 'PTG-001',
                'kategori'        => 'Petugas',
                'no_telepon'      => '081234567801',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],
            [
                'name'            => 'Siti Aminah',
                'email'           => 'siti.aminah@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'petugas',
                'status_akun'     => 'verified',
                'nomor_identitas' => 'PTG-002',
                'kategori'        => 'Petugas',
                'no_telepon'      => '081234567802',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],
            [
                'name'            => 'Bambang Wijaya',
                'email'           => 'bambang.w@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'petugas',
                'status_akun'     => 'verified',
                'nomor_identitas' => 'PTG-003',
                'kategori'        => 'Petugas',
                'no_telepon'      => '081234567803',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],
            [
                'name'            => 'Dewi Lestari',
                'email'           => 'dewi.lestari@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'petugas',
                'status_akun'     => 'suspended',
                'nomor_identitas' => 'PTG-004',
                'kategori'        => 'Petugas',
                'no_telepon'      => '081234567804',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],
            [
                'name'            => 'Hendra Wijaya',
                'email'           => 'hendra.w@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'petugas',
                'status_akun'     => 'verified',
                'nomor_identitas' => 'PTG-005',
                'kategori'        => 'Petugas',
                'no_telepon'      => '081234567805',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],
            [
                'name'            => 'Rina Kartika',
                'email'           => 'rina.k@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'petugas',
                'status_akun'     => 'verified',
                'nomor_identitas' => 'PTG-006',
                'kategori'        => 'Petugas',
                'no_telepon'      => '081234567806',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => null,
            ],

            // --- 3. STANDARD USERS / PENGGUNA (8 Accounts) ---
            [
                'name'            => 'Ahmed Fauzi (HMTI)',
                'email'           => 'ahmed@student.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '21120122140089',
                'kategori'        => 'Mahasiswa',
                'no_telepon'      => '085711223344',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Informatika',
            ],
            [
                'name'            => 'Anisa Rahmawati',
                'email'           => 'anisa.rahma@student.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '24060122130045',
                'kategori'        => 'Mahasiswa',
                'no_telepon'      => '085711223345',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Informatika',
            ],
            [
                'name'            => 'Rizky Pratama',
                'email'           => 'rizky.p@student.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '24020122120012',
                'kategori'        => 'Mahasiswa',
                'no_telepon'      => '085711223346',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Biologi',
            ],
            [
                'name'            => 'Dian Sastrowardoyo',
                'email'           => 'dian.sastro@student.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '24030122140078',
                'kategori'        => 'Mahasiswa',
                'no_telepon'      => '085711223347',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Kimia',
            ],
            [
                'name'            => 'Dr. Eng. Adi Wibowo, S.Si., M.Kom.',
                'email'           => 'adiwibowo@lecturer.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '198203152008121001',
                'kategori'        => 'Dosen',
                'no_telepon'      => '081299887766',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Informatika',
            ],
            [
                'name'            => 'Prof. Dr. Widowati, S.Si., M.Si.',
                'email'           => 'widowati@lecturer.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '196902141994032001',
                'kategori'        => 'Dosen',
                'no_telepon'      => '081299887767',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Matematika',
            ],
            [
                'name'            => 'Eko Nurcahyo',
                'email'           => 'eko.n@staff.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '197811052005011003',
                'kategori'        => 'Staf',
                'no_telepon'      => '081344556677',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Dekanat FSM',
            ],
            [
                'name'            => 'Maya Indah',
                'email'           => 'maya.indah@student.undip.ac.id',
                'password'        => $password,
                'role'            => 'pengguna',
                'status_akun'     => 'verified',
                'nomor_identitas' => '24040122130022',
                'kategori'        => 'Mahasiswa',
                'no_telepon'      => '085711223348',
                'fakultas'        => 'Fakultas Sains dan Matematika (FSM)',
                'program_studi'   => 'Fisika',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
