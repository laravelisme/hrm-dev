<?php

namespace App\Http\Controllers\Api\Izin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Izin\IzinApprovalFormRequest;
use App\Http\Requests\Api\Izin\IzinStoreFormRequest;
use App\Models\MKaryawan;
use App\Models\TIjin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IzinController extends Controller
{
    private TIjin $izin;

    public function __construct(TIjin $izin)
    {
        $this->izin = $izin;
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


            $query = $this->izin->newQuery();

            if ($karyawan) {
                $query->where('m_karyawan_id', $karyawan->id);
            }
            if ($searchStartDate) {
                $query->whereDate('tanggal_izin', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $query->whereDate('tanggal_izin', '<=', $searchEndDate);
            }

            $izins = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return $this->successResponse($izins, 'Izin fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[IzinController@index] '.$e->getMessage());
            return $this->errorResponse('Failed to load izin', 500);
        }
    }

    public function store(IzinStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $folder = 'izin_files/' . $karyawan->kode_karyawan;
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs($folder, $fileName, 'public');
                $data['file'] = '/storage/' . $filePath;
            }

            $izin = $this->izin->create([
                'm_karyawan_id' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'm_company_id' => $karyawan->m_company_id,
                'nama_perusahaan' => $karyawan->nama_company,
                'start_date' => $data['tanggal_start'],
                'end_date' => $data['tanggal_end'],
                'durasi' => $data['durasi'] ?? null,
                'tanggal_kembali' => $data['tanggal_kembali'],
                'keperluan' => $data['note'],
                'atasan1_id' => $karyawan->atasan1_id,
                'atasan2_id' => $karyawan->atasan2_id,
                'nama_atasan1' => $karyawan->nama_atasan1,
                'nama_atasan2' => $karyawan->nama_atasan2,
                'file' => $data['file'] ?? null,
                'm_jenis_izin_id' => $data['id_jenis' ],
            ]);

            return $this->successResponse($izin, 'Izin created successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[IzinController@store] '.$e->getMessage());
            return $this->errorResponse('Failed to store izin', 500);
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

            $listApprovalQuery = $this->izin->newQuery();

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
            return $this->errorResponse('Failed to load izin approval list', 500);
        }
    }

    public function showIzin($id)
    {
        try {

            $izin = $this->izin->find($id);
            if (!$izin) {
                return $this->errorResponse('Izin not found', 404);
            }

            return $this->successResponse($izin, 'Izin details fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[IzinController@showIzin] '.$e->getMessage());
            return $this->errorResponse('Failed to load izin details', 500);
        }
    }

    public function approvalIzin(IzinApprovalFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $izin = $this->izin->find($id);
            if (!$izin) {
                return $this->errorResponse('Izin not found', 404);
            }

            if ($izin->atasan1_id === $karyawan->id) {
                $izin->note_atasan1 = $data['note_approval'] ?? null;
                $izin->atasan_1_approval_date = now();
                if ($data['status_approval'] === 'APPROVED') {
                    $izin->is_approved_atasan1 = true;
                    $izin->status = 'PENDING_APPROVED';
                    $izin->save();
                    return $this->successResponse($izin, 'Izin approved by Atasan 1 successfully', 200);
                } elseif ($data['status_approval'] === 'REJECTED') {
                    $izin->status = 'REJECTED';
                    $izin->save();
                    return $this->successResponse($izin, 'Izin rejected by Atasan 1 successfully', 200);
                }
            } elseif ($izin->atasan2_id === $karyawan->id) {
                $izin->note_atasan2 = $data['note_approval'] ?? null;
                $izin->atasan_2_approval_date = now();
                if ($data['status_approval'] === 'APPROVED') {
                    $izin->is_approved_atasan2 = true;
                    $izin->status = 'APPROVED';
                    $izin->save();
                    return $this->successResponse($izin, 'Izin approved by Atasan 2 successfully', 200);
                } elseif ($data['status_approval'] === 'REJECTED') {
                    $izin->status = 'REJECTED';
                    $izin->save();
                    return $this->successResponse($izin, 'Izin rejected by Atasan 2 successfully', 200);
                }
            } else {
                return $this->errorResponse('You are not authorized to approve this izin', 403);
            }

            return $this->errorResponse('Invalid approval status', 400);


        } catch (\Throwable $e) {
            Log::error('[IzinController@approvalIzin] '.$e->getMessage());
            return $this->errorResponse('Failed to approve izin', 500);
        }
    }

}
