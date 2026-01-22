<?php

namespace App\Http\Controllers\MasterData\HariLibur;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\HariLibur\HariLiburStoreFormRequest;
use App\Models\MHariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HariLiburController extends Controller
{
    private MHariLibur $hariLibur;

    public function __construct(MHariLibur $hariLibur)
    {
        $this->hariLibur = $hariLibur;
    }

    public function index(Request $request)
    {
        try {

            $searchName = trim((string) $request->query('searchName', ''));
            $searchTahun = trim((string) $request->query('searchTahun', ''));
            $searchIsBersama = trim((string) $request->query('searchIsBersama', ''));
            $searchIsUmum = trim((string) $request->query('searchIsUmum', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->hariLibur->newQuery();

            if ($searchName || $searchTahun || $searchIsBersama || $searchIsUmum) {
                $query->where(function ($query) use ($searchName, $searchTahun, $searchIsBersama, $searchIsUmum) {
                    if ($searchName) {
                        $query->where('hari_libur', 'like', '%' . $searchName . '%');
                    }
                    if ($searchTahun) {
                        $query->whereYear('tanggal_mulai', $searchTahun);
                    }
                    if ($searchIsBersama) {
                        $query->where('is_cuti_bersama', $searchIsBersama);
                    }
                    if ($searchIsUmum) {
                        $query->where('is_umum', $searchIsUmum);
                    }
                });
            }

            $hariLiburs = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
            return view('pages.master-data.hari-libur.index', compact('hariLiburs'));

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load hari libur data');
        }
    }

    public function create()
    {
        try {

            return view('pages.master-data.hari-libur.create');

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create hari libur form');
        }
    }

    public function store(HariLiburStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $hariLibur = $this->hariLibur->create($data);

            if ($hariLibur) {
                return $this->successResponse($hariLibur, 'Hari libur data stored successfully', 201);
            } else {
                return $this->errorResponse('Failed to store hari libur data', 500);
            }

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store hari libur data', 500);
        }
    }

    public function edit($id)
    {
        try {

            $hariLibur = $this->hariLibur->findOrFail($id);
            return view('pages.master-data.hari-libur.edit', compact('hariLibur'));

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit hari libur form');
        }
    }

    public function update(HariLiburStoreFormRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $hariLibur = $this->hariLibur->findOrFail($id);
            $hariLibur->update($data);

            return $this->successResponse($hariLibur->fresh(), 'Hari libur data updated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update hari libur data', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $hariLibur = $this->hariLibur->findOrFail($id);
            $hariLibur->delete();

            return $this->successResponse($hariLibur, 'Hari libur data deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete hari libur data', 500);
        }
    }
}
