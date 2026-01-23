<?php

namespace App\Http\Requests\Recruitment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalonKaryawanRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',

            'm_company_id' => ['required', 'exists:m_companies,id'],

            'm_department_id' => [
                'required',
                Rule::exists('m_departments', 'id')->where(fn($q) =>
                $q->where('company_id', $this->input('m_company_id'))
                ),
            ],

            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:30',
            'nik' => 'required|string|max:30|unique:m_calon_karyawans,nik',

            'jenis_kelamin' => 'required|in:MALE,FEMALE',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:100',

            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',

            'status_perkawinan' => 'required|in:SINGLE,MARRIED,DIVORCED,WIDOWED',

            'nama_ayah' => 'required|string|max:255',
            'tempat_lahir_ayah' => 'required|string|max:255',
            'tanggal_lahir_ayah' => 'required|date',
            'pekerjaan_ayah' => 'nullable|string|max:255',

            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'required|string|max:255',
            'tanggal_lahir_ibu' => 'required|date',
            'pekerjaan_ibu' => 'nullable|string|max:255',

            // Saudara (opsional)
            'nama_saudara_kandung_1' => 'nullable|string|max:255',
            'tempat_saudara_kandung_1' => 'nullable|string|max:255',
            'tanggal_saudara_kandung_1' => 'nullable|date',
            'pekerjaan_saudara_kandung_1' => 'nullable|string|max:255',

            'nama_saudara_kandung_2' => 'nullable|string|max:255',
            'tempat_saudara_kandung_2' => 'nullable|string|max:255',
            'tanggal_saudara_kandung_2' => 'nullable|date',
            'pekerjaan_saudara_kandung_2' => 'nullable|string|max:255',

            'nama_saudara_kandung_3' => 'nullable|string|max:255',
            'tempat_saudara_kandung_3' => 'nullable|string|max:255',
            'tanggal_saudara_kandung_3' => 'nullable|date',
            'pekerjaan_saudara_kandung_3' => 'nullable|string|max:255',

            'nama_saudara_kandung_4' => 'nullable|string|max:255',
            'tempat_saudara_kandung_4' => 'nullable|string|max:255',
            'tanggal_saudara_kandung_4' => 'nullable|date',
            'pekerjaan_saudara_kandung_4' => 'nullable|string|max:255',

            // Pengalaman kerja (opsional)
            'pengalaman_kerja_1' => 'nullable|string|max:255',
            'industri_pengalaman_kerja_1' => 'nullable|string|max:255',
            'alamat_pengalaman_kerja_1' => 'nullable|string|max:255',
            'posisi_pengalaman_kerja_1' => 'nullable|string|max:255',
            'gaji_awal_pengalaman_kerja_1' => 'nullable|string|max:255',
            'gaji_akhir_pengalaman_kerja_1' => 'nullable|string|max:255',
            'alasan_berhenti_pengalaman_kerja_1' => 'nullable|string|max:255',
            'keterangan_pengalaman_kerja_1' => 'nullable|string|max:255',

            'pengalaman_kerja_2' => 'nullable|string|max:255',
            'industri_pengalaman_kerja_2' => 'nullable|string|max:255',
            'alamat_pengalaman_kerja_2' => 'nullable|string|max:255',
            'posisi_pengalaman_kerja_2' => 'nullable|string|max:255',
            'gaji_awal_pengalaman_kerja_2' => 'nullable|string|max:255',
            'gaji_akhir_pengalaman_kerja_2' => 'nullable|string|max:255',
            'alasan_berhenti_pengalaman_kerja_2' => 'nullable|string|max:255',
            'keterangan_pengalaman_kerja_2' => 'nullable|string|max:255',

            'pengalaman_kerja_3' => 'nullable|string|max:255',
            'industri_pengalaman_kerja_3' => 'nullable|string|max:255',
            'alamat_pengalaman_kerja_3' => 'nullable|string|max:255',
            'posisi_pengalaman_kerja_3' => 'nullable|string|max:255',
            'gaji_awal_pengalaman_kerja_3' => 'nullable|string|max:255',
            'gaji_akhir_pengalaman_kerja_3' => 'nullable|string|max:255',
            'alasan_berhenti_pengalaman_kerja_3' => 'nullable|string|max:255',
            'keterangan_pengalaman_kerja_3' => 'nullable|string|max:255',

            // Pendidikan (wajib)
            'pendidikan_terakhir' => 'required|in:SD,SMP,SMA,D3,S1/D4,S2,S3',
            'nama_sekolah_universitas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'ipk_nilai_akhir' => 'nullable|string|max:50',
            'tahun_lulus' => 'nullable|string|max:10',

            // Training (opsional)
            'nama_lembaga_training' => 'nullable|string|max:255',
            'jenis_training' => 'nullable|string|max:255',

            // Bahasa Asing (opsional)
            'keahlian_bahasa_asing_1' => 'nullable|string|max:255',
            'kemampuan_bicara_1' => 'nullable|integer|min:1|max:5',
            'kemampuan_mendengar_1' => 'nullable|string|max:50',
            'kemampuan_menulis_1' => 'nullable|string|max:50',
            'kemampuan_membaca_1' => 'nullable|string|max:50',

            'keahlian_bahasa_asing_2' => 'nullable|string|max:255',
            'kemampuan_bicara_2' => 'nullable|integer|min:1|max:5',
            'kemampuan_mendengar_2' => 'nullable|string|max:50',
            'kemampuan_menulis_2' => 'nullable|string|max:50',
            'kemampuan_membaca_2' => 'nullable|string|max:50',

            'prestasi' => 'nullable|string|max:255',
        ];
    }
}
