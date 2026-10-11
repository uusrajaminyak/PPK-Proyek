<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
            && ($user->isAdmin() || ($user->isPetugas() && $this->input('status_fasilitas') !== 'inactive'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_fasilitas' => ['required', 'string', 'in:active,in_repair,inactive'],
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
            'status_fasilitas' => 'status fasilitas',
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
            'status_fasilitas.required' => 'Status fasilitas wajib dipilih.',
            'status_fasilitas.in' => 'Status fasilitas harus Aktif, Dalam Perbaikan, atau Nonaktif.',
        ];
    }
}
