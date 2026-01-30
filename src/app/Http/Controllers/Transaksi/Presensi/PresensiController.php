<?php

namespace App\Http\Controllers\Transaksi\Presensi;

use App\Http\Controllers\Controller;
use App\Models\MKaryawan;
use App\Models\TPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    private TPresensi $presensi;

    public function __construct(TPresensi $presensi)
    {
        $this->presensi = $presensi;
    }

    public function index(Request $request)
    {
        try {

            $searchBulanTahun = trim((string) $request->query('searchBulanTahun', ''));
            $searchKaryawan = trim((string) $request->query('searchKaryawan', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->presensi->newQuery();

            if ($searchBulanTahun || $searchKaryawan) {
                $query->where(function ($query) use ($searchBulanTahun, $searchKaryawan) {
                    if ($searchBulanTahun) {
                        $query->whereRaw("to_char(check_in_date, 'MM-YYYY') = ?", [$searchBulanTahun]);
                    }
                    if ($searchKaryawan) {
                        $query->where('m_karyawan_id', (int) $searchKaryawan);
                    }
                });
            }

            $presensis = $query->orderByDesc('id')->with('karyawan')->paginate($perPage)->withQueryString();

            return view('pages.transaksi.presensi.index', compact('presensis'));

        } catch (\Throwable $e) {
            Log::error('[PresensiController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load presensi');
        }
    }

    public function karyawanOptions(Request $request)
    {
        try {

            $term    = trim((string) $request->get('q', $request->get('term', '')));
            $page    = max(1, (int) $request->get('page', 1));
            $perPage = (int) $request->get('perPage', 20);
            $perPage = max(1, min($perPage, 50));

            $q = MKaryawan::query()
                ->select('id', 'nama_karyawan');

            if ($term !== '') {
                $q->where('nama_karyawan', 'like', '%' . $term . '%');
            }

            $paginator = $q->orderBy('nama_karyawan')
                ->paginate($perPage, ['*'], 'page', $page);

            $results = $paginator->getCollection()->map(fn ($c) => [
                'id'   => $c->id,
                'text' => $c->nama_karyawan,
            ])->values();

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => $paginator->hasMorePages(),
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('[PresensiController@karyawanOptions] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to load karyawan options', 500);
        }
    }
}
