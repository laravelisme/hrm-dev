<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\MJabatan;
use App\Models\MJenisCuti;
use App\Models\MJenisIzin;
use App\Models\MJenisSp;
use App\Models\MKaryawan;
use App\Models\MLokasiKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    private MJenisIzin $jenisIzin;
    private MJenisCuti $jenisCuti;
    private MJenisSp $jenisSp;
    private MJabatan $jabatan;

    public function __construct(MJenisIzin $jenisIzin, MJenisCuti $jenisCuti, MJenisSp $jenisSp, MJabatan $jabatan)
    {
        $this->jenisIzin = $jenisIzin;
        $this->jenisCuti = $jenisCuti;
        $this->jenisSp = $jenisSp;
        $this->jabatan = $jabatan;
    }

    public function getJenisIzin(Request $request)
    {
        try {
            $query = $this->jenisIzin->newQuery();

            $jenisIzinList = $query->select( 'id', 'nama_izin', 'kode')->get();

            return $this->successResponse($jenisIzinList, 'Jenis Izin fetched successfully', 200);
        } catch (\Exception $e) {
            Log::error('[MasterController@getJenisIzin] '.$e->getMessage());
            return $this->errorResponse('Failed to fetch Jenis Izin', 500);
        }
    }

    public function getJenisCuti(Request $request)
    {
        try {
            $query = $this->jenisCuti->newQuery();

            $jenisCutiList = $query->select( 'id', 'nama_cuti', 'kode')->get();

            return $this->successResponse($jenisCutiList, 'Jenis Cuti fetched successfully', 200);
        } catch (\Exception $e) {
            Log::error('[MasterController@getJenisCuti] '.$e->getMessage());
            return $this->errorResponse('Failed to fetch Jenis Cuti', 500);
        }
    }

    public function getJenisSp(Request $request)
    {
        try {
            $query = $this->jenisSp->newQuery();

            $jenisSpList = $query->select( 'id', 'nama_sp', 'kode')->get();

            return $this->successResponse($jenisSpList, 'Jenis SP fetched successfully', 200);
        } catch (\Exception $e) {
            Log::error('[MasterController@getJenisSp] '.$e->getMessage());
            return $this->errorResponse('Failed to fetch Jenis SP', 500);
        }
    }

    public function getListBawahan(Request $request)
    {
        try {

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();
            if (!$karyawan) {
                return $this->errorResponse('Karyawan not found for the authenticated user', 404);
            }

            $bawahanList = MKaryawan::where('atasan1_id', $karyawan->id)
                ->orWhere('atasan2_id', $karyawan->id)
                ->select('id', 'nama_karyawan', 'nama_jabatan', 'nama_departement', 'nama_company', 'm_jabatan_id', 'm_department_id', 'm_company_id', 'm_group_kerja_id')
                ->get();

            return $this->successResponse($bawahanList, 'List bawahan fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[MasterController@getListBawahan] '.$e->getMessage());
            return $this->errorResponse('Failed to fetch list bawahan', 500);
        }
    }

    public function getJenisJabatan(Request $request)
    {
        try {
            $query = $this->jabatan->newQuery();

            $jenisJabatanList = $query->select( 'id', 'name', 'kode', 'level')->get();

            return $this->successResponse($jenisJabatanList, 'Jenis Jabatan fetched successfully', 200);
        } catch (\Exception $e) {
            Log::error('[MasterController@getJenisJabatan] '.$e->getMessage());
            return $this->errorResponse('Failed to fetch Jenis Jabatan', 500);
        }
    }

    public function getWorkLocation(Request $request)
    {
        try {

            $searchName = trim((string) $request->query('searchName', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = MLokasiKerja::query();

            if ($searchName !== '') {
                $query->where('nama_lokasi_kerja', 'like', "%{$searchName}%");
            }

            $workLocations = $query->select('id', 'name')
                ->paginate($perPage)
                ->withQueryString();

            return $this->successResponse($workLocations, 'Work locations fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[MasterController@getWorkLocation] '.$e->getMessage());
            return $this->errorResponse('Failed to fetch work location', 500);
        }
    }
}
