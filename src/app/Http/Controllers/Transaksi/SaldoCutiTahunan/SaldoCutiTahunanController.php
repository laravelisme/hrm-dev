<?php

namespace App\Http\Controllers\Transaksi\SaldoCutiTahunan;

use App\Http\Controllers\Controller;
use App\Models\TSaldoCuti;
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

    public function create()
    {
        try {



        } catch (\Throwable $e) {
            Log::error('[SaldoCutiTahunanController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create saldo cuti tahunan form');
        }
    }


}
