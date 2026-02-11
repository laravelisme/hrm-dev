<?php

namespace App\Listeners;

use App\Events\PresensiOfflineBulkEvent;
use App\Models\TPresensi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandlePresensiOfflineBulk implements ShouldQueue
{
    public function handle(PresensiOfflineBulkEvent $event): void
    {
        try {

            DB::transaction(function () use ($event) {

                TPresensi::upsert(
                    $event->preparedData,
                    ['m_karyawan_id', 'check_in_date'],
                    [
                        'check_in_time',
                        'check_in_latitude',
                        'check_in_longitude',
                        'check_in_img',
                        'check_in_info',
                        'check_out_date',
                        'is_active',
                        'check_out_time',
                        'check_out_latitude',
                        'check_out_longitude',
                        'check_out_img',
                        'check_out_info',
                        'check_in_timezone',
                        'check_out_timezone',
                        'updated_at'
                    ]
                );

            });

        } catch (\Throwable $e) {

            Log::error('[HandlePresensiOfflineBulk] ' . $e->getMessage());
            throw $e; // biar queue bisa retry
        }
    }
}
