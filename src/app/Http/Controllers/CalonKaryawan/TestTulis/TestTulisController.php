<?php

namespace App\Http\Controllers\CalonKaryawan\TestTulis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\TestTulis\GenerateDeadlineFormRequest;
use App\Http\Requests\Recruitment\TestTulis\GenerateTestFormRequest;
use App\Http\Requests\Recruitment\TestTulis\SettingTestFormRequest;
use App\Models\MCalonKaryawan;
use App\Models\TTestTulis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestTulisController extends Controller
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
                    $q->where('status', 'TES_TULIS');
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
            return view('pages.calon-karyawan.tes-tulis.index', compact('calonKaryawans'));

        } catch (\Throwable $e) {
            Log::error('[TestTulisController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
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

            return view('pages.calon-karyawan.tes-tulis.show', compact('calonKaryawan'));

        } catch (\Throwable $e) {
            Log::error('[TestTulisController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load calon karyawan detail');
        }
    }

    public function edit($id)
    {
        try {

            $calonKaryawan = $this->mCalonKaryawan->findOrFail($id);
            $testTulis = TTestTulis::where('m_calon_karyawan_id', $id)->first();
            if (!$testTulis) {
                abort(404, 'Calon karyawan test not found');
            }

            return view('pages.calon-karyawan.tes-tulis.show-test', compact('testTulis', 'calonKaryawan'));

        } catch (\Throwable $e) {
            Log::error('[TestTulisController@showTest] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load calon karyawan test detail');
        }
    }

    public function generateTest(GenerateTestFormRequest $request, $id)
    {
        try {
            $testTulis = TTestTulis::where('m_calon_karyawan_id', $id)->first();

            // komunikasi dengan service eksternal untuk generate test mas ocha

            $testTulis->test_psikologi = 'http://example.com/test/psikologi/' . $testTulis->id;
            $testTulis->test_teknikal = 'http://example.com/test/teknikal/' . $testTulis->id;

            $testTulis->save();

            return $this->successResponse($testTulis, 'Test for calon karyawan generated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[TestTulisController@generateTest] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to generate test for calon karyawan', 500);
        }
    }

    public function update(GenerateDeadlineFormRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $testTulis = TTestTulis::where('m_calon_karyawan_id', $id)->first();
            if (!$testTulis) {
                return $this->errorResponse('Calon karyawan test not found', 404);
            }
            $testTulis->deadline_psikologi = $data['deadline_psikologi'];
            $testTulis->deadline_teknikal = $data['deadline_teknikal'];

            $testTulis->save();

            return $this->successResponse($testTulis, 'Test deadline for calon karyawan updated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[TestTulisController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update test for calon karyawan', 500);
        }
    }

    public function setTest(SettingTestFormRequest $request)
    {
        try {

            $data = $request->validated();

            $calonKaryawan = MCalonKaryawan::where('nik', $data['nik'])->first();
            if (!$calonKaryawan) {
                return $this->errorResponse('Calon karyawan not found', 404);
            }

            $testTulis = TTestTulis::where('m_calon_karyawan_id', $calonKaryawan->id)->first();

//            if ($data['token'] !== $testTulis->token) {
//                return $this->errorResponse('Token tidak sesuai', 404);
//            }

            $testTulis->result_psikologi = $data['result_psikologi'];
            $testTulis->result_teknikal = $data['result_teknikal'];
            $testTulis->status_psikologi = 'completed';
            $testTulis->status_teknikal = 'completed';

            $testTulis->save();

            return $this->successResponse($testTulis, 'Test for calon karyawan set successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[TestTulisController@setTest] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to set test for calon karyawan', 500);
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
            Log::error('[TestTulisController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete calon karyawan', 500);
        }
    }
}
