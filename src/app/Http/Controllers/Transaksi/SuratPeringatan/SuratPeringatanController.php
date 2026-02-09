<?php

namespace App\Http\Controllers\Transaksi\SuratPeringatan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\SuratPeringatan\SuratPeringatanApproveFormRequest;
use App\Http\Requests\Transaksi\SuratPeringatan\SuratPeringatanStoreFormRequest;
use App\Models\MKaryawan;
use App\Models\TSp;
use App\Models\TSpDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SuratPeringatanController extends Controller
{
    private TSp $sp;

    public function __construct(TSp $sp)
    {
        $this->sp = $sp;
    }

    public function index(Request $request)
    {
        try {
            $searchBulanTahun = trim((string) $request->query('searchBulanTahun', '')); // MM-YYYY
            $searchKaryawan   = trim((string) $request->query('searchKaryawan', ''));   // id (select2)
            $searchCompany    = trim((string) $request->query('searchCompany', ''));    // id (select2)
            $searchStatus     = trim((string) $request->query('searchStatus', ''));     // enum
            $searchJenis      = trim((string) $request->query('searchJenis', ''));      // id (select2)
            $searchNomor      = trim((string) $request->query('searchNomor', ''));      // string
            $perPage          = (int) $request->query('perPage', 10);
            $perPage          = max(1, min($perPage, 100));

            $query = $this->sp->newQuery();

            if ($searchBulanTahun || $searchKaryawan || $searchCompany || $searchStatus || $searchJenis || $searchNomor) {
                $query->where(function ($q) use ($searchBulanTahun, $searchKaryawan, $searchCompany, $searchStatus, $searchJenis, $searchNomor) {
                    if ($searchBulanTahun) {
                        $q->whereRaw("to_char(tanggal_surat, 'MM-YYYY') = ?", [$searchBulanTahun]);
                    }

                    if ($searchKaryawan !== '') {
                        if (ctype_digit($searchKaryawan)) {
                            $q->where('m_karyawan_id', (int) $searchKaryawan);
                        } else {
                            $q->where('nama_karyawan', 'ilike', '%' . $searchKaryawan . '%');
                        }
                    }

                    if ($searchCompany) {
                        $q->where('m_company_id', (int) $searchCompany);
                    }

                    if ($searchStatus) {
                        $q->where('status', $searchStatus);
                    }

                    if ($searchJenis) {
                        $q->where('m_jenis_sp_id', (int) $searchJenis);
                    }

                    if ($searchNomor) {
                        $q->where('nomor', 'ilike', '%' . $searchNomor . '%');
                    }
                });
            }

            $sps = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.transaksi.surat-peringatan.index', compact('sps'));
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load surat peringatan');
        }
    }

    public function create()
    {
        try {
            return view('pages.transaksi.surat-peringatan.create');
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create surat peringatan form');
        }
    }

    public function store(SuratPeringatanStoreFormRequest $request)
    {
        try {
            $data = $request->validated();

            $fileSuratPath = null;
            if ($request->hasFile('file_surat')) {
                $fileSuratPath = $request->file('file_surat')->store('sp/file_surat', 'public');
            }

            DB::beginTransaction();

            $sp = $this->sp->create([
                'm_karyawan_id'    => $data['m_karyawan_id'],
                'nama_karyawan'    => $data['nama_karyawan'],
                'm_company_id'     => $data['m_company_id'],
                'nama_perusahaan'  => $data['nama_perusahaan'],
                'm_jenis_sp_id'    => $data['m_jenis_sp_id'],
                'nomor'            => $data['nomor'] ?? null,

                'tanggal_surat'    => $data['tanggal_surat'],
                'tanggal_start'    => $data['tanggal_start'],
                'tanggal_end'      => $data['tanggal_end'],

                'create_by'        => $request->user()?->name ?? $request->user()?->email ?? null,

                'atasan_id'        => $data['atasan_id'] ?? null,
                'nama_atasan'      => $data['nama_atasan'] ?? null,
                'atasan_note'      => $data['atasan_note'] ?? null,

                'file_surat'       => $fileSuratPath,
                'status'           => 'SUBMITED',
                'hr_approved'      => false,
            ]);

            $details = $data['details'] ?? [];
            foreach ($details as $i => $d) {
                $filePendukungPath = null;

                if ($request->hasFile("details.$i.file_pendukung")) {
                    $filePendukungPath = $request->file("details.$i.file_pendukung")
                        ->store('sp/file_pendukung', 'public');
                }

                TSpDetail::create([
                    't_sp_id'        => $sp->id,
                    'jenis'          => $d['jenis'],
                    'keterangan'     => $d['keterangan'],
                    'file_pendukung' => $filePendukungPath,
                ]);
            }

            DB::commit();

            return $this->successResponse($sp, 'Surat peringatan stored successfully.', 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[SuratPeringatanController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store surat peringatan', 500);
        }
    }

    public function show($id)
    {
        try {
            $sp = $this->sp
                ->with(['details'])
                ->findOrFail($id);

            return view('pages.transaksi.surat-peringatan.show', compact('sp'));
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@show] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load surat peringatan detail');
        }
    }

    public function destroy($id)
    {
        try {
            $sp = $this->sp->with('details')->findOrFail($id);

            // delete files (optional tapi bagus)
            if (!empty($sp->file_surat)) {
                Storage::disk('public')->delete($sp->file_surat);
            }
            foreach ($sp->details ?? [] as $d) {
                if (!empty($d->file_pendukung)) {
                    Storage::disk('public')->delete($d->file_pendukung);
                }
            }

            $sp->delete();

            return $this->successResponse(null, 'Surat peringatan deleted successfully.', 200);
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete surat peringatan', 500);
        }
    }

    // =========================
    // Options endpoints (select2)
    // =========================

    public function companyOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = \App\Models\MCompany::query()->select('id', 'company_name');
            if ($term !== '') $q->where('company_name', 'ilike', "%{$term}%");

            $p = $q->orderBy('company_name')->paginate($perPage, ['*'], 'page', $page);

            $results = $p->getCollection()->map(fn ($c) => [
                'id' => $c->id,
                'text' => $c->company_name,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => ['more' => $p->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@companyOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load company options', 500);
        }
    }

    public function karyawanOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $q = MKaryawan::query()->select('id', 'nama_karyawan');
            if ($term !== '') $q->where('nama_karyawan', 'ilike', "%{$term}%");

            $p = $q->orderBy('nama_karyawan')->paginate($perPage, ['*'], 'page', $page);

            $results = $p->getCollection()->map(fn ($k) => [
                'id' => $k->id,
                'text' => $k->nama_karyawan,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => ['more' => $p->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@karyawanOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan options', 500);
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
                    'atasan2_id',
                    'nama_atasan1',
                    'nama_atasan2',
                ])
                ->findOrFail($id);

            return response()->json([
                'id' => $k->id,
                'nama_karyawan' => $k->nama_karyawan,
                'm_company_id' => $k->m_company_id,
                'nama_perusahaan' => $k->nama_company,
                'atasan_id' => $k->atasan2_id ?? $k->atasan1_id,
                'nama_atasan' => $k->nama_atasan2 ?? $k->nama_atasan1,
            ]);
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@karyawanDetail] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan detail', 500);
        }
    }

    public function jenisSpOptions(Request $request)
    {
        try {
            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = max(1, min((int) $request->get('perPage', 20), 50));

            $table = 'm_jenis_sps';
            $labelCol = 'name';
            foreach (['nama_sp', 'nama_jenis_sp', 'name', 'jenis', 'nama'] as $cand) {
                if (Schema::hasColumn($table, $cand)) { $labelCol = $cand; break; }
            }

            $q = \App\Models\MJenisSp::query()->select('id', $labelCol);

            if ($term !== '') $q->where($labelCol, 'ilike', "%{$term}%");

            $p = $q->orderBy($labelCol)->paginate($perPage, ['*'], 'page', $page);

            $results = $p->getCollection()->map(function ($row) use ($labelCol) {
                return [
                    'id' => $row->id,
                    'text' => (string) ($row->{$labelCol} ?? 'Jenis SP'),
                ];
            })->values();

            return response()->json([
                'results' => $results,
                'pagination' => ['more' => $p->hasMorePages()],
            ]);
        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@jenisSpOptions] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to load jenis sp options', 500);
        }
    }

    public function approveHr(SuratPeringatanApproveFormRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $sp = $this->sp->findOrFail($id);

            $karyawan = MKaryawan::findOrFail($sp->m_karyawan_id);

            if ($request->hasFile('file_surat')) {
                $file = $data['file_surat'];
                $folder = 'sp_files_report/' . $karyawan->kode_karyawan;
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs($folder, $fileName, 'public');
                $data['file_surat'] = $filePath;
            }

            $sp->hr_note     = $data['hr_note'] ?? null;
            $sp->hr_approved = $data['status_approval'] === 'APPROVED' ? true : false;
            $sp->hr_approval_date = now();
            $sp->status = $data['status_approval'];
            $sp->nomor = $data['nomor'] ?? $sp->nomor;
            $sp->file_surat = $data['file_surat'] ?? $sp->file_surat;
            $sp->tanggal_surat = $data['tanggal_surat'] ?? $sp->tanggal_surat;

            $sp->save();

            return $this->successResponse($sp, 'Surat peringatan HR approval updated successfully.', 200);

        } catch (\Throwable $e) {
            Log::error('[SuratPeringatanController@approveHr] '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return $this->errorResponse('Failed to approve surat peringatan', 500);
        }
    }
}
