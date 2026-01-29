<?php

namespace App\Http\Controllers\Transaksi\SaldoCutiTahunan;

use App\Events\GenerateSaldoCutiTahunan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\SaldoCutiTahunan\SaldoCutiTahunanUpdateFormRequest;
use App\Models\MKaryawan;
use App\Models\TSaldoCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaldoCutiTahunanController extends Controller
{
    private TSaldoCuti $saldoCuti;

    public function __construct(TSaldoCuti $saldoCuti)
    {
        $this->saldoCuti = $saldoCuti;
    }

    public function index(Request $request)
    {
        try {
            $searchKaryawan = trim((string) $request->query('searchKaryawan', ''));
            $searchTahun = trim((string) $request->query('searchTahun', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->saldoCuti->newQuery();

            if ($searchKaryawan || $searchTahun) {
                $query->where(function ($query) use ($searchKaryawan, $searchTahun) {
                    if ($searchKaryawan) {
                        $query->where('nama_karyawan', 'like', '%' . $searchKaryawan . '%');
                    }
                    if ($searchTahun) {
                        $query->where('tahun', 'like', '%' . $searchTahun . '%');
                    }
                });
            }

            $saldoCutis = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return view('pages.transaksi.saldo-cuti-tahunan.index', compact('saldoCutis'));
        } catch (\Throwable $e) {
            Log::error('[SaldoCutiTahunanController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load saldo cuti tahunan');
        }
    }

    public function edit($id)
    {
        try {

            $saldoCuti = $this->saldoCuti->findOrFail($id);
            return view('pages.transaksi.saldo-cuti-tahunan.edit', compact('saldoCuti'));

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiTahunanController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit saldo cuti tahunan form');
        }
    }

    public function update(SaldoCutiTahunanUpdateFormRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $saldoCuti = $this->saldoCuti->findOrFail($id);

            $saldoCuti->saldo = $data['saldo'];
            $saldoCuti->sisa_saldo = $data['sisa_saldo'];
            $saldoCuti->save();

            return $this->successResponse($saldoCuti, 'Saldo cuti tahunan updated successfully.', 200);

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiTahunanController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update saldo cuti tahunan.', 500);
        }
    }

    public function generateNewSaldo()
    {
        try {

            event(new GenerateSaldoCutiTahunan(Carbon::now()->year));

            return $this->successResponse(null, 'Generate saldo cuti tahunan started.', 200);

        } catch (\Throwable $e) {
            Log::error('[SaldoCutiTahunanController@generateNewSaldo] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to generate new saldo cuti tahunan.', 500);
        }
    }
}
