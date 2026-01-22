<?php

namespace App\Http\Controllers\MasterData\Jabatan;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Jabatan\JabatanAddFormRequest;
use App\Http\Requests\MasterData\Jabatan\JabatanEditFormRequest;
use App\Http\Requests\MasterData\Jabatan\JabatanStoreFormRequest;
use App\Http\Requests\MasterData\Jabatan\JabatanUpdateFormRequest;
use App\Models\MJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JabatanController extends Controller
{
    private MJabatan $jabatan;

    public function __construct(MJabatan $jabatan)
    {
        $this->jabatan = $jabatan;
    }

    public function index(Request $request)
    {
        try {
            $searchName  = trim((string) $request->query('searchName', ''));
            $searchKode  = trim((string) $request->query('searchKode', ''));
            $searchLevel = trim((string) $request->query('searchLevel', ''));
            $perPage     = (int) $request->query('perPage', 10);
            $perPage     = max(1, min($perPage, 100));

            $query = $this->jabatan->newQuery();

            if ($searchName || $searchKode || $searchLevel) {
                $query->where(function ($query) use ($searchName, $searchKode, $searchLevel) {
                    if ($searchName) {
                        $query->where('name', 'like', '%' . $searchName . '%');
                    }
                    if ($searchLevel) {
                        $query->where('level', $searchLevel);
                    }
                    if ($searchKode) {
                        $query->where('kode', $searchKode);
                    }
                });
            }

            $jabatans = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.master-data.jabatan.index', compact('jabatans'));

        } catch (\Throwable $e) {
            Log::error('[JabatanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load jabatan data');
        }
    }

    public function create()
    {
        try {
            return view('pages.master-data.jabatan.create');
        } catch (\Throwable $e) {
            Log::error('[JabatanController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create jabatan form');
        }
    }

    public function store(JabatanStoreFormRequest $request)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $jabatan = $this->jabatan->create($data);

            DB::commit();

            return $this->successResponse($jabatan, 'Jabatan stored successfully', 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[JabatanController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store jabatan', 500);
        }
    }

    public function edit($id)
    {
        try {
            $jabatan = $this->jabatan->findOrFail($id);
            return view('pages.master-data.jabatan.edit', compact('jabatan'));
        } catch (\Throwable $e) {
            Log::error('[JabatanController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit jabatan form');
        }
    }

    public function update(JabatanUpdateFormRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $jabatan = $this->jabatan->findOrFail($id);

           $jabatan->update($data);

            return $this->successResponse($jabatan->fresh(), 'Jabatan updated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[JabatanController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update jabatan', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $jabatan = $this->jabatan->findOrFail($id);

            DB::transaction(function () use ($jabatan) {
                $jabatan->delete();
            });

            return $this->successResponse(null, 'Jabatan deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[JabatanController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete jabatan', 500);
        }
    }

    public function show($id)
    {
        try {
            $jabatan = $this->jabatan->findOrFail($id);
            return view('pages.master-data.jabatan.show', compact('jabatan'));
        } catch (\Throwable $e) {
            Log::error('[JabatanController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load jabatan detail');
        }
    }
}
