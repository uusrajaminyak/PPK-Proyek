<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['petugas', 'admin']);

        if ($request->filled('kategori') && in_array($request->kategori, ['Petugas', 'Admin'], true)) {
            $query->where('role', Str::lower($request->kategori));
        }

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
        $officer = User::whereIn('role', ['petugas', 'admin'])->findOrFail($id);

        return view('admin.officers.show', compact('officer'));
    }

    public function updateStatus(Request $request, $id)
    {
        $officer = User::whereIn('role', ['petugas', 'admin'])->findOrFail($id);

        $validated = $request->validate([
            'status_akun' => ['required', 'string', 'in:verified,pending,suspended,rejected'],
        ]);

        $officer->update(['status_akun' => $validated['status_akun']]);

        $statusLabel = match ($validated['status_akun']) {
            'verified' => 'diaktifkan',
            'suspended' => 'dinonaktifkan (Suspended)',
            default => 'diubah',
        };

        return redirect()->back()->with('success', "Status akun '{$officer->name}' berhasil {$statusLabel}.");
    }

    public function resetPassword($id)
    {
        $officer = User::whereIn('role', ['petugas', 'admin'])->findOrFail($id);

        $newPassword = Str::random(10);
        $officer->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()->with('success', "Password akun '{$officer->name}' berhasil di-reset. Password baru: {$newPassword}");
    }

    public function create()
    {
        return view('admin.officers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_depan' => ['required', 'string', 'max:127'],
            'nama_belakang' => ['required', 'string', 'max:127'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'kategori' => ['required', 'string', 'in:Petugas,Admin'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $role = Str::lower($validated['kategori']);
        $idPrefix = $role === 'admin' ? 'ADM' : 'PTG';
        $lastIdentity = User::query()
            ->where('role', $role)
            ->where('nomor_identitas', 'like', $idPrefix.'-%')
            ->latest('id')
            ->value('nomor_identitas');

        $nextIdNumber = 1;
        if ($lastIdentity && preg_match('/^'.preg_quote($idPrefix, '/').'-([0-9]+)$/', $lastIdentity, $matches)) {
            $nextIdNumber = intval($matches[1]) + 1;
        }
        $nomorIdentitas = $idPrefix.'-'.str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);

        $generatedPassword = Str::random(10);
        $name = trim($validated['nama_depan'].' '.$validated['nama_belakang']);

        $user = User::create([
            'name' => $name,
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'],
            'kategori' => $validated['kategori'],
            'fakultas' => 'Fakultas Sains dan Matematika (FSM)',
            'nomor_identitas' => $nomorIdentitas,
            'role' => $role,
            'password' => Hash::make($generatedPassword),
            'status_akun' => ($validated['status_aktif'] ?? false) ? 'verified' : 'pending',
        ]);

        return redirect()->route('admin.officers.index')->with('success', "Akun {$validated['kategori']} '{$user->name}' berhasil ditambahkan. Password sementara: {$generatedPassword}");
    }
}
