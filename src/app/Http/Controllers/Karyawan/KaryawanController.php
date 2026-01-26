<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
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
}
