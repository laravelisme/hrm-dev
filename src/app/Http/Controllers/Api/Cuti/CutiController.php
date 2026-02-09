<?php

namespace App\Http\Controllers\Api\Cuti;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cuti\CutiApprovalFormRequest;
use App\Http\Requests\Api\Cuti\CutiStoreFormRequest;
use App\Http\Requests\Api\Izin\IzinApprovalFormRequest;
use App\Models\MKaryawan;
use App\Models\TCuti;
use App\Models\TSaldoCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CutiController extends Controller
{
    private TCuti $cuti;

    public function __construct(TCuti $cuti)
    {
        $this->cuti = $cuti;
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


            $query = $this->cuti->newQuery();

            if ($karyawan) {
                $query->where('m_karyawan_id', $karyawan->id);
            }
            if ($searchStartDate) {
                $query->whereDate('start_date', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $query->whereDate('end_date', '<=', $searchEndDate);
            }

            $cutis = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return $this->successResponse($cutis, 'Cuti fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[IzinController@index] '.$e->getMessage());
            return $this->errorResponse('Failed to load cuti', 500);
        }
    }

    public function store(CutiStoreFormRequest $request)
    {
        try {

            $data = $request->validated();
            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            if (!$karyawan) {
                return $this->errorResponse('Karyawan not found for the user', 404);
            }

            DB::beginTransaction();

            $cuti = $this->cuti->create([
                'm_karyawan_id' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'jumlah_hari' => $data['jumlah_hari'] ?? null,
                'keperluan' => $data['keperluan'],
                'alamat_selama_cuti' => $data['alamat_selama_cuti'] ?? null,
                'no_telp' => $data['no_telepon'] ?? null,
                'm_jenis_cuti_id' => $data['id_jenis'],
                'tanggal_kembali' => $data['tgl_kembali_kerja'] ?? null,
                'atasan1_id' => $karyawan->atasan1_id,
                'atasan2_id' => $karyawan->atasan2_id,
                'nama_atasan1' => $karyawan->nama_atasan1 ??  null,
                'nama_atasan2' => $karyawan->nama_atasan2 ??  null,
                'm_company_id' => $karyawan->m_company_id,
                'nama_perusahaan' => $karyawan->nama_company,
            ]);

            if ($data['id_jenis'] == 4) {
                $saldoCuti = TSaldoCuti::where('m_karyawan_id', $karyawan->id)->where('tahun', date('Y'))->first();
                if($saldoCuti->sisa_saldo < $data['jumlah_hari']) {
                    DB::rollBack();
                    return $this->errorResponse('Insufficient cuti saldo', 400);
                }
                $saldoCuti->sisa_saldo = $saldoCuti->sisa_saldo - $data['jumlah_hari'];
                $saldoCuti->save();

                $cuti->saldo_sebelumnya = $saldoCuti->sisa_saldo + $data['jumlah_hari'];
                $cuti->saldo_terpakai = $data['jumlah_hari'];
                $cuti->saldo_sisa = $saldoCuti->sisa_saldo;
                $cuti->save();
            }

            DB::commit();

            return $this->successResponse($cuti, 'Cuti stored successfully', 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[CutiController@store] '.$e->getMessage());
            return $this->errorResponse('Failed to store cuti', 500);
        }
    }

    public function getListApproval(Request $request)
    {
        try {

            $perPage = (int) $request->query('perPage', 10);
            $perPage = max(1, min($perPage, 100));

            $searchKaryawanName = trim((string) $request->query('searchKaryawanName', ''));

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $listApprovalQuery = $this->cuti->newQuery();

            if ($karyawan) {
                $listApprovalQuery->where(function ($query) use ($karyawan) {
                    $query->where('atasan1_id', $karyawan->id)
                        ->orWhere('atasan2_id', $karyawan->id);
                });
            }

            $listApprovalQuery->whereIn('status', [
                'SUBMITED',
                'PENDING_APPROVED',
            ]);

            if ($searchKaryawanName) {
                $listApprovalQuery->where('nama_karyawan', 'like', '%' . $searchKaryawanName . '%');
            }

            $listApproval = $listApprovalQuery
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            return $this->successResponse(
                $listApproval,
                'Izin approval list fetched successfully',
                200
            );

        } catch (\Throwable $e) {
            Log::error('[IzinController@getListApproval] '.$e->getMessage());
            return $this->errorResponse('Failed to load cuti approval list', 500);
        }
    }

    public function showCuti($id)
    {
        try {

            $cuti = $this->cuti->find($id);
            if (!$cuti) {
                return $this->errorResponse('Cuti not found', 404);
            }

            return $this->successResponse($cuti, 'Cuti details fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[CutiController@showIzin] '.$e->getMessage());
            return $this->errorResponse('Failed to load cuti details', 500);
        }
    }

    public function approvalCuti(CutiApprovalFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $cuti = $this->cuti->find($id);
            if (!$cuti) {
                return $this->errorResponse('Cuti not found', 404);
            }

            if ($cuti->atasan1_id === $karyawan->id) {
                $cuti->note_atasan1 = $data['note_approval'] ?? null;
                $cuti->atasan_1_approval_date = now();
                if ($data['status_approval'] === 'APPROVED') {
                    $cuti->is_approved_atasan1 = true;
                    $cuti->status = 'PENDING_APPROVED';
                    $cuti->save();
                    return $this->successResponse($cuti, 'Cuti approved by Atasan 1 successfully', 200);
                } elseif ($data['status_approval'] === 'REJECTED') {
                    $cuti->status = 'REJECTED';
                    $cuti->save();
                    return $this->successResponse($cuti, 'Cuti rejected by Atasan 1 successfully', 200);
                }
            } elseif ($cuti->atasan2_id === $karyawan->id) {
                $cuti->note_atasan2 = $data['note_approval'] ?? null;
                $cuti->atasan_2_approval_date = now();
                if ($data['status_approval'] === 'APPROVED') {
                    $cuti->is_approved_atasan2 = true;
                    $cuti->status = 'APPROVED';
                    $cuti->save();
                    return $this->successResponse($cuti, 'Cuti approved by Atasan 2 successfully', 200);
                } elseif ($data['status_approval'] === 'REJECTED') {
                    $cuti->status = 'REJECTED';
                    $cuti->save();
                    return $this->successResponse($cuti, 'Cuti rejected by Atasan 2 successfully', 200);
                }
            } else {
                return $this->errorResponse('You are not authorized to approve this cuti', 403);
            }

            return $this->errorResponse('Invalid approval status', 400);


        } catch (\Throwable $e) {
            Log::error('[CutiController@approvalCuti] '.$e->getMessage());
            return $this->errorResponse('Failed to approve cuti', 500);
        }
    }
}
