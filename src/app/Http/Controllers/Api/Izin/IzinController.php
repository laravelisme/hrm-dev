<?php

namespace App\Http\Controllers\Api\Izin;

use App\Http\Controllers\Controller;
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
}
