<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('nomor_identitas', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status_akun', $request->status);
        }

        $officers = $query->orderBy('nomor_identitas')->paginate(10)->withQueryString();

        return view('admin.officers.index', compact('officers'));
    }

    public function show($id)
    {
        $officer = User::where('role', 'petugas')->findOrFail($id);

        // Fetch activity reports and reservations
        $reports = Report::with(['facility', 'reporter'])
            ->latest()
            ->paginate(5, ['*'], 'reports_page');

        $reservations = Reservation::with(['facility', 'user'])
            ->latest()
            ->paginate(5, ['*'], 'reservations_page');

        return view('admin.officers.show', compact('officer', 'reports', 'reservations'));
    }

    public function updateStatus(Request $request, $id)
    {
        $officer = User::where('role', 'petugas')->findOrFail($id);

        $validated = $request->validate([
            'status_akun' => ['required', 'string', 'in:verified,pending,suspended,rejected'],
        ]);

        $officer->update(['status_akun' => $validated['status_akun']]);

        $statusLabel = match ($validated['status_akun']) {
            'verified'  => 'diaktifkan',
            'suspended' => 'dinonaktifkan (Suspended)',
            default     => 'diubah',
        };

        return redirect()->back()->with('success', "Status akun petugas '{$officer->name}' berhasil {$statusLabel}.");
    }

    public function resetPassword($id)
    {
        $officer = User::where('role', 'petugas')->findOrFail($id);

        $newPassword = Str::random(10);
        $officer->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()->with('success', "Password akun petugas '{$officer->name}' berhasil di-reset. Password baru: {$newPassword}");
    }

    public function create()
    {
        $jabatanOptions = [
            'Staf Penanggung Jawab Lapangan',
            'Teknisi Laboratorium',
            'Koordinator Fasilitas',
            'Kepala Tata Usaha'
        ];

        $unitKerjaOptions = [
            'Fakultas Sains dan Matematika (FSM)',
            'Fakultas Teknik (FT)',
            'Fakultas Kedokteran (FK)',
            'Biro Umum dan Keuangan'
        ];

        return view('admin.officers.create', compact('jabatanOptions', 'unitKerjaOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'jabatan'    => ['nullable', 'string'],
            'unit_kerja' => ['nullable', 'string'],
        ]);

        $lastOfficer = User::where('role', 'petugas')->orderBy('id', 'desc')->first();
        $nextIdNumber = 1;
        if ($lastOfficer && preg_match('/PTG-(\d+)/', $lastOfficer->nomor_identitas, $matches)) {
            $nextIdNumber = intval($matches[1]) + 1;
        }
        $nomorIdentitas = 'PTG-' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

        $generatedPassword = Str::random(10);

        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'no_telepon'      => $validated['no_telepon'],
            'kategori'        => $validated['jabatan'] ?? 'Staf Penanggung Jawab Lapangan',
            'fakultas'        => $validated['unit_kerja'] ?? 'Fakultas Sains dan Matematika (FSM)',
            'nomor_identitas' => $nomorIdentitas,
            'role'            => 'petugas',
            'password'        => Hash::make($generatedPassword),
            'status_akun'     => $request->has('status_aktif') ? 'verified' : 'pending',
        ]);

        return redirect()->route('admin.officers.index')->with('success', "Petugas '{$user->name}' berhasil ditambahkan. Password sementara: {$generatedPassword}");
    }
}
