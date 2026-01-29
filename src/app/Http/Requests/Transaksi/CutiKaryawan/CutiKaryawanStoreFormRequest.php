<?php

namespace App\Http\Requests\Transaksi\CutiKaryawan;

use Illuminate\Foundation\Http\FormRequest;

class CutiKaryawanStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('hr');
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
            'nama_karyawan' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'm_company_id' => 'required|exists:m_companies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jumlah_hari' => 'required|integer|min:1',
            'keperluan' => 'required|string|max:500',
            'tanggal_kembali' => 'nullable|date|after:end_date',
            'alamat_selama_cuti' => 'nullable|string|max:500',
            'no_telp' => 'nullable|string|max:20',
            'atasan1_id' => 'nullable|exists:m_karyawans,id',
            'atasan2_id' => 'nullable|exists:m_karyawans,id',
            'm_jenis_cuti_id' => 'required|exists:m_jenis_cutis,id',
            'nama_atasan1' => 'nullable|string|max:255',
            'nama_atasan2' => 'nullable|string|max:255',
        ];
    }
}
