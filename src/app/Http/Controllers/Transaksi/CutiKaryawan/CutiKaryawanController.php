<?php

namespace App\Http\Controllers\Transaksi\CutiKaryawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\CutiKaryawan\CutiKaryawanStoreFormRequest;
use App\Models\MCompany;
use App\Models\MKaryawan;
use App\Models\TCuti;
use App\Models\TSaldoCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CutiKaryawanController extends Controller
{
    private TCuti $cuti;

    public function __construct(TCuti $cuti)
    {
        $this->cuti = $cuti;
    }

    public function index(Request $request)
    {
        try {

            $searchBulanTahun = trim((string) $request->query('searchBulanTahun', ''));
            $searchKaryawan = trim((string) $request->query('searchKaryawan', ''));
            $searchCompany = trim((string) $request->query('searchCompany', ''));
            $searchJenis = trim((string) $request->query('searchJenis', ''));
            $searchStatus = trim((string) $request->query('searchStatus', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->cuti->newQuery();

            if ($searchBulanTahun || $searchKaryawan || $searchCompany || $searchJenis || $searchStatus) {
                $query->where(function ($query) use ($searchBulanTahun, $searchKaryawan, $searchCompany, $searchJenis, $searchStatus) {
                    if ($searchBulanTahun) {
                        $query->whereRaw("DATE_FORMAT(start_date, '%m-%Y') = ?", [$searchBulanTahun]);
                    }
                    if ($searchKaryawan) {
                        $query->where('m_karyawan_id', (int) $searchKaryawan);
                    }

                    if($searchCompany) {
                        $query->where('m_company_id', (int) $searchCompany);
                    }
                    if ($searchJenis) {
                        $query->where('m_jenis_cuti_id', (int) $searchJenis);
                    }
                    if ($searchStatus) {
                        $query->where('status', 'like', '%' . $searchStatus . '%');
                    }
                });
            }

            $cutis = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.transaksi.cuti-karyawan.index', compact('cutis'));


        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load cuti');
        }
    }

    public function create()
    {
        try {
            return view('pages.transaksi.cuti-karyawan.create');

        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create cuti form');
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
            Log::error('[CutiKaryawanController@companyOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
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
            Log::error('[CutiKaryawanController@karyawanDetail] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan detail', 500);
        }
    }


    public function jenisCutiOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = \App\Models\MJenisCuti::query()->select('id', 'nama_cuti');

            if ($term !== '') $q->where('nama_cuti', 'like', "%{$term}%");

            $paginator = $q->orderBy('nama_cuti')->paginate($perPage, ['*'], 'page', $page);

            $results = $paginator->getCollection()->map(fn ($c) => [
                'id'   => $c->id,
                'text' => $c->nama_cuti,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => ['more' => $paginator->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@jenisCutiOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load jenis cuti options', 500);
        }
    }

    public function store(CutiKaryawanStoreFormRequest $request)
    {
        try {

            $data = $request->validated();
            $cutiTahunan = TSaldoCuti::where('m_karyawan_id', $data['m_karyawan_id'])
                ->where('tahun', date('Y'))
                ->first();
            if ($data['m_jenis_cuti_id'] == 4) {
                $data['saldo_sebelumnya'] = $cutiTahunan->saldo;
                $data['saldo_terpakai'] = $data['jumlah_hari'];
                $data['saldo_sisa'] = $cutiTahunan->saldo - $data['jumlah_hari'];
                if ($data['saldo_sisa'] < 0) {
                    return $this->errorResponse('Insufficient cuti tahunan balance', 400);
                }
            }

            $cuti = $this->cuti->create([
                'm_karyawan_id' => $data['m_karyawan_id'],
                'm_company_id' => $data['m_company_id'],
                'nama_karyawan' => $data['nama_karyawan'],
                'nama_perusahaan' => $data['nama_perusahaan'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'jumlah_hari' => $data['jumlah_hari'],
                'tanggal_kembali' => $data['tanggal_kembali'],
                'keperluan' => $data['keperluan'],
                'alamat_selama_cuti' => $data['alamat_selama_cuti'],
                'no_telp' => $data['no_telp'],
                'atasan1_id' => $data['atasan1_id'],
                'atasan2_id' => $data['atasan2_id'],
                'nama_atasan1' => $data['nama_atasan1'],
                'nama_atasan2' => $data['nama_atasan2'],
                'm_jenis_cuti_id' => $data['m_jenis_cuti_id'],
                'status' => 'SUBMITED',
                'saldo_sebelumnya' => $data['saldo_sebelumnya'] ?? 0,
                'saldo_terpakai' => $data['saldo_terpakai'] ?? 0,
                'saldo_sisa' => $data['saldo_sisa'] ?? 0,
            ]);

            if ($cuti) {
                return $this->successResponse($cuti, 'Cuti data stored successfully', 201);
            } else {
                return $this->errorResponse('Failed to store cuti data', 500);
            }

        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store cuti data', 500);
        }
    }

    public function show($id)
    {
        try {
            $cuti = $this->cuti->with('karyawan', 'jenisCuti')->findOrFail($id);

            return view('pages.transaksi.cuti-karyawan.show', compact('cuti'));

        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load cuti details');
        }
    }

    public function destroy($id)
    {
        try {
            $cuti = $this->cuti->findOrFail($id);
            $cuti->delete();

            return $this->successResponse(null, 'Cuti deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[CutiKaryawanController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete cuti', 500);
        }
    }

}
