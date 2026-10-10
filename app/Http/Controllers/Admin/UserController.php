<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users with dynamic search and filtering.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 1. Case-insensitive Search (Name, NIM/NIP/ID, or Email)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('nomor_identitas', 'ilike', "%{$search}%");
            });
        }

        // 2. Category / Role Filter
        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $kat = $request->kategori;
            if ($kat === 'Admin') {
                $query->where(function ($q) {
                    $q->where('kategori', 'Admin')->orWhere('role', 'admin');
                });
            } elseif ($kat === 'Petugas') {
                $query->where(function ($q) {
                    $q->where('kategori', 'Petugas')->orWhere('role', 'petugas');
                });
            } else {
                $query->where('kategori', $kat);
            }
        }

        // 3. Status Filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $status = $request->status;
            if ($status === 'verified') {
                $query->whereIn('status_akun', ['verified', 'active']);
            } elseif ($status === 'suspended') {
                $query->whereIn('status_akun', ['suspended', 'inactive', 'rejected']);
            } else {
                $query->where('status_akun', $status);
            }
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display detailed profile and activity history for a specific user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Eager load reservations with facility metadata
        $reservations = $user->reservations()->with('facility')->latest()->paginate(5, ['*'], 'reservations_page');

        // Eager load damage reports with facility metadata
        $reports = $user->reports()->with('facility')->latest()->paginate(5, ['*'], 'reports_page');

        return view('admin.users.show', compact('user', 'reservations', 'reports'));
    }

    /**
     * Update account status (Verification, Suspension, Rejection).
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'status_akun' => ['required', 'string', 'in:verified,pending,suspended,rejected'],
        ]);

        $user->update(['status_akun' => $validated['status_akun']]);

        $statusLabel = match ($validated['status_akun']) {
            'verified'  => 'diverifikasi dan diaktifkan',
            'suspended' => 'dinonaktifkan (Suspended)',
            'rejected'  => 'ditolak',
            default     => 'diubah',
        };

        return redirect()->back()->with('success', "Status akun '{$user->name}' berhasil {$statusLabel}.");
    }

    /**
     * Reset user password and return new temporary credential.
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $newPassword = Str::random(10);
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()->with('success', "Password akun '{$user->name}' berhasil di-reset. Password baru: {$newPassword}");
    }

    public function create()
    {
        $kategoriOptions = ['Mahasiswa', 'Dosen', 'Petugas', 'Admin'];

        $fakultasOptions = [
            'Fakultas Sains dan Matematika (FSM)',
            'Fakultas Teknik (FT)',
            'Fakultas Kedokteran (FK)',
            'Fakultas Ekonomika dan Bisnis (FEB)',
            'Fakultas Hukum (FH)',
        ];

        $prodiOptions = [
            'Informatika',
            'Sistem Informasi',
            'Biologi',
            'Kimia',
            'Fisika',
            'Matematika',
            'Statistika',
            'Teknik Sipil',
            'Kedokteran Umum',
        ];

        return view('admin.users.create', compact('kategoriOptions', 'fakultasOptions', 'prodiOptions'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $role = match ($validated['kategori']) {
            'Admin'   => 'admin',
            'Petugas' => 'petugas',
            default   => 'pengguna',
        };

        $generatedPassword = Str::random(10);

        $user = User::create([
            'name'            => $validated['nama'],
            'nomor_identitas' => $validated['nomor_identitas'],
            'kategori'        => $validated['kategori'],
            'email'           => $validated['email'],
            'no_telepon'      => $validated['no_telepon'],
            'fakultas'        => $validated['fakultas'] ?? 'Fakultas Sains dan Matematika (FSM)',
            'program_studi'   => $validated['program_studi'] ?? null,
            'password'        => Hash::make($generatedPassword),
            'status_akun'     => $request->has('status_aktif') ? 'verified' : 'pending',
            'role'            => $role,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun pengguna '{$user->name}' berhasil ditambahkan. Password sementara: {$generatedPassword}");
    }
}
