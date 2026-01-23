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
            $searchIsHr      = (string) $request->query('searchIsHr', '');
            $searchCompanyId = (string) $request->query('searchCompanyId', '');
            $perPage         = (int) $request->query('perPage', 10);
            $perPage         = max(1, min($perPage, 100));

            $query = $this->department->newQuery()
                ->with('company:id,company_name')
                ->select(['id','department_name','company_id','is_hr','created_at','updated_at']);

            if ($searchName !== '') {
                $query->where('department_name', 'like', '%' . $searchName . '%');
            }
            if ($searchIsHr !== '') {
                $query->where('is_hr', (int) $searchIsHr);
            }
            if ($searchCompanyId !== '') {
                $query->where('company_id', (int) $searchCompanyId);
            }

            $departments = $query->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            $selectedCompany = null;
            if ($searchCompanyId !== '') {
                $selectedCompany = MCompany::select('id','company_name')->find((int)$searchCompanyId);
            }

            return view('pages.master-data.department.index', compact('departments', 'selectedCompany'));
        } catch (\Throwable $e) {
            Log::error('[DepartmentController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load department data');
        }
    }


    public function companyOptions(Request $request)
    {
        $term    = trim((string) $request->get('q', $request->get('term', '')));
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = (int) $request->get('perPage', 20);
        $perPage = max(1, min($perPage, 50));

        $q = MCompany::query()
            ->select('id', 'company_name');

        if ($term !== '') {
            $q->where('company_name', 'like', '%' . $term . '%');
        }

        $paginator = $q->orderBy('company_name')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $paginator->getCollection()->map(fn ($c) => [
            'id'   => $c->id,
            'text' => $c->company_name,
        ])->values();

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $paginator->hasMorePages(),
            ],
        ]);
    }


    public function create()
    {
        try {

            return view('pages.master-data.department.create');

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

            $department = $this->department
                ->with('company:id,company_name')
                ->findOrFail($id);

            $selectedCompany = $department->company;

            return view('pages.master-data.department.edit', compact('department', 'selectedCompany'));

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
