<?php

namespace App\Http\Controllers\Api\SP;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SP\SPStoreFormRequest;
use App\Models\MKaryawan;
use App\Models\TSp;
use App\Models\TSpDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SPController extends Controller
{
    private  TSp $sp;
    private TSpDetail $spDetail;

    public function __construct(TSp $sp, TSpDetail $spDetail)
    {
        $this->sp = $sp;
        $this->spDetail = $spDetail;
    }

    public function index(Request $request)
    {
        try {
            $searchStartDate = trim((string) $request->query('searchStartDate', ''));
            $searchEndDate   = trim((string) $request->query('searchEndDate', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $query = $this->sp->where('status', 'APPROVED')->newQuery();

            if ($karyawan) {
                $query->where('m_karyawan_id', $karyawan->id);
            }
            if ($searchStartDate) {
                $query->whereDate('start_date', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $query->whereDate('end_date', '<=', $searchEndDate);
            }

            $sps = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return $this->successResponse($sps, 'SP fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SPController@index] '.$e->getMessage());
            return $this->errorResponse('Failed to load sp', 500);
        }
    }

    public function store(SPStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            if($karyawan->m_jabatan_id !== 3){
                return $this->errorResponse('Unauthorized to create SP', 403);
            }

            $karyawanSp = MKaryawan::findOrFail($data['m_karyawan_id']);
            if (!$karyawanSp) {
                return $this->errorResponse('Karyawan not found for the SP', 404);
            }

            if($karyawanSp->atasan1_id !== $karyawan->id && $karyawanSp->atasan2_id !== $karyawan->id){
                return $this->errorResponse('Unauthorized to create SP1', 403);
            }

            DB::beginTransaction();

            $sp = $this->sp->create([
                'm_karyawan_id' => $karyawanSp->id,
                'nama_karyawan' => $karyawanSp->nama_karyawan,
                'm_jenis_sp_id' => $data['m_jenis_sp_id'],
                'tanggal_start' => $data['tanggal_start'],
                'tanggal_end' => $data['tanggal_end'],
                'm_company_id' => $karyawanSp->m_company_id,
                'nama_perusahaan' => $karyawanSp->nama_company,
                'atasan_id' => $karyawan->id,
                'nama_atasan' => $karyawan->nama_karyawan,
                'atasan_note' => $data['atasan_note'] ?? null,
                'create_by' => $karyawan->nama_karyawan,
            ]);

            foreach ($data['details'] as $detail) {

                if (isset($detail['file_pendukung'])) {
                    $file = $detail['file_pendukung'];
                    $folder = 'sp_files/' . $karyawanSp->kode_karyawan;
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($folder, $fileName, 'public');
                    $detail['file_pendukung'] = $filePath;
                } else {
                    $detail['file_pendukung'] = null;
                }

                $this->spDetail->create([
                    't_sp_id' => $sp->id,
                    'jenis' => $detail['jenis'],
                    'keterangan' => $detail['keterangan'] ?? null,
                    'file_pendukung' => $detail['file_pendukung'],
                ]);
            }

            DB::commit();

            return $this->successResponse($sp, 'SP created successfully', 201);


        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[SPController@store] '.$e->getMessage());
            return $this->errorResponse('Failed to store sp', 500);
        }
    }

    public function listSpApproval(Request $request)
    {
        try {

            $searchStartDate = trim((string) $request->query('searchStartDate', ''));
            $searchEndDate   = trim((string) $request->query('searchEndDate', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $query = $this->sp->where('atasan_id', $karyawan->id)->newQuery();
            if ($searchStartDate) {
                $query->whereDate('tanggal_start', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $query->whereDate('tanggal_end', '<=', $searchEndDate);
            }
            $spApprovals = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return $this->successResponse($spApprovals, 'SP approvals fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SPController@listSpApproval] '.$e->getMessage());
            return $this->errorResponse('Failed to load sp approvals', 500);
        }
    }

    public function showSP($id)
    {
        try {

            $sp = $this->sp->with('details')->findOrFail($id);

            return $this->successResponse($sp, 'SP details fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SPController@showSP] '.$e->getMessage());
            return $this->errorResponse('Failed to load sp details', 500);
        }
    }


}
