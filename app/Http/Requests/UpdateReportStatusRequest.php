<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportStatusRequest extends FormRequest
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
            'status_laporan' => ['required', 'string', 'in:baru,diproses,selesai,ditolak'],
            'catatan_resolusi' => [
                'required_if:status_laporan,selesai,ditolak',
                'nullable',
                'string',
                'min:5',
                'max:1000',
            ],
            'update_facility_status' => [
                'nullable',
                'string',
                'in:active,in_repair,keep',
            ],
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
            'status_laporan' => 'status laporan',
            'catatan_resolusi' => 'catatan resolusi',
            'update_facility_status' => 'status operasional fasilitas',
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
            'status_laporan.required' => 'Status laporan wajib dipilih.',
            'status_laporan.in' => 'Pilihan status laporan tidak valid.',
            'catatan_resolusi.required_if' => 'Catatan resolusi wajib diisi saat laporan diselesaikan atau ditolak.',
            'catatan_resolusi.min' => 'Catatan resolusi minimal 5 karakter.',
            'catatan_resolusi.max' => 'Catatan resolusi maksimal 1000 karakter.',
            'update_facility_status.in' => 'Pilihan status operasional fasilitas tidak valid.',
        ];
    }
}
