<?php

namespace App\Http\Controllers\Transaksi\IzinKaryawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\IzinKaryawan\IzinKaryawanStoreFormRequest;
use App\Models\MKaryawan;
use App\Models\TIjin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IzinKaryawanController extends Controller
{
    private TIjin $ijin;

    public function __construct(TIjin $ijin)
    {
        $this->ijin = $ijin;
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

            $query = $this->ijin->newQuery();

            if ($searchBulanTahun || $searchKaryawan || $searchCompany || $searchJenis || $searchStatus) {
                $query->where(function ($query) use ($searchBulanTahun, $searchKaryawan, $searchCompany, $searchJenis, $searchStatus) {
                    if ($searchBulanTahun) {
                        $query->whereRaw("to_char(start_date, 'MM-YYYY') = ?", [$searchBulanTahun]);
                    }
                    if ($searchKaryawan) {
                        $query->where('m_karyawan_id', (int) $searchKaryawan);
                    }

                    if($searchCompany) {
                        $query->where('m_company_id', (int) $searchCompany);
                    }
                    if ($searchJenis) {
                        $query->where('m_jenis_izin_id', (int) $searchJenis);
                    }
                    if ($searchStatus) {
                        $query->where('status', 'like', '%' . $searchStatus . '%');
                    }
                });
            }

            $izins = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.transaksi.izin-karyawan.index', compact('izins'));

        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load izin karyawan');
        }
    }

    public function create()
    {
        try {

            return view('pages.transaksi.izin-karyawan.create');

        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create izin karyawan form');
        }
    }

    public function store(IzinKaryawanStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $izin = $this->ijin->create([
                'm_karyawan_id'    => $data['m_karyawan_id'],
                'nama_karyawan'    => $data['nama_karyawan'],
                'nama_perusahaan'  => $data['nama_perusahaan'],
                'm_company_id'     => $data['m_company_id'],
                'start_date'       => $data['start_date'],
                'end_date'         => $data['end_date'],
                'durasi'           => $data['durasi'],
                'keperluan'        => $data['keperluan'],
                'tanggal_kembali'  => $data['tanggal_kembali'] ?? null,
                'atasan1_id'      => $data['atasan1_id'] ?? null,
                'atasan2_id'      => $data['atasan2_id'] ?? null,
                'm_jenis_izin_id'  => $data['m_jenis_izin_id'],
                'nama_atasan1'    => $data['nama_atasan1'] ?? null,
                'nama_atasan2'    => $data['nama_atasan2'] ?? null,
                'status'           => 'SUBMITED',
            ]);

            return $this->successResponse($izin, 'Izin karyawan created successfully.', 201);

        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store izin karyawan.', 500);
        }
    }

    public function show($id)
    {
        try {

            $izin = $this->ijin
                ->with('karyawan', 'jenisIzin')
                ->findOrFail($id);

            if (!$izin) {
                abort(404, 'Izin karyawan not found');
            }

            return view('pages.transaksi.izin-karyawan.show', compact('izin'));

        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load izin karyawan detail');
        }
    }

    public function destroy($id)
    {
        try {

            $izin = $this->ijin->findOrFail($id);
            $izin->delete();

            return $this->successResponse(null, 'Izin karyawan deleted successfully.', 200);

        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete izin karyawan.', 500);
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
            Log::error('[IzinKaryawanController@companyOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
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
            Log::error('[IzinKaryawanController@karyawanDetail] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan detail', 500);
        }
    }

    public function jenisIzinOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = \App\Models\MJenisIzin::query()->select('id', 'nama_izin');

            if ($term !== '') $q->where('nama_izin', 'like', "%{$term}%");

            $paginator = $q->orderBy('nama_izin')->paginate($perPage, ['*'], 'page', $page);

            $results = $paginator->getCollection()->map(fn ($c) => [
                'id'   => $c->id,
                'text' => $c->nama_izin,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => ['more' => $paginator->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@jenisCutiOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load jenis izin options', 500);
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

    public function approveHr($id)
    {
        try {

            $izin = $this->ijin->findOrFail($id);
            $izin->status = 'APPROVED';
            $izin->hr_verify_date = now();
            $izin->nama_hr_approval = auth()->user()->name;
            $izin->save();

            return $this->successResponse($izin, 'Izin karyawan approved by HR successfully.', 200);

        } catch (\Throwable $e) {
            Log::error('[IzinKaryawanController@approveHr] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to approve izin karyawan by HR.', 500);
        }
    }

}
