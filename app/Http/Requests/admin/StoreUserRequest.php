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
            'nama'            => ['required', 'string', 'max:255'],
            'nomor_identitas' => ['required', 'string', 'max:50', 'unique:users,nomor_identitas'],
            'kategori'        => ['required', 'string', 'in:Mahasiswa,Dosen,Staf,Petugas,Admin'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_telepon'      => ['required', 'string', 'max:20'],
            'fakultas'        => ['nullable', 'string', 'max:255'],
            'program_studi'   => ['nullable', 'string', 'max:255'],
            'status_aktif'    => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'            => 'Nama lengkap pengguna wajib diisi.',
            'nomor_identitas.required' => 'Nomor identitas (NIM/NIP/ID) wajib diisi.',
            'nomor_identitas.unique'   => 'Nomor identitas tersebut sudah terdaftar dalam sistem.',
            'email.required'           => 'Email universitas wajib diisi.',
            'email.email'              => 'Format alamat email tidak valid.',
            'email.unique'             => 'Email universitas tersebut sudah terdaftar.',
            'no_telepon.required'      => 'Nomor telepon aktif wajib diisi.',
            'kategori.required'        => 'Silakan pilih kategori pengguna.',
        ];
    }
}
