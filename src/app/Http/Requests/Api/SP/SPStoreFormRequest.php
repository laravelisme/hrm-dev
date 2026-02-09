<?php

namespace App\Http\Requests\Api\SP;

use Illuminate\Foundation\Http\FormRequest;

class SPStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'm_karyawan_id' => 'required|exists:m_karyawans,id',
            'm_jenis_sp_id' => 'required|exists:m_jenis_sps,id',
            'tanggal_start' => 'required|date',
            'tanggal_end' => 'required|date|after_or_equal:tanggal_start',
            'atasan_note' => 'nullable|string',
            'details' => 'nullable|array',
            'details.*.jenis' => 'required|string',
            'details.*.keterangan' => 'nullable|string',
            'details.*.file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
