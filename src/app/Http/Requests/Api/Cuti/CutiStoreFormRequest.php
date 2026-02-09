<?php

namespace App\Http\Requests\Api\Cuti;

use Illuminate\Foundation\Http\FormRequest;

class CutiStoreFormRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jumlah_hari' => 'nullable|string',
            'keperluan' => 'required|string',
            'alamat_selama_cuti' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'id_jenis' => 'required|exists:m_jenis_izins,id',
            'tgl_kembali_kerja' => 'nullable|date|after_or_equal:end_date',
        ];
    }
}
