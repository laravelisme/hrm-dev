<?php

namespace App\Http\Controllers\MasterData\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Company\CompanyStoreFormRequest;
use App\Http\Requests\MasterData\Company\CompanyUpdateFormRequest;
use App\Models\MCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    private MCompany $company;

    public function __construct(MCompany $company)
    {
        $this->company = $company;
    }

    public function index(Request $request)
    {
        try {

            $searchName = trim((string) $request->query('searchName', ''));
            $searchLevel = trim((string) $request->query('searchLevel', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->company->newQuery();

            if ($searchName || $searchLevel) {
                $query->where(function ($query) use ($searchName, $searchLevel) {
                    if ($searchName) {
                        $query->where('company_name', 'like', '%' . $searchName . '%');
                    }
                    if ($searchLevel) {
                        $query->where('level', $searchLevel);
                    }
                });
            }

            $companies = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
            return view('pages.master-data.company.index', compact('companies'));

        } catch (\Throwable $e) {
            Log::error('[CompanyController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load company data');
        }
    }

    public function create()
    {
        try {

            return view('pages.master-data.company.create');

        } catch (\Throwable $e) {
            Log::error('[CompanyController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create company form');
        }
    }

    public function store(CompanyStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $company = $this->company->create($data);

            if ($company) {
                return $this->successResponse($company, 'Company data stored successfully', 201);
            } else {
                return $this->errorResponse('Failed to store company data', 500);
            }

        } catch (\Throwable $e) {
            Log::error('[CompanyController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store company data', 500);
        }
    }

    public function edit($id)
    {
        try {

            $company = $this->company->findOrFail($id);
            return view('pages.master-data.company.edit', compact('company'));

        } catch (\Throwable $e) {
            Log::error('[CompanyController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit company form');
        }
    }

    public function update(CompanyUpdateFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $company = $this->company->findOrFail($id);
            $updated = $company->update($data);

            if ($updated) {
                return $this->successResponse($updated, 'Company data updated successfully', 200);
            } else {
                return $this->errorResponse('Failed to update company data', 500);
            }

        } catch (\Throwable $e) {
            Log::error('[CompanyController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update company data', 500);
        }
    }

    public function destroy($id)
    {
        try {

            $company = $this->company->findOrFail($id);
            $deleted = $company->delete();

            if ($deleted) {
                return $this->successResponse($deleted, 'Company data deleted successfully', 200);
            } else {
                return $this->errorResponse('Failed to delete company data', 500);
            }

        } catch (\Throwable $e) {
            Log::error('[CompanyController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete company data', 500);
        }
    }
}
