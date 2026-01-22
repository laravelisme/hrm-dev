<?php

namespace App\Http\Controllers\MasterData\LokasiKerja;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\LokasiKerja\LokasiKerjaStoreFormRequest;
use App\Http\Requests\MasterData\LokasiKerja\LokasiKerjaUpdateFormRequest;
use App\Models\MLokasiKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LokasiKerjaController extends Controller
{
    private MLokasiKerja $lokasiKerja;

    public function __construct(MLokasiKerja $lokasiKerja)
    {
        $this->lokasiKerja = $lokasiKerja;
    }

    public function index(Request $request)
    {
        try {

            $searchName = trim((string) $request->query('searchName', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->lokasiKerja->newQuery();

            if ($searchName) {
                $query->where(function ($query) use ($searchName) {
                    if ($searchName) {
                        $query->where('name', 'like', '%' . $searchName . '%');
                    }
                });
            }

            $lokasiKerjas = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.master-data.lokasi-kerja.index', compact('lokasiKerjas'));

        } catch (\Throwable $e) {
            Log::error('[LokasiKerjaController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort('500', 'Failed to load lokasi kerja data');
        }
    }

    public function create()
    {
        try {

            return view('pages.master-data.lokasi-kerja.create');

        } catch (\Throwable $e) {
            Log::error('[LokasiKerjaController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load lokasi kerja data');
        }
    }

    public function store(LokasiKerjaStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $lokasiKerja = $this->lokasiKerja->create($data);

            return $this->successResponse($lokasiKerja, 'LokasiKerja created successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[LokasiKerjaController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to create lokasiKerja', 500);
        }
    }

    public function edit($id)
    {
        try {

            $lokasiKerja = $this->lokasiKerja->findOrFail($id);

            if (!$lokasiKerja) {
                abort(404, 'Lokasi Kerja not found');
            }

            return view('pages.master-data.lokasi-kerja.edit', compact('lokasiKerja'));

        } catch (\Throwable $e) {
            Log::error('[LokasiKerjaController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit lokasi kerja form');
        }
    }

    public function update(LokasiKerjaUpdateFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $lokasiKerja = $this->lokasiKerja->findOrFail($id);

            if (!$lokasiKerja) {
                return $this->errorResponse('LokasiKerja not found', 404);
            }

            $lokasiKerja->update($data);

            return $this->successResponse($lokasiKerja->fresh(), 'LokasiKerja updated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[LokasiKerjaController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update lokasiKerja', 500);
        }
    }

    public function destroy($id)
    {
        try {

            $lokasiKerja = $this->lokasiKerja->findOrFail($id);

            if (!$lokasiKerja) {
                return $this->errorResponse('LokasiKerja not found', 404);
            }

            $lokasiKerja->delete();

            return $this->successResponse(null, 'LokasiKerja deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[LokasiKerjaController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete lokasiKerja', 500);
        }
    }
}
