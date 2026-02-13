<?php

namespace App\Http\Controllers\CalonKaryawan\ShortlistAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalonKaryawan\UpdateStatusRecruitment\UpdateStatusRecruitmentFormRequest;
use App\Models\MCalonKaryawan;
use App\Models\MStatusRecruitment;
use App\Models\TInterview;
use App\Models\TTestTulis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShortListAdminController extends Controller
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
                    $q->where('status', 'SHORTLIST_ADMIN');
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
            return view('pages.calon-karyawan.shortlist-admin.index', compact('calonKaryawans'));

        } catch (\Throwable $e) {
            Log::error('[ShortlistAdminController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
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

            return view('pages.calon-karyawan.shortlist-admin.show', compact('calonKaryawan'));

        } catch (\Throwable $e) {
            Log::error('[ShortlistAdminController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load calon karyawan detail');
        }
    }

    public function updateStatus(UpdateStatusRecruitmentFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $status = MStatusRecruitment::where('m_calon_karyawan_id', $id)->first();
            if (!$status) {
                return $this->errorResponse('Calon karyawan status not found', 404);
            }

            DB::beginTransaction();
            $status->status = $data['status'];
            $status->save();

            switch ($data['status']) {
                case 'TES_TULIS':
                    $testTulis = TTestTulis::where('m_calon_karyawan_id', $id)->first();
                    if (!$testTulis) {
                        TTestTulis::create([
                            'm_calon_karyawan_id' => $id,
                            'status_psikologi' => 'pending',
                            'status_teknikal' => 'pending',
                        ]);
                        break;
                    }

                    $testTulis->status_psikologi = 'pending';
                    $testTulis->status_teknikal = 'pending';
                    $testTulis->test_psikologi = null;
                    $testTulis->test_teknikal = null;
                    $testTulis->deadline_psikologi = null;
                    $testTulis->deadline_teknikal = null;
                    $testTulis->result_psikologi = null;
                    $testTulis->result_teknikal = null;
                    $testTulis->save();

                    break;

                case 'INTERVIEW':

                    $interview = TInterview::firstOrCreate([
                        'm_calon_karyawan_id' => $id,
                    ]);

                    $interview->interview_date_hr = null;
                    $interview->interview_time_hr = null;
                    $interview->interview_date_user = null;
                    $interview->interview_time_user = null;
                    $interview->interview_hr_location = null;
                    $interview->interview_user_location = null;
                    $interview->interview_user_notes = null;
                    $interview->interview_hr_notes = null;
                    $interview->interview_hr_status = 'WAITING';
                    $interview->interview_user_status = 'WAITING';

                    $interview->save();

                    break;

                default :
                    break;
            }

            DB::commit();

            return $this->successResponse($status, 'Calon karyawan status updated successfully', 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('[ShortlistAdminController@updateStatus] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update calon karyawan status', 500);
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
            Log::error('[ShortlistAdminController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete calon karyawan', 500);
        }
    }
}
