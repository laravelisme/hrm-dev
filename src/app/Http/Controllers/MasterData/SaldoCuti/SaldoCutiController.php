<?php

namespace App\Http\Controllers\MasterData\SaldoCuti;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\SaldoCuti\SaldoCutiStoreFormRequest;
use App\Http\Requests\MasterData\SaldoCuti\SaldoCutiUpdateFormRequest;
use App\Models\MSaldoCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaldoCutiController extends Controller
{
    private MSaldoCuti $saldoCuti;

    public function __construct(MSaldoCuti $saldoCuti)
    {
        $this->saldoCuti = $saldoCuti;
    }

    public function index(Request $request)
    {
        try {

            $searchJenis = trim((string) $request->query('searchJenis', ''));
            $searchJumlah = trim((string) $request->query('searchJumlah', ''));
            $perPage     = (int) $request->query('perPage', 10);
            $perPage     = max(1, min($perPage, 100));

            $query = $this->saldoCuti->newQuery();

            if ($searchJenis || $searchJumlah) {
                $query->where(function ($query) use ($searchJenis, $searchJumlah) {
                    if ($searchJenis) {
                        $query->where('jenis', 'like', '%' . $searchJenis . '%');
                    }
                    if ($searchJumlah) {
                        $query->where('jumlah', $searchJumlah);
                    }
                });
            }

            $saldoCutis = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
            return view('pages.master-data.saldo-cuti.index', compact('saldoCutis'));

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load jabatan data');
        }
    }

    public function create()
    {
        try {

            return view('pages.master-data.saldo-cuti.create');

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create saldo cuti form');
        }
    }

    public function store(SaldoCutiStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $saldoCuti = $this->saldoCuti->create($data);

            if (!$saldoCuti) {
                return $this->errorResponse(500, 'Failed to store saldo cuti');
            }

            return $this->successResponse($saldoCuti, 'Saldo cuti stored successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store saldo cuti', 500);
        }
    }

    public function show($id)
    {
        try {

            $saldoCuti = $this->saldoCuti->findOrFail($id);

            if (!$saldoCuti) {
                abort(404, 'Saldo cuti not found');
            } else {
                return view('pages.master-data.saldo-cuti.show', compact('saldoCuti'));
            }

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load saldo cuti');
        }
    }

    public function edit($id)
    {
        try {

            $saldoCuti = $this->saldoCuti->findOrFail($id);

            if (!$saldoCuti) {
                abort(404, 'Saldo cuti not found');
            } else {
                return view('pages.master-data.saldo-cuti.edit', compact('saldoCuti'));
            }

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit saldo cuti form');
        }
    }

    public function update(SaldoCutiUpdateFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $saldoCuti = $this->saldoCuti->findOrFail($id);
            $saldoCuti->update($data);

            return $this->successResponse($saldoCuti->fresh(), 'Saldo cuti updated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse(500, 'Failed to update saldo cuti');
        }
    }

    public function destroy($id)
    {
        try {

            $saldoCuti = $this->saldoCuti->findOrFail($id);
            $saldoCuti->delete();

            return $this->successResponse(null, 'Saldo cuti deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse(500, 'Failed to delete saldo cuti');
        }
    }

}
