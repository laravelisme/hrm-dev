<?php

namespace App\Http\Requests\Karyawan;

use App\Models\MKaryawan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KaryawanUpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->hasRole('hr') || $user->hasRole('admin'));
    }

    public function rules(): array
    {
        $karyawanId = (int) $this->route('id');

        $karyawan = MKaryawan::query()
            ->select('id', 'user_id')
            ->find($karyawanId);

        $userId = $karyawan?->user_id;

        return [
            'kode_karyawan' => [
                'nullable','string','max:255',
                Rule::unique('m_karyawans','kode_karyawan')->ignore($karyawanId),
            ],

            'nama_karyawan' => ['required','string','max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('m_karyawans','email')->ignore($karyawanId),
                $userId
                    ? Rule::unique('p_users','email')->ignore($userId)
                    : Rule::unique('p_users','email'),
            ],
            'nik' => [
                'required','string','max:255',
                Rule::unique('m_karyawans','nik')->ignore($karyawanId),
            ],

            'm_lokasi_kerja_id' => ['nullable','integer','exists:m_lokasi_kerjas,id'],
            'nama_lokasi_kerja' => ['nullable','string','max:255'],

            'atasan1_id' => ['nullable','integer','exists:m_karyawans,id'],
            'atasan2_id' => ['nullable','integer','exists:m_karyawans,id'],

            'm_jabatan_id'    => ['nullable','exists:m_jabatans,id'],
            'm_department_id' => ['nullable','exists:m_departments,id'],
            'm_company_id'    => ['nullable','exists:m_companies,id'],
            'm_group_kerja_id' => ['nullable', 'exists:m_grup_jam_kerja,id'],

            'nama_pasangan' => ['nullable','string','max:255'],
            'tempat_lahir_pasangan' => ['nullable','string','max:255'],
            'tanggal_lahir_pasangan' => ['nullable','date'],
            'pekerjaan_pasangan' => ['nullable','string','max:255'],
            'nama_perusahaan_pasangan' => ['nullable','string','max:255'],
            'jabatan_pasangan' => ['nullable','string','max:255'],

            'anak_ke' => ['nullable','integer'],
            'jumlah_anak' => ['nullable','integer'],
            'jumlah_saudara_kandung' => ['nullable','integer'],

            'jabatan_tugas_utama' => ['nullable','string','max:255'],
            'jabatan_tugas' => ['nullable','string','max:255'],
            'jabatan_aplikasi' => ['nullable','string','max:255'],
            'fasilitas' => ['nullable','string','max:255'],
            'rencana_karir' => ['nullable','string','max:255'],
            'jabatan_tanggal_mulai' => ['nullable','date'],
            'jabatan_tanggal_selesai' => ['nullable','date'],

            'tinggi_badan' => ['nullable','string','max:50'],
            'berat_badan' => ['nullable','string','max:50'],
            'golongan_darah' => ['nullable','string','max:10'],
            'riwayat_penyakit' => ['nullable','string','max:255'],
            'kendaraan' => ['nullable','string','max:255'],
            'sim' => ['nullable','string','max:50'],

            // ===== FLAGS =====
            'is_active' => ['boolean'],
            'is_active_organisasi' => ['boolean'],
            'is_active_daerah_lain' => ['boolean'],
            'alaasan_daerah_lain' => ['nullable','string','max:255'],
            'is_perjalanan_dinas' => ['boolean'],
            'alasan_perjalanan_dinas' => ['nullable','string','max:255'],

            'is_presensi' => ['boolean'],
            'is_cuti' => ['boolean'],
            'is_izin' => ['boolean'],
            'is_lembur' => ['boolean'],
            'is_pembaruan_data' => ['boolean'],
            'is_resign' => ['boolean'],
            'is_data_benar' => ['boolean'],
            'skip_level_two' => ['boolean'],

            // ===== KONTAK DARURAT =====
            'darurat_nama' => ['nullable','string','max:255'],
            'darurat_hubungan' => ['nullable','string','max:255'],
            'darurat_hp' => ['nullable','string','max:50'],
            'darurat_alamat' => ['nullable','string','max:255'],

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

            // ===== m_karyawan_jabatans (riwayat) =====
            'jabatans' => ['nullable','array'],
            'jabatans.*.m_jabatan_id' => ['nullable','exists:m_jabatans,id','required_with:jabatans.*.m_department_id,jabatans.*.m_company_id'],
            'jabatans.*.m_department_id' => ['nullable','exists:m_departments,id','required_with:jabatans.*.m_jabatan_id,jabatans.*.m_company_id'],
            'jabatans.*.m_company_id' => ['nullable','exists:m_companies,id','required_with:jabatans.*.m_jabatan_id,jabatans.*.m_department_id'],
            'jabatans.*.tugas_utama' => ['nullable','string','max:255'],
            'jabatans.*.tugas' => ['nullable','string','max:255'],
            'jabatans.*.aplikasi' => ['nullable','string','max:255'],
            'jabatans.*.tanggal_mulai' => ['nullable','date'],
            'jabatans.*.tanggal_selesai' => ['nullable','date'],

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
