<?php

namespace App\Http\Requests\Transaksi\SuratPeringatan;

use Illuminate\Foundation\Http\FormRequest;

class SuratPeringatanStoreFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('hr');
    }

    public function rules(): array
    {
        return [
            'm_karyawan_id'   => ['required','integer','exists:m_karyawans,id'],
            'nama_karyawan'   => ['required','string','max:255'],

            'm_company_id'    => ['required','integer','exists:m_companies,id'],
            'nama_perusahaan' => ['required','string','max:255'],

            'm_jenis_sp_id'   => ['required','integer','exists:m_jenis_sps,id'],
            'nomor'           => ['nullable','string','max:100'],

            'tanggal_surat'   => ['nullable','date'],
            'tanggal_start'   => ['required','date'],
            'tanggal_end'     => ['required','date','after_or_equal:tanggal_start'],

            'atasan_id'       => ['nullable','integer','exists:m_karyawans,id'],
            'nama_atasan'     => ['nullable','string','max:255'],
            'atasan_note'     => ['nullable','string','max:500'],

            'file_surat'      => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],

            'details'                 => ['nullable','array'],
            'details.*.jenis'         => ['required_with:details','string','max:100'],
            'details.*.keterangan'    => ['required_with:details','string','max:500'],
            'details.*.file_pendukung'=> ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
        ];
    }
}
