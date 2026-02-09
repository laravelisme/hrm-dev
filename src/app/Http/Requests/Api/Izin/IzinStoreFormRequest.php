<?php

namespace App\Http\Requests\Api\Izin;

use Illuminate\Foundation\Http\FormRequest;

class IzinStoreFormRequest extends FormRequest
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
            'tanggal_start' => 'required|date',
            'tanggal_end' => 'required|date|after_or_equal:tanggal_start',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_end',
            'durasi' => 'nullable|string',
            'note' => 'required|string',
            'id_jenis' => 'required|exists:m_jenis_izins,id',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048|required_if:id_jenis,3',
        ];
    }
}
