<?php

namespace App\Http\Controllers\Transaksi\LemburKaryawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\LemburKaryawan\LemburKaryawanStoreFormRequest;
use App\Models\MKaryawan;
use App\Models\TLembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LemburKaryawanController extends Controller
{
    private TLembur $lembur;

    public function __construct(TLembur $lembur)
    {
        $this->lembur = $lembur;
    }

    public function index(Request $request)
    {
        try {

            $searchBulanTahun = trim((string) $request->query('searchBulanTahun', ''));
            $searchKaryawan = trim((string) $request->query('searchKaryawan', ''));
            $searchCompany = trim((string) $request->query('searchCompany', ''));
            $searchStatus = trim((string) $request->query('searchStatus', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->lembur->newQuery();

            if ($searchBulanTahun || $searchKaryawan || $searchCompany || $searchStatus) {
                $query->where(function ($query) use ($searchBulanTahun, $searchKaryawan, $searchCompany, $searchStatus) {
                    if ($searchBulanTahun) {
                        $query->whereRaw("to_char(date, 'MM-YYYY') = ?", [$searchBulanTahun]);
                    }
                    if ($searchKaryawan) {
                        $query->where('m_karyawan_id', (int) $searchKaryawan);
                    }

                    if($searchCompany) {
                        $query->where('m_company_id', (int) $searchCompany);
                    }
                    if ($searchStatus) {
                        $query->where('status', 'like', '%' . $searchStatus . '%');
                    }
                });
            }

            $lemburs = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.transaksi.lembur-karyawan.index', compact('lemburs'));

        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load lembur karyawan');
        }
    }

    public function create()
    {
        try {
            return view('pages.transaksi.lembur-karyawan.create');
        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load lembur karyawan');
        }
    }

    public function store(LemburKaryawanStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $lembur = $this->lembur->create([
               'm_karyawan_id' => $data['m_karyawan_id'],
               'nama_karyawan' => $data['nama_karyawan'],
               'm_company_id' => $data['m_company_id'],
               'nama_company' => $data['nama_perusahaan'],
               'date' => $data['date'],
               'note' => $data['note'] ?? null,
               'atasan1_id' => $data['atasan1_id'] ?? null,
               'atasan2_id' => $data['atasan2_id'] ?? null,
               'nama_atasan1' => $data['nama_atasan1'] ?? null,
               'nama_atasan2' => $data['nama_atasan2'] ?? null,
               'durasi_diajukan_menit' => $data['durasi_diajukan_menit'],
                'status' => 'SUBMITED',
            ]);

            return $this->successResponse($lembur, 'Lembur karyawan stored successfully.', 201);

        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store lembur karyawan', 500);
        }
    }

    public function show($id)
    {
        try {

            $lembur = $this->lembur->with('karyawan')->findOrFail($id);

            return view('pages.transaksi.lembur-karyawan.show', compact('lembur'));

        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load lembur karyawan details');
        }
    }

    public function destroy($id)
    {
        try {

            $lembur = $this->lembur->findOrFail($id);
            $lembur->delete();

            return $this->successResponse(null, 'Lembur karyawan deleted successfully.', 200);

        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete lembur karyawan', 500);
        }
    }
    public function companyOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = \App\Models\MCompany::query()->select('id', 'company_name');

            if ($term !== '') $q->where('company_name', 'like', "%{$term}%");

            $paginator = $q->orderBy('company_name')->paginate($perPage, ['*'], 'page', $page);

            $results = $paginator->getCollection()->map(fn ($c) => [
                'id'   => $c->id,
                'text' => $c->company_name,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => ['more' => $paginator->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@companyOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load company options', 500);
        }
    }

    public function karyawanDetail($id)
    {
        try {
            $k = MKaryawan::query()
                ->select([
                    'id',
                    'nama_karyawan',
                    'm_company_id',
                    'nama_company',
                    'atasan1_id',
                    'nama_atasan1',
                    'atasan2_id',
                    'nama_atasan2',
                ])
                ->findOrFail($id);

            return response()->json([
                'id' => $k->id,
                'nama_karyawan' => $k->nama_karyawan,
                'm_company_id' => $k->m_company_id,
                'nama_perusahaan' => $k->nama_company,
                'atasan1_id' => $k->atasan1_id,
                'nama_atasan1' => $k->nama_atasan1,
                'atasan2_id' => $k->atasan2_id,
                'nama_atasan2' => $k->nama_atasan2,
            ]);
        } catch (\Throwable $e) {
            Log::error('[LemburKaryawanController@karyawanDetail] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan detail', 500);
        }
    }

    public function karyawanOptions(Request $request)
    {
        try {

            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = (int) $request->get('perPage', 20);
            $perPage = max(1, min($perPage, 50));

            $q = MKaryawan::query()
                ->select('id', 'nama_karyawan');

            if ($term !== '') {
                $q->where('nama_karyawan', 'like', '%' . $term . '%');
            }

            $paginator = $q->orderBy('nama_karyawan')
                ->paginate($perPage, ['*'], 'page', $page);

            $results = $paginator->getCollection()->map(fn ($c) => [
                'id'   => $c->id,
                'text' => $c->nama_karyawan,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => $paginator->hasMorePages(),
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@karyawanOptions] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan options', 500);
        }
    }
}
