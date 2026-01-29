<?php

namespace App\Listeners;

use App\Events\GenerateSaldoCutiTahunan;
use App\Models\MKaryawan;
use App\Models\MSaldoCuti;
use App\Models\TSaldoCuti;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateSaldoCutiTahunanListener
{
    public function handle(GenerateSaldoCutiTahunan $event): void
    {
        $year = $event->year ?? Carbon::now()->year;
        $saldoByJabatan = [];

        MKaryawan::query()
            ->orderBy('id')
            ->chunkById(100, function ($karyawans) use (&$saldoByJabatan, $year) {
                foreach ($karyawans as $karyawan) {

                    $jabatanId = $karyawan->m_jabatan_id;

                    if (!array_key_exists($jabatanId, $saldoByJabatan)) {
                        $saldoMaster = MSaldoCuti::where('m_jabatan_id', $jabatanId)->first();
                        $saldoByJabatan[$jabatanId] = (int) ($saldoMaster->jumlah ?? 0);
                    }

                    $jumlah = $saldoByJabatan[$jabatanId] ?? 0;

                    $karyawanJabatanRelId = null;
                    if (!empty($karyawan->m_jabatan_id)) {
                        $karyawanJabatanRelId = $karyawan->jabatans()->latest()->value('id');
                    }

                    TSaldoCuti::updateOrCreate(
                        [
                            'm_karyawan_id' => $karyawan->id,
                            'tahun' => $year,
                        ],
                        [
                            'nama_karyawan' => $karyawan->nama_karyawan,
                            'saldo' => $jumlah,
                            'sisa_saldo' => $jumlah,
                            'm_company_id' => $karyawan->m_company_id,
                            'nama_perusahaan' => $karyawan->nama_company,
                            'm_department_id' => $karyawan->m_department_id,
                            'nama_department' => $karyawan->nama_departement,
                            'm_karyawan_jabatan_id' => $karyawanJabatanRelId,
                            'nama_jabatan' => $karyawan->nama_jabatan,
                        ]
                    );
                }
            });

        Log::info("[GenerateSaldoCutiTahunanListener] Done generate saldo cuti tahunan for year={$year}");
    }
}
