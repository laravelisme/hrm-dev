<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MKaryawan;
use App\Models\TCuti;
use App\Models\TSp;
use App\Models\Tenant;
use App\Models\MCompany;
use App\Models\MDepartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today     = now()->toDateString();
            $thisMonth = now()->month;
            $thisYear  = now()->year;

            $totalKaryawan   = MKaryawan::where('is_active', true)->count();
            $totalTenant     = Tenant::count();
            $totalCompany    = MCompany::count();
            $totalDepartment = MDepartment::count();

            $izinBulanIni = DB::table('t_ijins')
                ->whereMonth('created_at', $thisMonth)
                ->whereYear('created_at', $thisYear)
                ->count();

            $izinHariIni = DB::table('t_ijins')
                ->whereDate('created_at', $today)
                ->count();

            $spBulanIni = TSp::whereMonth('tanggal_surat', $thisMonth)
                ->whereYear('tanggal_surat', $thisYear)
                ->count();

            $cutiHariIni = TCuti::whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->count();

            $cutiBulanIni = TCuti::whereMonth('start_date', $thisMonth)
                ->whereYear('start_date', $thisYear)
                ->count();

            return view('pages.dashboard.index', compact(
                'totalKaryawan',
                'totalTenant',
                'totalCompany',
                'totalDepartment',
                'izinBulanIni',
                'izinHariIni',
                'spBulanIni',
                'cutiHariIni',
                'cutiBulanIni'
            ));

        } catch (\Throwable $e) {
            Log::error('[DashboardController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return view('pages.dashboard.index', [
                'totalKaryawan'   => 0,
                'totalTenant'     => 0,
                'totalCompany'    => 0,
                'totalDepartment' => 0,
                'izinBulanIni'    => 0,
                'izinHariIni'     => 0,
                'spBulanIni'      => 0,
                'cutiHariIni'     => 0,
                'cutiBulanIni'    => 0,
            ]);
        }
    }
}
