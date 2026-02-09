<?php

namespace App\Http\Controllers\Api\Lembur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Lembur\LemburApproveFormRequest;
use App\Http\Requests\Api\Lembur\LemburStoreFormRequest;
use App\Models\MKaryawan;
use App\Models\TLembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LemburController extends Controller
{
    private TLembur $lembur;

    public function __construct(TLembur $lembur)
    {
        $this->lembur = $lembur;
    }

    public function index(Request $request)
    {
        try {
            $searchStartDate = trim((string) $request->query('searchStartDate', ''));
            $searchEndDate   = trim((string) $request->query('searchEndDate', ''));
            $searchStatus   = trim((string) $request->query('searchStatus', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->lembur->newQuery();

            if ($searchStartDate) {
                $query->whereDate('date', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $query->whereDate('date', '<=', $searchEndDate);
            }
            if ($searchStatus) {
                $query->where('status', $searchStatus);
            }

            $lemburs = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return $this->successResponse($lemburs, 'Lembur fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[LemburController@index] '.$e->getMessage());
            return $this->errorResponse('Failed to load lembur', 500);
        }
    }

    public function store(LemburStoreFormRequest $request)
    {
        try {

            $data =    $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $lembur = $this->lembur->create([
                'm_karyawan_id' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'm_company_id' => $karyawan->m_company_id,
                'nama_company' => $karyawan->nama_company,
                'date' => $data['date'],
                'note' => $data['note'] ?? null,
                'status' => 'SUBMITED',
                'atasan1_id' => $karyawan->atasan1_id,
                'nama_atasan1' => $karyawan->nama_atasan1,
                'atasan2_id' => $karyawan->atasan2_id,
                'nama_atasan2' => $karyawan->nama_atasan2,
                'durasi_diajukan_menit' => $data['durasi_diajukan_menit'],
            ]);

            return $this->successResponse($lembur, 'Lembur created successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[LemburController@store] '.$e->getMessage());
            return $this->errorResponse('Failed to store lembur', 500);
        }
    }

    public function getListApproval(Request $request)
    {
        try {

            $searchStartDate = trim((string) $request->query('searchStartDate', ''));
            $searchEndDate   = trim((string) $request->query('searchEndDate', ''));
            $searchStatus   = trim((string) $request->query('searchStatus', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $listApprovalQuery = $this->lembur->newQuery();

            if ($karyawan) {
                $listApprovalQuery->where(function ($query) use ($karyawan) {
                    $query->where('atasan1_id', $karyawan->id)
                        ->orWhere('atasan2_id', $karyawan->id);
                });
            }

            $listApprovalQuery->whereIn('status', [
                'SUBMITED',
                'APPROVED_PENDING',
                'APPROVED',
                'REJECTED'
            ]);

            if ($searchStartDate) {
                $listApprovalQuery->whereDate('date', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $listApprovalQuery->whereDate('date', '<=', $searchEndDate);
            }
            if ($searchStatus) {
                $listApprovalQuery->where('status', $searchStatus);
            }

            $listApproval = $listApprovalQuery
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString();

            return $this->successResponse(
                $listApproval,
                'Lembur approval list fetched successfully',
                200
            );

        } catch (\Throwable $e) {
            Log::error('[LemburController@getListApproval] '.$e->getMessage());
            return $this->errorResponse('Failed to load lembur approvals', 500);
        }
    }

    public function showLembur($id)
    {
        try {

            $lembur = $this->lembur->find($id);
            if (!$lembur) {
                return $this->errorResponse('Lembur not found', 404);
            }

            return $this->successResponse(
                $lembur,
                'Lembur details fetched successfully',
                200
            );

        } catch (\Throwable $e) {
            Log::error('[LemburController@showLembur] '.$e->getMessage());
            return $this->errorResponse('Failed to load lembur details', 500);
        }
    }

    public function approvalLembur(LemburApproveFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $lembur = $this->lembur->find($id);
            if (!$lembur) {
                return $this->errorResponse('Lembur not found', 404);
            }

            if ($lembur->atasan1_id !== $karyawan->id && $lembur->atasan2_id !== $karyawan->id) {
                return $this->errorResponse('Unauthorized to approve lembur', 403);
            }


            if ($lembur->atasan1_id === $karyawan->id) {
                $lembur->atasan1_note = $data['note_approval'] ?? null;
                $lembur->atasan1_approval_date = now();
                if ($data['status_approval'] === 'APPROVED') {
                    $lembur->is_approved_atasan1 = true;
                    $lembur->status = 'APPROVED_PENDING';
                    $lembur->durasi_disetujui_menit = $data['durasi_disetujui_menit'];
                    $lembur->save();
                    return $this->successResponse($lembur, 'Cuti approved by Atasan 1 successfully', 200);
                } elseif ($data['status_approval'] === 'REJECTED') {
                    $lembur->status = 'REJECTED';
                    $lembur->save();
                    return $this->successResponse($lembur, 'Cuti rejected by Atasan 1 successfully', 200);
                }
            } elseif ($lembur->atasan2_id === $karyawan->id) {
                $lembur->note_atasan2 = $data['note_approval'] ?? null;
                $lembur->atasan2_approval_date = now();
                if ($data['status_approval'] === 'APPROVED') {
                    $lembur->is_approved_atasan2 = true;
                    $lembur->status = 'APPROVED';
                    $lembur->durasi_disetujui_menit = $data['durasi_disetujui_menit'];
                    $lembur->save();
                    return $this->successResponse($lembur, 'Cuti approved by Atasan 2 successfully', 200);
                } elseif ($data['status_approval'] === 'REJECTED') {
                    $lembur->status = 'REJECTED';
                    $lembur->save();
                    return $this->successResponse($lembur, 'Cuti rejected by Atasan 2 successfully', 200);
                }
            } else {
                return $this->errorResponse('You are not authorized to approve this lembur', 403);
            }

            return $this->errorResponse('Invalid approval action', 400);

        } catch (\Throwable $e) {
            Log::error('[LemburController@approvalLembur] '.$e->getMessage());
            return $this->errorResponse('Failed to approve lembur', 500);
        }
    }

}
