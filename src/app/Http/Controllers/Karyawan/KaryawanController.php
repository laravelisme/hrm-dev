<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\MCompany;
use App\Models\MDepartment;
use App\Models\MJabatan;
use App\Models\MKaryawan;
use Illuminate\Http\Request;
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

            $searchName = trim((string) $request->query('searchName', ''));
            $searchNik = trim((string) $request->query('searchNik', ''));
            $searchJabatan = trim((string) $request->query('searchJabatan', ''));
            $searchDepartment = trim((string) $request->query('searchDepartment', ''));
            $searchCompany = trim((string) $request->query('searchCompany', ''));
            $searchLokasiKerja = trim((string) $request->query('searchLokasiKerja', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->karyawan->newQuery();

            if ($searchName) {
                $query->where('nama_karyawan', 'like', '%' . $searchName . '%');
            }

            if ($searchNik) {
                $query->where('nik', 'like', '%' . $searchNik . '%');
            }

            if ($searchJabatan) {
                $query->where('m_jabatan_id', 'like', '%' . $searchJabatan . '%');
            }

            if ($searchDepartment) {
                $query->where('m_department_id', 'like', '%' . $searchDepartment . '%');
            }

            if ($searchCompany) {
                $query->where('m_company_id', 'like', '%' . $searchCompany . '%');
            }

            if ($searchLokasiKerja) {
                $query->where('m_lokasi_kerja_id', 'like', '%' . $searchLokasiKerja . '%');
            }

            $karyawans = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.karyawan.index', compact('karyawans'));

        } catch (\Throwable $e) {
            Log::error('[KaryawanIndex@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load karyawan data');
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

    public function jabatanOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', ''));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MJabatan::query()->select('id', 'nama_jabatan');

            if ($term !== '') {
                $q->where('nama_jabatan', 'like', "%{$term}%");
            }

            $paginator = $q->orderBy('nama_jabatan')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'results' => $paginator->getCollection()->map(fn($j) => [
                    'id' => $j->id,
                    'text' => $j->nama_jabatan
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
