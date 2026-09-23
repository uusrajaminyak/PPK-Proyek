<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter (Name, NIM/NIP, or Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }

        // Category/Role filter
        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status_akun', $request->status);
        }

        // Paginate items per page 
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $kategoriOptions = ['Mahasiswa', 'Dosen', 'Staf', 'Petugas', 'Admin'];
        
        $fakultasOptions = [
            'Fakultas Sains dan Matematika (FSM)',
            'Fakultas Teknik (FT)',
            'Fakultas Kedokteran (FK)',
            'Fakultas Ekonomika dan Bisnis (FEB)'
        ];
        
        $prodiOptions = [
            'Informatika',
            'Sistem Informasi',
            'Teknik Sipil',
            'Manajemen',
            'Kedokteran Umum'
        ];

        return view('admin.users.create', compact('kategoriOptions', 'fakultasOptions', 'prodiOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nomor_identitas' => ['required', 'string', 'max:50', 'unique:users'],
            'kategori' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'fakultas' => ['nullable', 'string'],
            'program_studi' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = 'pengguna';
        if (in_array($validated['kategori'], ['Admin', 'Petugas'])) {
            $role = strtolower($validated['kategori']);
        }

        User::create([
            'name' => $validated['nama'], 
            'nomor_identitas' => $validated['nomor_identitas'],
            'kategori' => $validated['kategori'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'],
            'fakultas' => $validated['fakultas'],
            'program_studi' => $validated['program_studi'],
            'password' => Hash::make($validated['password']),
            'status_akun' => $request->has('status_aktif') ? 'verified' : 'pending',
            'role' => $role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }
}