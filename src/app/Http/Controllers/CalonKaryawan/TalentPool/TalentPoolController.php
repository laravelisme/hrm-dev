<?php

namespace App\Http\Controllers\CalonKaryawan\TalentPool;

use App\Http\Controllers\Controller;
use App\Models\MCalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TalentPoolController extends Controller
{
    private MCalonKaryawan $mCalonKaryawan;

    public function __construct(MCalonKaryawan $mCalonKaryawan)
    {
        $this->mCalonKaryawan = $mCalonKaryawan;
    }

    public function index(Request $request)
    {
        try {

            $searchName = trim((string) $request->query('searchName', ''));
            $searchDepartment = trim((string) $request->query('searchDepartment', ''));
            $searchCompany = trim((string) $request->query('searchCompany', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->mCalonKaryawan->newQuery()
                ->with('latestStatusRecruitment')
                ->whereHas('latestStatusRecruitment', function ($q) {
                    $q->where('status', 'TALENT_POOL');
                });

            if ($searchName || $searchDepartment || $searchCompany) {
                $query->where(function ($query) use ($searchName, $searchDepartment, $searchCompany) {
                    if ($searchName) {
                        $query->where('nama_lengkap', 'like', '%' . $searchName . '%');
                    }
                    if ($searchDepartment) {
                        $query->where('department_name', 'like', '%' . $searchDepartment . '%');
                    }
                    if ($searchCompany) {
                        $query->where('company_name', 'like', '%' . $searchCompany . '%');
                    }
                });
            }

            $calonKaryawans = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
            return view('pages.calon-karyawan.talent-pool.index', compact('calonKaryawans'));

        } catch (\Throwable $e) {
            Log::error('[TalentPoolController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load calon karyawan data');
        }
    }

    public function show($id)
    {
        try {

            $calonKaryawan = $this->mCalonKaryawan
                ->with('latestStatusRecruitment')
                ->findOrFail($id);

            if (!$calonKaryawan) {
                abort(404, 'Calon karyawan not found');
            }

            return view('pages.calon-karyawan.talent-pool.show', compact('calonKaryawan'));

        } catch (\Throwable $e) {
            Log::error('[TalentPoolController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load calon karyawan detail');
        }
    }

    public function destroy($id)
    {
        try {

            $calonKaryawan = $this->mCalonKaryawan->find($id);
            if (!$calonKaryawan) {
                return $this->errorResponse('Calon karyawan not found', 404);
            }

            $calonKaryawan->delete();

            return $this->successResponse(null, 'Calon karyawan deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[TalentPoolController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete calon karyawan', 500);
        }
    }
}
