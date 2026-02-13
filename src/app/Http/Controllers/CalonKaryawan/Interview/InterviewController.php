<?php

namespace App\Http\Controllers\CalonKaryawan\Interview;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalonKaryawan\Interview\InterviewStoreFormRequest;
use App\Models\MCalonKaryawan;
use App\Models\TInterview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InterviewController extends Controller
{
    private MCalonKaryawan $mCalonKaryawan;
    private TInterview $tInterview;

    public function __construct(MCalonKaryawan $mCalonKaryawan, TInterview $tInterview)
    {
        $this->mCalonKaryawan = $mCalonKaryawan;
        $this->tInterview = $tInterview;
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
                    $q->where('status', 'INTERVIEW');
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
            return view('pages.calon-karyawan.interview.index', compact('calonKaryawans'));

        } catch (\Throwable $e) {
            Log::error('[InterviewController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
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

            return view('pages.calon-karyawan.interview.show', compact('calonKaryawan'));

        } catch (\Throwable $e) {
            Log::error('[InterviewController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
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
            Log::error('[InterviewController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete calon karyawan', 500);
        }
    }

    public function generateLinkZoom(InterviewStoreFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $interview = TInterview::where('m_calon_karyawan_id', $id)->first();
            if (!$interview) {
                return $this->errorResponse('Interview record not found', 404);
            }

            $interview->interview_date_hr = $data['interview_date_hr'] ?? null;
            $interview->interview_time_hr = $data['interview_time_hr'] ?? null;
            $interview->interview_date_user = $data['interview_date_user'] ?? null;
            $interview->interview_time_user = $data['interview_time_user'] ?? null;
            $interview->interview_hr_location = $data['interview_hr_location'] ?? null;
            $interview->interview_user_location = $data['interview_user_location'] ?? null;
            $interview->interview_hr_status = $data['interview_hr_location'] ? 'SCHEDULED' : 'WAITING';
            $interview->interview_user_status = $data['interview_user_location'] ? 'SCHEDULED' : 'WAITING';
            $interview->interview_hr_notes = $data['interview_hr_notes'] ?? null;
            $interview->interview_user_notes = $data['interview_user_notes'] ?? null;
            $interview->save();

            return $this->successResponse($interview, 'Zoom link generated successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[InterviewController@generateLinkZoom] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to generate Zoom link', 500);
        }
    }

    public function showLinkZoom($id)
    {
        try {

            $interview = $this->tInterview->where('m_calon_karyawan_id', $id)->with('calonKaryawan')->first();
            if (!$interview) {
                abort(404, 'Interview link not found');
            }

            return view('pages.calon-karyawan.interview.show-link', compact('interview'));

        } catch (\Throwable $e) {
            Log::error('[InterviewController@showLinkZoom] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load interview link detail');
        }
    }
}
