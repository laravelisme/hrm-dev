<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\CalonKaryawanRegisterRequest;
use App\Models\MCompany;
use App\Models\MDepartment;
use App\Models\MCalonKaryawan; // buat model ini
use App\Models\TTokenCalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function create()
    {
        try {
            $tokenStr = request('token');

            if (!$tokenStr) {
                return view('pages.recruitment.link-used', [
                    'title' => 'Token tidak ditemukan',
                    'message' => 'Token tidak valid atau tidak tersedia.',
                ]);
            }

            $tokenRow = TTokenCalonKaryawan::where('token', $tokenStr)->first();

            if (!$tokenRow) {
                return view('pages.recruitment.link-used', [
                    'title' => 'Token tidak valid',
                    'message' => 'Token tidak ditemukan atau sudah tidak berlaku.',
                ]);
            }

            if ($tokenRow->is_used) {
                return view('pages.recruitment.link-used', [
                    'title' => 'Link sudah digunakan',
                    'message' => 'Link ini hanya dapat digunakan satu kali.',
                ]);
            }

            $selectedCompany = null;
            $selectedDepartment = null;

            $oldCompanyId = old('m_company_id');
            $oldDepartmentId = old('m_department_id');

            if ($oldCompanyId) {
                $selectedCompany = MCompany::select('id', 'company_name')->find($oldCompanyId);
            }

            if ($oldDepartmentId) {
                $selectedDepartment = MDepartment::select('id', 'department_name', 'company_id')->find($oldDepartmentId);
            }

            return view('pages.recruitment.register', compact(
                'tokenStr',
                'selectedCompany',
                'selectedDepartment'
            ));
        } catch (\Throwable $e) {
            Log::error('[RegisterController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load registration form');
        }
    }

    public function select2Companies(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;

        $companies = MCompany::query()
            ->select('id', 'company_name')
            ->when($q, fn($qq) => $qq->where('company_name', 'like', "%{$q}%"))
            ->orderBy('company_name')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'results' => $companies->getCollection()->map(fn($c) => [
                'id' => $c->id,
                'text' => $c->company_name,
            ])->values(),
            'pagination' => ['more' => $companies->hasMorePages()],
        ]);
    }

    public function select2Departments(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;

        $companyId = $request->get('company_id'); // sync by company

        if (!$companyId) {
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false],
            ]);
        }

        $departments = MDepartment::query()
            ->select('id', 'department_name', 'company_id')
            ->where('company_id', (int) $companyId)
            ->when($q, fn($qq) => $qq->where('department_name', 'like', "%{$q}%"))
            ->orderBy('department_name')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'results' => $departments->getCollection()->map(fn($d) => [
                'id' => $d->id,
                'text' => $d->department_name,
            ])->values(),
            'pagination' => ['more' => $departments->hasMorePages()],
        ]);
    }

    public function success()
    {
        return view('pages.recruitment.success');
    }

    public function store(CalonKaryawanRegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $tokenStr = $data['token'];

            return DB::transaction(function () use ($data, $tokenStr) {

                $tokenRow = TTokenCalonKaryawan::where('token', $tokenStr)
                    ->lockForUpdate()
                    ->first();

                if (!$tokenRow) {
                    return back()->withErrors(['token' => 'Token tidak valid.'])->withInput();
                }

                if ($tokenRow->is_used) {
                    return view('pages.recruitment.link-used', [
                        'title' => 'Link sudah digunakan',
                        'message' => 'Link ini hanya dapat digunakan satu kali.',
                    ]);
                }

                $company = MCompany::findOrFail($data['m_company_id']);
                $dept    = MDepartment::findOrFail($data['m_department_id']);

                $data['company_name']    = $company->company_name;
                $data['department_name'] = $dept->department_name;

                unset($data['token']);

                $calon = MCalonKaryawan::create($data);

                $tokenRow->update(['is_used' => true]);

                return redirect()->route('recruitment.register.success');
            });

        } catch (\Throwable $e) {
            Log::error('[RegisterController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['general' => 'Gagal submit form. Coba lagi.'])->withInput();
        }
    }
}
