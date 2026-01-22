<?php

namespace App\Http\Controllers\MasterData\HariLibur;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\HariLibur\HariLiburStoreFormRequest;
use App\Http\Requests\MasterData\HariLibur\HariLiburUpdateFormRequest;
use App\Models\MCompany;
use App\Models\MHariLibur;
use App\Models\MHariLiburCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            $companies = MCompany::all();

            return view('pages.master-data.hari-libur.create', compact('companies'));

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create hari libur form');
        }
    }

    public function store(HariLiburStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            DB::beginTransaction();

            $hariLibur = $this->hariLibur->create([
                'hari_libur' => $data['hari_libur'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'is_cuti_bersama' => $data['is_cuti_bersama'],
                'is_umum' => $data['is_umum'],
                'is_repeat' => $data['is_repeat'],
            ]);
            if ($hariLibur->is_umum === true) {
                $companies =MCompany::select('id')->get();
                $hariLiburCompaniesData = [];
                foreach ($companies as $company) {
                    $hariLiburCompaniesData[] = [
                        'hari_libur_id' => $hariLibur->id,
                        'company_id' => $company->id,
                    ];
                }

                MHariLiburCompany::insert($hariLiburCompaniesData);
            } else {
                $companyIds = $data['company_ids'] ?? [];
                $hariLiburCompaniesData = [];
                foreach ($companyIds as $companyId) {
                    $hariLiburCompaniesData[] = [
                        'hari_libur_id' => $hariLibur->id,
                        'company_id' => $companyId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                MHariLiburCompany::insert($hariLiburCompaniesData);
            }

            DB::commit();

            return $this->successResponse($hariLibur, 'Hari libur data stored successfully', 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[HariLiburController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store hari libur data', 500);
        }
    }

    public function edit($id)
    {
        try {
            $hariLibur = $this->hariLibur->findOrFail($id);
            $companies = MCompany::all();

            $selectedCompanyIds = MHariLiburCompany::where('hari_libur_id', $hariLibur->id)
                ->pluck('company_id')
                ->toArray();

            return view('pages.master-data.hari-libur.edit', compact('hariLibur', 'companies', 'selectedCompanyIds'));

        } catch (\Throwable $e) {
            Log::error('[HariLiburController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit hari libur form');
        }
    }


    public function update(HariLiburUpdateFormRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $companyIds = $data['company_ids'] ?? [];
            unset($data['company_ids']);

            $hariLibur = DB::transaction(function () use ($id, $data, $companyIds) {
                $hariLibur = $this->hariLibur->findOrFail($id);
                $hariLibur->update($data);

                MHariLiburCompany::where('hari_libur_id', $hariLibur->id)->delete();

                $ids = $hariLibur->is_umum
                    ? MCompany::pluck('id')->all()
                    : $companyIds;

                $ids = array_values(array_unique($ids));

                $now = now();
                $rows = [];
                foreach ($ids as $cid) {
                    $rows[] = [
                        'hari_libur_id' => $hariLibur->id,
                        'company_id'    => $cid,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }

                if (!empty($rows)) {
                    MHariLiburCompany::insert($rows);
                }

                return $hariLibur;
            });

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
