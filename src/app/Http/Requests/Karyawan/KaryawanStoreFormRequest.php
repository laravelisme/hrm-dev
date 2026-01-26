<?php

namespace App\Http\Requests\Karyawan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KaryawanStoreFormRequest extends FormRequest
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
            'kode_karyawan' => ['nullable','string','max:255', Rule::unique('m_karyawans','kode_karyawan')],

            'nama_karyawan' => ['required','string','max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('m_karyawans','email'),
                Rule::unique('p_users','email'),
            ],
            'nik' => ['required','string','max:255', Rule::unique('m_karyawans','nik')],

            'm_jabatan_id'    => ['nullable','exists:m_jabatans,id'],
            'm_department_id' => ['nullable','exists:m_departments,id'],
            'm_company_id'    => ['nullable','exists:m_companies,id'],

            // ===== ORANG TUA =====
            'nama_ayah' => ['nullable','string','max:255'],
            'tanggal_lahir_ayah' => ['nullable','date'],
            'pekerjaan_ayah' => ['nullable','string','max:255'],

            'nama_ibu' => ['nullable','string','max:255'],
            'tanggal_lahir_ibu' => ['nullable','date'],
            'pekerjaan_ibu' => ['nullable','string','max:255'],

            // opsional lain
            'no_hp' => ['nullable','string','max:50'],
            'alamat_ktp' => ['nullable','string','max:255'],
            'alamat_domisili' => ['nullable','string','max:255'],
            'tempat_lahir' => ['nullable','string','max:100'],
            'tanggal_lahir' => ['nullable','date'],
            'jenis_kelamin' => ['nullable','string','max:50'],
            'status_perkawinan' => ['nullable','string','max:50'],
            'agama' => ['nullable','string','max:50'],
            'tanggal_bergabung' => ['nullable','date'],
            'status_karyawan' => ['nullable','string','max:50'],
            'is_active' => ['nullable','boolean'],

            // ===== detail arrays =====
            'pendidikan' => ['nullable','array'],
            'pendidikan.*.jenjang_pendidikan' => ['nullable','string','max:100'],
            'pendidikan.*.nama' => ['nullable','string','max:255'],
            'pendidikan.*.program_studi' => ['nullable','string','max:255'],
            'pendidikan.*.tahun_masuk' => ['nullable','string','max:10'],
            'pendidikan.*.tahun_lulus' => ['nullable','string','max:10'],
            'pendidikan.*.urutan' => ['nullable','integer'],

            'pengalaman_kerja' => ['nullable','array'],
            'pengalaman_kerja.*.nama_perusahaan' => ['nullable','string','max:255'],
            'pengalaman_kerja.*.jabatan' => ['nullable','string','max:255'],
            'pengalaman_kerja.*.tahun_mulai' => ['nullable','string','max:10'],
            'pengalaman_kerja.*.tahun_selesai' => ['nullable','string','max:10'],
            'pengalaman_kerja.*.urutan' => ['nullable','integer'],
            'pengalaman_kerja.*.riwayat_tugas' => ['nullable','string','max:255'],
            'pengalaman_kerja.*.riwayat_alamat_perusahaan' => ['nullable','string','max:255'],
            'pengalaman_kerja.*.riwayat_berhenti' => ['nullable','string','max:255'],
            'pengalaman_kerja.*.gaji' => ['nullable','string','max:50'],

            'organisasi' => ['nullable','array'],
            'organisasi.*.nama' => ['nullable','string','max:255'],
            'organisasi.*.posisi' => ['nullable','string','max:255'],
            'organisasi.*.is_active' => ['nullable','boolean'],
            'organisasi.*.urutan' => ['nullable','integer'],

            'bahasa' => ['nullable','array'],
            'bahasa.*.bahasa_asing' => ['nullable','string','max:255'],
            'bahasa.*.kemampuan_berbicara' => ['nullable','string','max:50'],
            'bahasa.*.kemampuan_menulis' => ['nullable','string','max:50'],
            'bahasa.*.kemampuan_membaca' => ['nullable','string','max:50'],
            'bahasa.*.urutan' => ['nullable','integer'],

            'anak' => ['nullable','array'],
            'anak.*.anak_ke' => ['nullable','integer'],
            'anak.*.nama' => ['nullable','string','max:255'],
            'anak.*.jenis_kelamin' => ['nullable','string','max:50'],
            'anak.*.tempat_lahir' => ['nullable','string','max:100'],
            'anak.*.tanggal_lahir' => ['nullable','date'],
            'anak.*.pendidikan_terakhir' => ['nullable','string','max:255'],

            'saudara' => ['nullable','array'],
            'saudara.*.anak_ke' => ['nullable','integer'],
            'saudara.*.nama' => ['nullable','string','max:255'],
            'saudara.*.jenis_kelamin' => ['nullable','string','max:50'],
            'saudara.*.tanggal_lahir' => ['nullable','date'],
            'saudara.*.pendidikan_terakhir' => ['nullable','string','max:255'],
            'saudara.*.pekerjaan' => ['nullable','string','max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge(['is_active' => (bool) $this->input('is_active')]);
        }
    }
}
