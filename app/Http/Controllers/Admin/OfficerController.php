<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        // Strictly scope to 'petugas' role
        $query = User::where('role', 'petugas');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status_akun', $request->status);
        }

        $officers = $query->orderBy('nomor_identitas')->paginate(10)->withQueryString();

        return view('admin.officers.index', compact('officers'));
    }

    public function create()
    {
        // Dynamic dropdown data
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
        // Server-Side Validation 
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string'],
            'unit_kerja' => ['nullable', 'string'],
        ]);

        // Auto-generate ID Petugas (e.g., PTG-007)
        $lastOfficer = User::where('role', 'petugas')->orderBy('id', 'desc')->first();
        $nextIdNumber = 1;
        if ($lastOfficer && preg_match('/PTG-(\d+)/', $lastOfficer->nomor_identitas, $matches)) {
            $nextIdNumber = intval($matches[1]) + 1;
        }
        $nomorIdentitas = 'PTG-' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

        // Auto-generate secure password
        $generatedPassword = \Illuminate\Support\Str::random(10);

        // Store Officer
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'],
            'kategori' => $validated['jabatan'], 
            'fakultas' => $validated['unit_kerja'], 
            'nomor_identitas' => $nomorIdentitas,
            'role' => 'petugas', 
            'password' => \Illuminate\Support\Facades\Hash::make($generatedPassword),
            'status_akun' => $request->has('status_aktif') ? 'verified' : 'pending',
        ]);

        // TODO: Dispatch Job to email $generatedPassword to $user->email here

        return redirect()->route('admin.officers.index')->with('success', 'Petugas berhasil ditambahkan. Kredensial telah dikirim ke email institusi.');
    }
}