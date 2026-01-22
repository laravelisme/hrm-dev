<?php

namespace App\Http\Controllers\MasterData\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Department\DepartmentStoreFormRequest;
use App\Http\Requests\MasterData\Department\DepartmentUpdateFormRequest;
use App\Models\MCompany;
use App\Models\MDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    private MDepartment $department;

    public function __construct(MDepartment $department)
    {
        $this->department = $department;
    }

    public function index(Request $request)
    {
        try {
            $searchName      = trim((string) $request->query('searchName', ''));
            $searchIsHr      = (string) $request->query('searchIsHr', ''); // jangan trim biar "0" aman
            $searchCompanyId = (string) $request->query('searchCompanyId', '');
            $perPage         = (int) $request->query('perPage', 10);
            $perPage         = max(1, min($perPage, 100));

            $query = $this->department->newQuery()->with('company:id,company_name');

            if ($searchName !== '' || $searchIsHr !== '' || $searchCompanyId !== '') {
                $query->where(function ($query) use ($searchName, $searchIsHr, $searchCompanyId) {
                    if ($searchName !== '') {
                        $query->where('name', 'like', '%' . $searchName . '%');
                    }
                    if ($searchIsHr !== '') {
                        $query->where('is_hr', $searchIsHr);
                    }
                    if ($searchCompanyId !== '') {
                        $query->where('company_id', $searchCompanyId);
                    }
                });
            }

            $departments = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            $companies = MCompany::select('id', 'company_name')->orderBy('company_name')->get();

            return view('pages.master-data.department.index', compact('departments', 'companies'));
        } catch (\Throwable $e) {
            Log::error('[DepartmentController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load department data');
        }
    }


    public function create()
    {
        try {

            $companies = MCompany::all();
            return view('pages.master-data.department.create', compact('companies'));

        } catch (\Throwable $e) {
            Log::error('[DepartmentController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create department form');
        }
    }

    public function store(DepartmentStoreFormRequest $request)
    {
        try {

            $data = $request->validated();
            $department = $this->department->create($data);

            return $this->successResponse($department, 'Department created successfully.', 201);

        } catch (\Throwable $e) {
            Log::error('[DepartmentController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to create department.', 500);
        }
    }

    public function edit($id)
    {
        try {

            $department = $this->department->findOrFail($id);
            $companies = MCompany::all();
            return view('pages.master-data.department.edit', compact('department', 'companies'));

        } catch (\Throwable $e) {
            Log::error('[DepartmentController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit department form');
        }
    }


    public function update(DepartmentUpdateFormRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $department = $this->department->findOrFail($id);
            $department->update($data);

            return $this->successResponse($department, 'Department updated successfully.', 200);
        } catch (\Throwable $e) {
            Log::error('[DepartmentController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update department.', 500);
        }
    }

    public function destroy($id)
    {
        try {

            $department = $this->department->findOrFail($id);
            $department->delete();

            return $this->successResponse(null, 'Department deleted successfully.', 200);

        } catch (\Throwable $e) {
            Log::error('[DepartmentController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete department.', 500);
        }
    }
}
