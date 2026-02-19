<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Karyawan\KaryawanStoreFormRequest;
use App\Http\Requests\Karyawan\KaryawanUpdateFormRequest;
use App\Models\MCompany;
use App\Models\MDepartment;
use App\Models\MGrupJamKerja;
use App\Models\MJabatan;
use App\Models\MKaryawan;
use App\Models\MLokasiKerja;
use App\Models\MSaldoCuti;
use App\Models\TSaldoCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class KaryawanController extends Controller
{
    private MKaryawan $karyawan;

    public function __construct(MKaryawan $karyawan)
    {
        $this->karyawan = $karyawan;
    }

    public function index(Request $request)
    {
        try {
            $searchName       = trim((string) $request->query('searchName', ''));
            $searchNik        = trim((string) $request->query('searchNik', ''));
            $searchJabatan    = $request->query('searchJabatan');
            $searchDepartment = $request->query('searchDepartment');
            $searchCompany    = $request->query('searchCompany');

            $perPage = (int) $request->query('perPage', 10);
            $perPage = max(1, min($perPage, 100));

            $query = $this->karyawan->newQuery()
                ->from('m_karyawans')
                ->leftJoin('m_jabatans as j', 'j.id', '=', 'm_karyawans.m_jabatan_id')
                ->leftJoin('m_departments as d', 'd.id', '=', 'm_karyawans.m_department_id')
                ->leftJoin('m_companies as c', 'c.id', '=', 'm_karyawans.m_company_id')
                ->select([
                    'm_karyawans.*',
                    'j.name as nama_jabatan',
                    'd.department_name as nama_departement',
                    'c.company_name as nama_company',
                ]);

            if ($searchName !== '') {
                $query->where('m_karyawans.nama_karyawan', 'like', "%{$searchName}%");
            }
            if ($searchNik !== '') {
                $query->where('m_karyawans.nik', 'like', "%{$searchNik}%");
            }
            if (!empty($searchJabatan)) {
                $query->where('m_karyawans.m_jabatan_id', (int) $searchJabatan);
            }
            if (!empty($searchDepartment)) {
                $query->where('m_karyawans.m_department_id', (int) $searchDepartment);
            }
            if (!empty($searchCompany)) {
                $query->where('m_karyawans.m_company_id', (int) $searchCompany);
            }

            $karyawans = $query
                ->orderByDesc('m_karyawans.id')
                ->paginate($perPage)
                ->withQueryString();

            $selectedJabatan = !empty($searchJabatan)
                ? MJabatan::select('id', 'name')->find((int) $searchJabatan)
                : null;

            $selectedDepartment = !empty($searchDepartment)
                ? MDepartment::select('id', 'department_name')->find((int) $searchDepartment)
                : null;

            $selectedCompany = !empty($searchCompany)
                ? MCompany::select('id', 'company_name')->find((int) $searchCompany)
                : null;

            return view('pages.karyawan.index', compact(
                'karyawans',
                'selectedJabatan',
                'selectedDepartment',
                'selectedCompany'
            ));
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load karyawan data');
        }
    }

    public function create()
    {
        return view('pages.karyawan.create');
    }

    public function edit($id)
    {
        $karyawan = MKaryawan::with([
            'jabatans',
            'pendidikans',
            'pengalamanKerjas',
            'organisasis',
            'bahasas',
            'anaks',
            'saudaras',
        ])->findOrFail($id);

        // determine selected role for the linked user (if any)
        $selectedRole = 'normal';
        if (!empty($karyawan->user_id)) {
            $user = User::find($karyawan->user_id);
            if ($user) {
                $roles = $user->getRoleNames();
                if ($roles->count() > 0) {
                    // take first role name as selected
                    $selectedRole = $roles->first();
                }
            }
        }

        $existing = [
            'jabatans' => ($karyawan->jabatans ?? collect())->map(function ($r) {
                return [
                    'm_jabatan_id' => $r->m_jabatan_id,
                    'm_department_id' => $r->m_department_id,
                    'm_company_id' => $r->m_company_id,
                    'tugas_utama' => $r->tugas_utama,
                    'tugas' => $r->tugas,
                    'aplikasi' => $r->aplikasi,
                    'tanggal_mulai' => $r->tanggal_mulai ? \Carbon\Carbon::parse($r->tanggal_mulai)->format('Y-m-d') : null,
                    'tanggal_selesai' => $r->tanggal_selesai ? \Carbon\Carbon::parse($r->tanggal_selesai)->format('Y-m-d') : null,
                    'nama_jabatan' => $r->nama_jabatan,
                    'nama_department' => $r->nama_department,
                    'nama_company' => $r->nama_company,
                ];
            })->values(),

            'pendidikan' => ($karyawan->pendidikans ?? collect())->map(function ($r) {
                return [
                    'jenjang_pendidikan' => $r->jenjang_pendidikan,
                    'nama' => $r->nama,
                    'program_studi' => $r->program_studi,
                    'tahun_masuk' => $r->tahun_masuk,
                    'tahun_lulus' => $r->tahun_lulus,
                    'urutan' => $r->urutan,
                ];
            })->values(),

            'pengalaman_kerja' => ($karyawan->pengalamanKerjas ?? collect())->map(function ($r) {
                return [
                    'nama_perusahaan' => $r->nama_perusahaan,
                    'jabatan' => $r->jabatan,
                    'tahun_mulai' => $r->tahun_mulai,
                    'tahun_selesai' => $r->tahun_selesai,
                    'riwayat_tugas' => $r->riwayat_tugas,
                    'riwayat_alamat_perusahaan' => $r->riwayat_alamat_perusahaan,
                    'riwayat_berhenti' => $r->riwayat_berhenti,
                    'gaji' => $r->gaji,
                    'urutan' => $r->urutan,
                ];
            })->values(),

            'organisasi' => ($karyawan->organisasis ?? collect())->map(function ($r) {
                return [
                    'nama' => $r->nama,
                    'posisi' => $r->posisi,
                    'is_active' => (int) $r->is_active,
                    'urutan' => $r->urutan,
                ];
            })->values(),

            'bahasa' => ($karyawan->bahasas ?? collect())->map(function ($r) {
                return [
                    'bahasa_asing' => $r->bahasa_asing,
                    'kemampuan_berbicara' => $r->kemampuan_berbicara,
                    'kemampuan_menulis' => $r->kemampuan_menulis,
                    'kemampuan_membaca' => $r->kemampuan_membaca,
                    'urutan' => $r->urutan,
                ];
            })->values(),

            'anak' => ($karyawan->anaks ?? collect())->map(function ($r) {
                return [
                    'anak_ke' => $r->anak_ke,
                    'nama' => $r->nama,
                    'jenis_kelamin' => $r->jenis_kelamin,
                    'tempat_lahir' => $r->tempat_lahir,
                    'tanggal_lahir' => $r->tanggal_lahir ? \Carbon\Carbon::parse($r->tanggal_lahir)->format('Y-m-d') : null,
                    'pendidikan_terakhir' => $r->pendidikan_terakhir,
                ];
            })->values(),

            'saudara' => ($karyawan->saudaras ?? collect())->map(function ($r) {
                return [
                    'anak_ke' => $r->anak_ke,
                    'nama' => $r->nama,
                    'jenis_kelamin' => $r->jenis_kelamin,
                    'tanggal_lahir' => $r->tanggal_lahir ? \Carbon\Carbon::parse($r->tanggal_lahir)->format('Y-m-d') : null,
                    'pendidikan_terakhir' => $r->pendidikan_terakhir,
                    'pekerjaan' => $r->pekerjaan,
                ];
            })->values(),
        ];

        $selectedGroupKerja = !empty($karyawan->m_group_kerja_id)
            ? \App\Models\MGrupJamKerja::select('id','name')->find($karyawan->m_group_kerja_id)
            : null;

        return view('pages.karyawan.edit', compact('karyawan', 'existing', 'selectedGroupKerja', 'selectedRole'));
    }

    public function store(KaryawanStoreFormRequest $request)
    {
        $data = $request->validated();

        try {

            if ($request->hasFile('cv_file')) {
                $cvFile = $request->file('cv_file');
                $cvFilePath = $cvFile->store('karyawan_cv', 'public');
                $data['cv_file'] = $cvFilePath;
            }

            if ($request->hasFile('kk_file')) {
                $kkFile = $request->file('kk_file');
                $kkFilePath = $kkFile->store('karyawan_kk', 'public');
                $data['kk_file'] = $kkFilePath;
            }

            if ($request->hasFile('npwp_file')) {
                $ktpFile = $request->file('npwp_file');
                $ktpFilePath = $ktpFile->store('karyawan_npwp', 'public');
                $data['npwp_file'] = $ktpFilePath;
            }

            if ($request->hasFile('sim_file')) {
                $simFile = $request->file('sim_file');
                $simFilePath = $simFile->store('karyawan_sim', 'public');
                $data['sim_file'] = $simFilePath;
            }

            if ($request->hasFile('ijazah_file')) {
                $ijazahFile = $request->file('ijazah_file');
                $ijazahFilePath = $ijazahFile->store('karyawan_ijazah', 'public');
                $data['ijazah_file'] = $ijazahFilePath;
            }

            if ($request->hasFile('ktp_file')) {
                $ktpFile = $request->file('ktp_file');
                $ktpFilePath = $ktpFile->store('karyawan_ktp', 'public');
                $data['ktp_file'] = $ktpFilePath;
            }

            if ($request->hasFile('foto')) {
                $fotoFile = $request->file('foto');
                $fotoFilePath = $fotoFile->store('karyawan_foto', 'public');
                $data['foto'] = $fotoFilePath;
            }

            DB::transaction(function () use ($data) {

                $passwordPlain = strlen($data['nik']) >= 6 ? substr($data['nik'], -6) : $data['nik'];

                $userId = DB::table('p_users')->insertGetId([
                    'username' => $data['nama_karyawan'],
                    'name' => $data['nama_karyawan'],
                    'email' => $data['email'],
                    'password' => Hash::make($passwordPlain),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // assign role if provided on store (optional) - if role set and not 'normal', sync roles
                if (!empty($data['role']) && $data['role'] !== 'normal') {
                    $user = User::find($userId);
                    if ($user) {
                        $user->syncRoles([$data['role']]);
                    }
                }

                // 2) Lookup master
                $jabatan    = !empty($data['m_jabatan_id']) ? MJabatan::find($data['m_jabatan_id']) : null;
                $department = !empty($data['m_department_id']) ? MDepartment::find($data['m_department_id']) : null;
                $company    = !empty($data['m_company_id']) ? MCompany::find($data['m_company_id']) : null;
                $atasan1    = !empty($data['atasan1_id']) ? MKaryawan::find($data['atasan1_id']) : null;
                $atasan2    = !empty($data['atasan2_id']) ? MKaryawan::find($data['atasan2_id']) : null;

                if (empty($data['kode_karyawan'])) {
                    $data['kode_karyawan'] = $this->generateKodeKaryawan();
                }

                $karyawan = MKaryawan::create([
                    'kode_karyawan' => $data['kode_karyawan'],
                    'nama_karyawan' => $data['nama_karyawan'],
                    'email' => $data['email'],
                    'nik' => $data['nik'],
                    'user_id' => $userId,

                    'm_jabatan_id' => $data['m_jabatan_id'] ?? null,
                    'nama_jabatan' => $jabatan?->name,
                    'level' => $jabatan?->level,

                    'm_department_id' => $data['m_department_id'] ?? null,
                    'nama_departement' => $department?->department_name,
                    'm_lokasi_kerja_id' => $data['m_lokasi_kerja_id'] ?? null,
                    'nama_lokasi_kerja' => $data['nama_lokasi_kerja'] ?? null,

                    'jabatan_tugas_utama' => $data['jabatan_tugas_utama'] ?? null,
                    'jabatan_tugas' => $data['jabatan_tugas'] ?? null,
                    'jabatan_aplikasi' => $data['jabatan_aplikasi'] ?? null,
                    'fasilitas' => $data['fasilitas'] ?? null,
                    'rencana_karir' => $data['rencana_karir'] ?? null,
                    'jabatan_tanggal_mulai' => $data['jabatan_tanggal_mulai'] ?? null,
                    'jabatan_tanggal_selesai' => $data['jabatan_tanggal_selesai'] ?? null,

                    'atasan1_id' => $data['atasan1_id'] ?? null,
                    'nama_atasan1' => $atasan1?->nama_karyawan,
                    'atasan2_id' => $data['atasan2_id'] ?? null,
                    'nama_atasan2' => $atasan2?->nama_karyawan,
                    'm_group_kerja_id' => $data['m_group_kerja_id'] ?? null,

                    'm_company_id' => $data['m_company_id'] ?? null,
                    'nama_company' => $company?->company_name,

                    'nama_ayah' => $data['nama_ayah'] ?? null,
                    'tanggal_lahir_ayah' => $data['tanggal_lahir_ayah'] ?? null,
                    'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? null,

                    'nama_ibu' => $data['nama_ibu'] ?? null,
                    'tanggal_lahir_ibu' => $data['tanggal_lahir_ibu'] ?? null,
                    'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? null,

                    'nama_pasangan' => $data['nama_pasangan'] ?? null,
                    'tempat_lahir_pasangan' => $data['tempat_lahir_pasangan'] ?? null,
                    'tanggal_lahir_pasangan' => $data['tanggal_lahir_pasangan'] ?? null,
                    'pekerjaan_pasangan' => $data['pekerjaan_pasangan'] ?? null,
                    'nama_perusahaan_pasangan' => $data['nama_perusahaan_pasangan'] ?? null,
                    'jabatan_pasangan' => $data['jabatan_pasangan'] ?? null,

                    'anak_ke' => $data['anak_ke'] ?? null,
                    'jumlah_anak' => $data['jumlah_anak'] ?? null,
                    'jumlah_saudara_kandung' => $data['jumlah_saudara_kandung'] ?? null,

                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat_ktp' => $data['alamat_ktp'] ?? null,
                    'alamat_domisili' => $data['alamat_domisili'] ?? null,
                    'tempat_lahir' => $data['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                    'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
                    'agama' => $data['agama'] ?? null,
                    'status_perkawinan' => $data['status_perkawinan'] ?? null,
                    'tanggal_bergabung' => $data['tanggal_bergabung'] ?? null,
                    'status_karyawan' => $data['status_karyawan'] ?? null,

                    'is_active' => (bool)($data['is_active'] ?? 0),
                    'is_active_organisasi' => (bool)($data['is_active_organisasi'] ?? 0),
                    'is_active_daerah_lain' => (bool)($data['is_active_daerah_lain'] ?? 0),
                    'alaasan_daerah_lain' => $data['alaasan_daerah_lain'] ?? null,
                    'is_perjalanan_dinas' => (bool)($data['is_perjalanan_dinas'] ?? 0),
                    'alasan_perjalanan_dinas' => $data['alasan_perjalanan_dinas'] ?? null,
                    'is_presensi' => (bool)($data['is_presensi'] ?? 0),
                    'is_cuti' => (bool)($data['is_cuti'] ?? 0),
                    'is_izin' => (bool)($data['is_izin'] ?? 0),
                    'is_lembur' => (bool)($data['is_lembur'] ?? 0),
                    'is_pembaruan_data' => (bool)($data['is_pembaruan_data'] ?? 0),
                    'is_resign' => (bool)($data['is_resign'] ?? 0),
                    'skip_level_two' => (bool)($data['skip_level_two'] ?? 0),

                    'cv_file' => $data['cv_file'] ?? null,
                    'kk_file' => $data['kk_file'] ?? null,
                    'npwp_file' => $data['npwp_file'] ?? null,
                    'sim_file' => $data['sim_file'] ?? null,
                    'ijazah_file' => $data['ijazah_file'] ?? null,
                    'ktp_file' => $data['ktp_file'] ?? null,
                    'foto' => $data['foto'] ?? null,
                    'created_by' => auth()->user()->name ?? null,

                    'tinggi_badan' => $data['tinggi_badan'] ?? null,
                    'berat_badan' => $data['berat_badan'] ?? null,
                    'golongan_darah' => $data['golongan_darah'] ?? null,
                    'riwayat_penyakit' => $data['riwayat_penyakit'] ?? null,
                    'kendaraan' => $data['kendaraan'] ?? null,
                    'sim' => $data['sim'] ?? null,

                    'darurat_nama' => $data['darurat_nama'] ?? null,
                    'darurat_hubungan' => $data['darurat_hubungan'] ?? null,
                    'darurat_hp' => $data['darurat_hp'] ?? null,
                    'darurat_alamat' => $data['darurat_alamat'] ?? null,
                ]);

                if (!empty($data['m_jabatan_id']) || !empty($data['m_department_id']) || !empty($data['m_company_id'])) {
                    $karyawan->jabatans()->create([
                        'm_jabatan_id' => $data['m_jabatan_id'] ?? null,
                        'm_department_id' => $data['m_department_id'] ?? null,
                        'm_company_id' => $data['m_company_id'] ?? null,
                        'nama_jabatan' => $jabatan?->name,
                        'nama_department' => $department?->department_name,
                        'nama_company' => $company?->company_name,
                        'tanggal_mulai' => $data['tanggal_bergabung'] ?? null,
                        'tugas' => $data['jabatan_tugas'] ?? null,
                        'aplikasi' => $data['jabatan_aplikasi'] ?? null,
                        'tugas_utama' => $data['jabatan_tugas_utama'] ?? null,
                    ]);
                }

                $karyawan->pendidikans()->createMany($this->filterRows($data['pendidikan'] ?? []));
                $karyawan->pengalamanKerjas()->createMany($this->filterRows($data['pengalaman_kerja'] ?? []));
                $karyawan->organisasis()->createMany($this->filterRows($data['organisasi'] ?? [], ['is_active']));
                $karyawan->bahasas()->createMany($this->filterRows($data['bahasa'] ?? []));
                $karyawan->anaks()->createMany($this->filterRows($data['anak'] ?? []));
                $karyawan->saudaras()->createMany($this->filterRows($data['saudara'] ?? []));

                $saldoCuti = MSaldoCuti::where('m_jabatan_id', $karyawan->m_jabatan_id)->first();

                TSaldoCuti::create([
                    'm_karyawan_id' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'tahun' => Carbon::now()->year,
                    'saldo' => $saldoCuti->jumlah ?? 0,
                    'sisa_saldo' => $saldoCuti->jumlah ?? 0,
                    'm_company_id' => $karyawan->m_company_id,
                    'nama_perusahaan' => $karyawan->nama_company,
                    'm_department_id' => $karyawan->m_department_id,
                    'nama_department' => $karyawan->nama_departement,
                    'm_karyawan_jabatan_id' => !empty($karyawan->m_jabatan_id) ? $karyawan->jabatans()->latest()->first()->id : null,
                    'nama_jabatan' => $karyawan->nama_jabatan,
                ]);
            });

            return $this->successResponse(null, 'Karyawan + User berhasil dibuat (password = NIK)', 201);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@store] '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to create karyawan', 500);
        }
    }

    public function update(KaryawanUpdateFormRequest $request, $id)
    {
        $data = $request->validated();

        try {

            if ($request->hasFile('cv_file')) {
                $cvFile = $request->file('cv_file');
                $cvFilePath = $cvFile->store('karyawan_cv', 'public');
                $data['cv_file'] = $cvFilePath;
            }

            if ($request->hasFile('kk_file')) {
                $kkFile = $request->file('kk_file');
                $kkFilePath = $kkFile->store('karyawan_kk', 'public');
                $data['kk_file'] = $kkFilePath;
            }

            if ($request->hasFile('npwp_file')) {
                $ktpFile = $request->file('npwp_file');
                $ktpFilePath = $ktpFile->store('karyawan_npwp', 'public');
                $data['npwp_file'] = $ktpFilePath;
            }

            if ($request->hasFile('sim_file')) {
                $simFile = $request->file('sim_file');
                $simFilePath = $simFile->store('karyawan_sim', 'public');
                $data['sim_file'] = $simFilePath;
            }

            if ($request->hasFile('ijazah_file')) {
                $ijazahFile = $request->file('ijazah_file');
                $ijazahFilePath = $ijazahFile->store('karyawan_ijazah', 'public');
                $data['ijazah_file'] = $ijazahFilePath;
            }

            if ($request->hasFile('ktp_file')) {
                $ktpFile = $request->file('ktp_file');
                $ktpFilePath = $ktpFile->store('karyawan_ktp', 'public');
                $data['ktp_file'] = $ktpFilePath;
            }

            if ($request->hasFile('foto')) {
                $fotoFile = $request->file('foto');
                $fotoFilePath = $fotoFile->store('karyawan_foto', 'public');
                $data['foto'] = $fotoFilePath;
            }

            DB::transaction(function () use ($id, $data) {

                $karyawan = MKaryawan::findOrFail($id);

                // simpan posisi lama
                $oldJabatanId = $karyawan->m_jabatan_id;
                $oldDeptId    = $karyawan->m_department_id;
                $oldCompId    = $karyawan->m_company_id;

                $jabatan    = !empty($data['m_jabatan_id']) ? MJabatan::find($data['m_jabatan_id']) : null;
                $department = !empty($data['m_department_id']) ? MDepartment::find($data['m_department_id']) : null;
                $company    = !empty($data['m_company_id']) ? MCompany::find($data['m_company_id']) : null;
                $atasan1    = !empty($data['atasan1_id']) ? MKaryawan::find($data['atasan1_id']) : null;
                $atasan2    = !empty($data['atasan2_id']) ? MKaryawan::find($data['atasan2_id']) : null;

                DB::table('p_users')
                    ->where('id', $karyawan->user_id)
                    ->update([
                        'username' => $data['nama_karyawan'],
                        'name' => $data['nama_karyawan'],
                        'email' => $data['email'],
                        'updated_at' => now(),
                    ]);

                // sync role on linked user (if any): 'normal' => no roles (remove all)
                if (!empty($karyawan->user_id)) {
                    $user = User::find($karyawan->user_id);
                    if ($user) {
                        if (!empty($data['role']) && $data['role'] !== 'normal') {
                            $user->syncRoles([$data['role']]);
                        } else {
                            // remove all roles
                            $user->syncRoles([]);
                        }
                    }
                }

                $kode = !empty($data['kode_karyawan']) ? $data['kode_karyawan'] : $karyawan->kode_karyawan;

                $karyawan->update([
                    'kode_karyawan' => $kode,
                    'nama_karyawan' => $data['nama_karyawan'],
                    'email' => $data['email'],
                    'nik' => $data['nik'],

                    'm_jabatan_id' => $data['m_jabatan_id'] ?? null,
                    'nama_jabatan' => $jabatan?->name,
                    'level' => $jabatan?->level,
                    'm_lokasi_kerja_id' => $data['m_lokasi_kerja_id'] ?? null,
                    'nama_lokasi_kerja' => $data['nama_lokasi_kerja'] ?? null,

                    'jabatan_tugas_utama' => $data['jabatan_tugas_utama'] ?? null,
                    'jabatan_tugas' => $data['jabatan_tugas'] ?? null,
                    'jabatan_aplikasi' => $data['jabatan_aplikasi'] ?? null,
                    'fasilitas' => $data['fasilitas'] ?? null,
                    'rencana_karir' => $data['rencana_karir'] ?? null,
                    'jabatan_tanggal_mulai' => $data['jabatan_tanggal_mulai'] ?? null,
                    'jabatan_tanggal_selesai' => $data['jabatan_tanggal_selesai'] ?? null,

                    'nama_pasangan' => $data['nama_pasangan'] ?? null,
                    'tempat_lahir_pasangan' => $data['tempat_lahir_pasangan'] ?? null,
                    'tanggal_lahir_pasangan' => $data['tanggal_lahir_pasangan'] ?? null,
                    'pekerjaan_pasangan' => $data['pekerjaan_pasangan'] ?? null,
                    'nama_perusahaan_pasangan' => $data['nama_perusahaan_pasangan'] ?? null,
                    'jabatan_pasangan' => $data['jabatan_pasangan'] ?? null,

                    'anak_ke' => $data['anak_ke'] ?? null,
                    'jumlah_anak' => $data['jumlah_anak'] ?? null,
                    'jumlah_saudara_kandung' => $data['jumlah_saudara_kandung'] ?? null,


                    'm_department_id' => $data['m_department_id'] ?? null,
                    'nama_departement' => $department?->department_name,

                    'atasan1_id' => $data['atasan1_id'] ?? null,
                    'nama_atasan1' => $atasan1?->nama_karyawan,

                    'atasan2_id' => $data['atasan2_id'] ?? null,
                    'nama_atasan2' => $atasan2?->nama_karyawan,

                    'm_group_kerja_id' => $data['m_group_kerja_id'] ?? null,

                    'm_company_id' => $data['m_company_id'] ?? null,
                    'nama_company' => $company?->company_name,

                    // orang tua
                    'nama_ayah' => $data['nama_ayah'] ?? null,
                    'tanggal_lahir_ayah' => $data['tanggal_lahir_ayah'] ?? null,
                    'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? null,

                    'nama_ibu' => $data['nama_ibu'] ?? null,
                    'tanggal_lahir_ibu' => $data['tanggal_lahir_ibu'] ?? null,
                    'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? null,

                    // lainnya
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat_ktp' => $data['alamat_ktp'] ?? null,
                    'alamat_domisili' => $data['alamat_domisili'] ?? null,
                    'tempat_lahir' => $data['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                    'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
                    'agama' => $data['agama'] ?? null,
                    'status_perkawinan' => $data['status_perkawinan'] ?? null,
                    'tanggal_bergabung' => $data['tanggal_bergabung'] ?? null,
                    'status_karyawan' => $data['status_karyawan'] ?? null,

                    'is_active' => (bool)($data['is_active'] ?? 0),
                    'is_active_organisasi' => (bool)($data['is_active_organisasi'] ?? 0),
                    'is_active_daerah_lain' => (bool)($data['is_active_daerah_lain'] ?? 0),
                    'alaasan_daerah_lain' => $data['alaasan_daerah_lain'] ?? null,
                    'is_perjalanan_dinas' => (bool)($data['is_perjalanan_dinas'] ?? 0),
                    'alasan_perjalanan_dinas' => $data['alasan_perjalanan_dinas'] ?? null,
                    'is_presensi' => (bool)($data['is_presensi'] ?? 0),
                    'is_cuti' => (bool)($data['is_cuti'] ?? 0),
                    'is_izin' => (bool)($data['is_izin'] ?? 0),
                    'is_lembur' => (bool)($data['is_lembur'] ?? 0),
                    'is_pembaruan_data' => (bool)($data['is_pembaruan_data'] ?? 0),
                    'is_resign' => (bool)($data['is_resign'] ?? 0),
                    'skip_level_two' => (bool)($data['skip_level_two'] ?? 0),

                    'cv_file' => $data['cv_file'] ?? $karyawan->cv_file,
                    'kk_file' => $data['kk_file'] ?? $karyawan->kk_file,
                    'npwp_file' => $data['npwp_file'] ?? $karyawan->npwp_file,
                    'sim_file' => $data['sim_file'] ?? $karyawan->sim_file,
                    'ijazah_file' => $data['ijazah_file'] ?? $karyawan->ijazah_file,
                    'ktp_file' => $data['ktp_file'] ?? $karyawan->ktp_file,
                    'foto' => $data['foto'] ?? $karyawan->foto,

                    'tinggi_badan' => $data['tinggi_badan'] ?? null,
                    'berat_badan' => $data['berat_badan'] ?? null,
                    'golongan_darah' => $data['golongan_darah'] ?? null,
                    'riwayat_penyakit' => $data['riwayat_penyakit'] ?? null,
                    'kendaraan' => $data['kendaraan'] ?? null,
                    'sim' => $data['sim'] ?? null,

                    'darurat_nama' => $data['darurat_nama'] ?? null,
                    'darurat_hubungan' => $data['darurat_hubungan'] ?? null,
                    'darurat_hp' => $data['darurat_hp'] ?? null,
                    'darurat_alamat' => $data['darurat_alamat'] ?? null,
                ]);

                $changedPosition =
                    ($oldJabatanId != ($data['m_jabatan_id'] ?? null)) ||
                    ($oldDeptId != ($data['m_department_id'] ?? null)) ||
                    ($oldCompId != ($data['m_company_id'] ?? null));

                if ($changedPosition && (!empty($data['m_jabatan_id']) || !empty($data['m_department_id']) || !empty($data['m_company_id']))) {
                    $karyawan->jabatans()->create([
                        'm_jabatan_id' => $data['m_jabatan_id'] ?? null,
                        'm_department_id' => $data['m_department_id'] ?? null,
                        'm_company_id' => $data['m_company_id'] ?? null,
                        'nama_jabatan' => $jabatan?->name,
                        'nama_department' => $department?->department_name,
                        'nama_company' => $company?->company_name,
                        'tugas' => $data['jabatan_tugas'] ?? null,
                        'aplikasi' => $data['jabatan_aplikasi'] ?? null,
                        'tugas_utama' => $data['jabatan_tugas_utama'] ?? null,
                        'tanggal_mulai' => $data['tanggal_bergabung'] ?? now()->toDateString(),
                        'tanggal_selesai' => Carbon::now()->toDateString(),
                    ]);
                }

                $karyawan->pendidikans()->delete();
                $karyawan->pengalamanKerjas()->delete();
                $karyawan->organisasis()->delete();
                $karyawan->bahasas()->delete();
                $karyawan->anaks()->delete();
                $karyawan->saudaras()->delete();

                $karyawan->pendidikans()->createMany($this->filterRows($data['pendidikan'] ?? []));
                $karyawan->pengalamanKerjas()->createMany($this->filterRows($data['pengalaman_kerja'] ?? []));
                $karyawan->organisasis()->createMany($this->filterRows($data['organisasi'] ?? [], ['is_active']));
                $karyawan->bahasas()->createMany($this->filterRows($data['bahasa'] ?? []));
                $karyawan->anaks()->createMany($this->filterRows($data['anak'] ?? []));
                $karyawan->saudaras()->createMany($this->filterRows($data['saudara'] ?? []));
            });

            return $this->successResponse(null, 'Karyawan berhasil diupdate', 200);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@update] '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update karyawan', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $karyawan = $this->karyawan->findOrFail($id);
            $karyawan->delete();

            return $this->successResponse(null, 'Karyawan Berhasil dihapus', 200);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete karyawan', 500);
        }
    }

    // ======================
    // OPTIONS select2
    // ======================
    public function jabatanOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MJabatan::query()->select('id', 'name');

            if ($term !== '') $q->where('name', 'like', "%{$term}%");

            $paginator = $q->orderBy('name')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($j) => ['id'=>$j->id,'text'=>$j->name]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@jabatanOptions] '.$e->getMessage());
            return response()->json(['results'=>[]], 500);
        }
    }

    public function departmentOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MDepartment::query()->select('id', 'department_name');

            if ($term !== '') $q->where('department_name', 'like', "%{$term}%");

            $paginator = $q->orderBy('department_name')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($d) => ['id'=>$d->id,'text'=>$d->department_name]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@departmentOptions] '.$e->getMessage());
            return response()->json(['results'=>[]], 500);
        }
    }

    public function companyOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MCompany::query()->select('id', 'company_name');

            if ($term !== '') $q->where('company_name', 'like', "%{$term}%");

            $paginator = $q->orderBy('company_name')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($c) => ['id'=>$c->id,'text'=>$c->company_name]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@companyOptions] '.$e->getMessage());
            return response()->json(['results'=>[]], 500);
        }
    }

    // ====== OPTIONS: ATASAN (SELECT2) ======
    public function atasanOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MKaryawan::query()->select('id', 'nama_karyawan', 'nik');

            if ($term !== '') {
                $q->where(function ($w) use ($term) {
                    $w->where('nama_karyawan', 'like', "%{$term}%")
                        ->orWhere('nik', 'like', "%{$term}%");
                });
            }

            $paginator = $q->orderBy('nama_karyawan')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($k) => [
                    'id' => $k->id,
                    'text' => $k->nama_karyawan . ($k->nik ? " ({$k->nik})" : ''),
                ]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@atasanOptions] '.$e->getMessage());
            return response()->json(['results' => []], 500);
        }
    }

    public function lokasiKerjaOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MLokasiKerja::query()->select('id', 'name');
            if ($term !== '') $q->where('name', 'like', "%{$term}%");

            $p = $q->orderBy('name')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $p->getCollection()->map(fn($r) => ['id' => $r->id, 'text' => $r->name]),
                'pagination' => ['more' => $p->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            \Log::error('[KaryawanController@lokasiKerjaOptions] '.$e->getMessage());
            return response()->json(['results' => []], 500);
        }
    }

    public function grupJamKerjaOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MGrupJamKerja::query()->select('id', 'name');

            if ($term !== '') {
                $q->where('name', 'like', "%{$term}%");
            }

            $paginator = $q->orderBy('name')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($r) => [
                    'id' => $r->id,
                    'text' => $r->name,
                ]),
                'pagination' => ['more' => $paginator->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@grupJamKerjaOptions] '.$e->getMessage());
            return response()->json(['results' => []], 500);
        }
    }


    // ======================
    // Helpers
    // ======================
    private function filterRows(array $rows, array $ignoreKeys = []): array
    {
        return array_values(array_filter($rows, function ($row) use ($ignoreKeys) {
            if (!is_array($row)) return false;

            foreach ($row as $k => $v) {
                if (in_array($k, $ignoreKeys, true)) continue;
                if ($v !== null && $v !== '') return true;
            }
            return false;
        }));
    }

    private function generateKodeKaryawan(): string
    {
        $lastId = DB::table('m_karyawans')->max('id') ?? 0;
        return 'KRY-' . str_pad((string)($lastId + 1), 6, '0', STR_PAD_LEFT);
    }
}
