<?php

namespace App\Http\Controllers\Api\Cuti;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cuti\CutiStoreFormRequest;
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
            dd($e->getMessage());
            Log::error('[CutiController@store] '.$e->getMessage());
            return $this->errorResponse('Failed to store cuti', 500);
        }
    }
}
