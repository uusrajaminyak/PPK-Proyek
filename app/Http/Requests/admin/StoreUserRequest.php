<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_depan' => ['required', 'string', 'max:127'],
            'nama_belakang' => ['required', 'string', 'max:127'],
            'nomor_identitas' => ['required', 'string', 'max:50', 'unique:users,nomor_identitas'],
            'kategori' => ['required', 'string', 'in:Mahasiswa,Dosen,Staf'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'program_studi' => ['exclude_if:kategori,Staf', 'required', 'string', 'max:255'],
            'angkatan' => ['exclude_unless:kategori,Mahasiswa', 'required', 'integer', 'min:2000', 'max:'.now()->year],
            'status_aktif' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_depan.required' => 'Nama depan pengguna wajib diisi.',
            'nama_belakang.required' => 'Nama belakang pengguna wajib diisi.',
            'nomor_identitas.required' => 'Nomor identitas (NIM/NIP/ID) wajib diisi.',
            'nomor_identitas.unique' => 'Nomor identitas tersebut sudah terdaftar dalam sistem.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email tersebut sudah terdaftar.',
            'no_telepon.required' => 'Nomor telepon aktif wajib diisi.',
            'kategori.required' => 'Silakan pilih kategori pengguna.',
            'angkatan.required' => 'Angkatan wajib dipilih untuk mahasiswa.',
        ];
    }
}
