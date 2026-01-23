<?php

namespace App\Http\Controllers\MasterData\GrupJamKerja;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\GrupJamKerja\GrupJamKerjaStoreFormRequest;
use App\Http\Requests\MasterData\GrupJamKerja\GrupJamKerjaUpdateFormRequest;
use App\Models\MGrupJamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GrupJamKerjaController extends Controller
{
    private MGrupJamKerja $grupJamKerja;

    public function __construct(MGrupJamKerja $grupJamKerja)
    {
        $this->grupJamKerja = $grupJamKerja;
    }

    public function index(Request $request)
    {
        try {
            $searchName = trim((string) $request->query('searchName', ''));
            $perPage = (int) $request->query('perPage', 10);
            $perPage = max(1, min($perPage, 100));

            $query = $this->grupJamKerja->newQuery();

            if ($searchName !== '') {
                $query->where('name', 'like', '%' . $searchName . '%');
            }

            $grupJamKerjas = $query->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            return view('pages.master-data.grup-jam-kerja.index', compact('grupJamKerjas'));
        } catch (\Throwable $e) {
            Log::error('[GrupJamKerjaController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load grup jam kerja data');
        }
    }

    public function create()
    {
        try {
            return view('pages.master-data.grup-jam-kerja.create');
        } catch (\Throwable $e) {
            Log::error('[GrupJamKerjaController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create grup jam kerja form');
        }
    }

    public function store(GrupJamKerjaStoreFormRequest $request)
    {
        try {
            $data = $request->validated();
            $grupJamKerja = $this->grupJamKerja->create($data);

            return $this->successResponse($grupJamKerja, 'Grup jam kerja created successfully.', 201);
        } catch (\Throwable $e) {
            Log::error('[GrupJamKerjaController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to create grup jam kerja.', 500);
        }
    }

    public function edit($id)
    {
        try {
            $grupJamKerja = $this->grupJamKerja->findOrFail($id);
            return view('pages.master-data.grup-jam-kerja.edit', compact('grupJamKerja'));
        } catch (\Throwable $e) {
            Log::error('[GrupJamKerjaController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit grup jam kerja form');
        }
    }

    public function update(GrupJamKerjaUpdateFormRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $grupJamKerja = $this->grupJamKerja->findOrFail($id);
            $grupJamKerja->update($data);

            return $this->successResponse($grupJamKerja, 'Grup jam kerja updated successfully.', 200);
        } catch (\Throwable $e) {
            Log::error('[GrupJamKerjaController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update grup jam kerja.', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $grupJamKerja = $this->grupJamKerja->findOrFail($id);
            $grupJamKerja->delete();

            return $this->successResponse(null, 'Grup jam kerja deleted successfully.', 200);
        } catch (\Throwable $e) {
            Log::error('[GrupJamKerjaController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete grup jam kerja.', 500);
        }
    }
}
