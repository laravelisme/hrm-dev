<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Karyawan\KaryawanStoreFormRequest;
use App\Models\MCompany;
use App\Models\MDepartment;
use App\Models\MJabatan;
use App\Models\MKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
                    // alias supaya UI bisa pakai field yang sama seperti di table m_karyawans
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

            // supaya select2 tetap ada value setelah reload
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

    public function store(KaryawanStoreFormRequest $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, &$userId) {

                // 1) CREATE USER (password = NIK)
                $userId = DB::table('p_users')->insertGetId([
                    'username' => $data['nama_karyawan'],
                    'name' => $data['nama_karyawan'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['nik']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 2) Lookup master untuk denormalisasi
                $jabatan    = !empty($data['m_jabatan_id']) ? MJabatan::find($data['m_jabatan_id']) : null;
                $department = !empty($data['m_department_id']) ? MDepartment::find($data['m_department_id']) : null;
                $company    = !empty($data['m_company_id']) ? MCompany::find($data['m_company_id']) : null;

                if (empty($data['kode_karyawan'])) {
                    $data['kode_karyawan'] = $this->generateKodeKaryawan();
                }

                // 3) CREATE KARYAWAN
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

                    'm_company_id' => $data['m_company_id'] ?? null,
                    'nama_company' => $company?->company_name,

                    // ===== ORANG TUA =====
                    'nama_ayah' => $data['nama_ayah'] ?? null,
                    'tanggal_lahir_ayah' => $data['tanggal_lahir_ayah'] ?? null,
                    'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? null,

                    'nama_ibu' => $data['nama_ibu'] ?? null,
                    'tanggal_lahir_ibu' => $data['tanggal_lahir_ibu'] ?? null,
                    'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? null,

                    // opsional
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
                    'created_by' => auth()->user()->name ?? null,
                ]);

                // 4) Create 1 job history (optional)
                if (!empty($data['m_jabatan_id']) || !empty($data['m_department_id']) || !empty($data['m_company_id'])) {
                    $karyawan->jabatans()->create([
                        'm_jabatan_id' => $data['m_jabatan_id'] ?? $jabatan?->id,
                        'm_department_id' => $data['m_department_id'] ?? $department?->id,
                        'm_company_id' => $data['m_company_id'] ?? $company?->id,
                        'nama_jabatan' => $jabatan?->name,
                        'nama_department' => $department?->department_name,
                        'nama_company' => $company?->company_name,
                        'tanggal_mulai' => $data['tanggal_bergabung'] ?? null,
                    ]);
                }

                // 5) Create many detail rows (filter row kosong)
                $karyawan->pendidikans()->createMany($this->filterRows($data['pendidikan'] ?? []));
                $karyawan->pengalamanKerjas()->createMany($this->filterRows($data['pengalaman_kerja'] ?? []));
                $karyawan->organisasis()->createMany($this->filterRows($data['organisasi'] ?? []));
                $karyawan->bahasas()->createMany($this->filterRows($data['bahasa'] ?? []));
                $karyawan->anaks()->createMany($this->filterRows($data['anak'] ?? []));
                $karyawan->saudaras()->createMany($this->filterRows($data['saudara'] ?? []));
            });

            return $this->successResponse(null, 'Karyawan + User berhasil dibuat (password = NIK)', 201);

        } catch (\Throwable $e) {
            Log::error('[KaryawanController@store] '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to create karyawan', 500);
        }
    }

    private function filterRows(array $rows): array
    {
        return array_values(array_filter($rows, function ($row) {
            if (!is_array($row)) return false;
            foreach ($row as $v) {
                if ($v !== null && $v !== '') return true;
            }
            return false;
        }));
    }

    private function generateKodeKaryawan(): string
    {
        // simple: KRY-000001
        $lastId = DB::table('m_karyawans')->max('id') ?? 0;
        return 'KRY-' . str_pad((string)($lastId + 1), 6, '0', STR_PAD_LEFT);
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

    public function jabatanOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MJabatan::query()->select('id', 'name');

            if ($term !== '') {
                $q->where('name', 'like', "%{$term}%");
            }

            $paginator = $q->orderBy('name')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($j) => [
                    'id' => $j->id,
                    'text' => $j->name
                ]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@jabatanOptions] '.$e->getMessage());
            return response()->json(['results'=>[]],500);
        }
    }

    public function departmentOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MDepartment::query()->select('id', 'department_name');

            if ($term !== '') {
                $q->where('department_name', 'like', "%{$term}%");
            }

            $paginator = $q->orderBy('department_name')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($d) => [
                    'id' => $d->id,
                    'text' => $d->department_name
                ]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@departmentOptions] '.$e->getMessage());
            return response()->json(['results'=>[]],500);
        }
    }

    public function companyOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MCompany::query()->select('id', 'company_name');

            if ($term !== '') {
                $q->where('company_name', 'like', "%{$term}%");
            }

            $paginator = $q->orderBy('company_name')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($c) => [
                    'id' => $c->id,
                    'text' => $c->company_name
                ]),
                'pagination' => ['more' => $paginator->hasMorePages()]
            ]);
        } catch (\Throwable $e) {
            Log::error('[KaryawanController@companyOptions] '.$e->getMessage());
            return response()->json(['results'=>[]],500);
        }
    }


}
