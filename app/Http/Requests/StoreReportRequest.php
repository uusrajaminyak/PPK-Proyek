<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'exists:facilities,id'],
            'kategori_laporan' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'min:10', 'max:2000'],
            'fotos' => ['nullable', 'array', 'max:5'],
            'fotos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'facility_id' => 'fasilitas',
            'kategori_laporan' => 'kategori kerusakan',
            'deskripsi' => 'deskripsi kerusakan',
            'fotos' => 'foto bukti kerusakan',
            'fotos.*' => 'berkas foto',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'facility_id.required' => 'Silakan pilih fasilitas yang mengalami masalah.',
            'facility_id.exists' => 'Fasilitas yang dipilih tidak valid.',
            'kategori_laporan.required' => 'Pilih kategori kerusakan fasilitas.',
            'deskripsi.required' => 'Deskripsi kerusakan wajib diisi.',
            'deskripsi.min' => 'Deskripsi kerusakan minimal 10 karakter.',
            'fotos.max' => 'Maksimal 5 foto bukti kerusakan yang dapat diunggah.',
            'fotos.*.image' => 'Berkas bukti harus berupa gambar.',
            'fotos.*.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.',
            'fotos.*.max' => 'Ukuran maksimal tiap foto adalah 5MB.',
        ];
    }
}

