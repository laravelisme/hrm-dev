<?php

namespace App\Http\Controllers\Api\Presensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Presensi\PresensiCheckoutFormRequest;
use App\Http\Requests\Api\Presensi\PresensiStoreFormRequest;
use App\Models\MGrupJamKerja;
use App\Models\MKaryawan;
use App\Models\TLembur;
use App\Models\TPresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    private  TPresensi $presensi;

    public function __construct(TPresensi $presensi)
    {
        $this->presensi = $presensi;
    }

    public function index(Request $request)
    {
        try {

            $searchStartDate = trim((string) $request->query(
                'searchStartDate',
                now()->subDays(30)->toDateString()
            ));

            $searchEndDate = trim((string) $request->query(
                'searchEndDate',
                now()->toDateString()
            ));

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $presensis = $this->presensi->newQuery();

            if ($karyawan) {
                $presensis->where('m_karyawan_id', $karyawan->id);
            }

            $presensis->whereDate('check_in_date', '>=', $searchStartDate)
                ->whereDate('check_in_date', '<=', $searchEndDate)
                ->orderByDesc('id');

            $records = $presensis->get()
                ->filter(fn ($item) => $item->check_out_time !== null);

            $totalJamKerja = $records->sum(function ($item) {

                $checkIn = Carbon::parse(
                    $item->check_in_date . ' ' . $item->check_in_time
                );

                $checkOutDate = $item->check_out_date ?? $item->check_in_date;

                $checkOut = Carbon::parse(
                    $checkOutDate . ' ' . $item->check_out_time
                );

                if ($checkOut->lt($checkIn)) {
                    $checkOut->addDay();
                }

                return $checkIn->floatDiffInHours($checkOut);
            });

            $count = $records->count();
            $averageJamKerja = $count > 0 ? $totalJamKerja / $count : 0;

            $lembur = TLembur::where('m_karyawan_id', $karyawan->id)
                ->whereDate('date', '>=', $searchStartDate)
                ->whereDate('date', '<=', $searchEndDate)
                ->where('status', 'APPROVED')
                ->get();

            $durasiLembur = round(
                $lembur->sum('durasi_verifikasi_menit') / 60,
                2
            );

            return $this->successResponse([
                'count' => $count,
                'average_jam_kerja' => round($averageJamKerja, 2),
                'jam_lembur'   => $durasiLembur,
                'range' => [
                    'start' => $searchStartDate,
                    'end'   => $searchEndDate,
                ],
            ], 'Presensi fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[PresensiController@index] ' . $e->getMessage());
            return $this->errorResponse('Failed to load presensi', 500);
        }
    }

    public function getCurrentPresensi()
    {
        try {

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            if (!$karyawan) {
                return $this->errorResponse('Karyawan not found', 404);
            }

            $presensi = $this->presensi->where('m_karyawan_id', $karyawan->id)
                ->where('is_active', true)
                ->first();

            return $this->successResponse($presensi, 'Current presensi fetched successfully', 200);

        }
        catch (\Throwable $e) {
            Log::error('[PresensiController@getCurrentPresensi] '.$e->getMessage());
            return $this->errorResponse('Failed to load current presensi', 500);
        }
    }

    public function store(PresensiStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            if (!$karyawan) {
                return $this->errorResponse('Karyawan not found', 404);
            }

            $grupKerja = MGrupJamKerja::findOrFail($karyawan->m_group_kerja_id);

            $day = strtolower(
                Carbon::parse($data['check_in_date'])->format('l')
            );

            $minCheckIn = $grupKerja->{$day . '_min_check_in'};
            $maxCheckIn = $grupKerja->{$day . '_max_check_in'};

            if ($minCheckIn == null || $maxCheckIn == null) {
                $minCheckIn = $grupKerja->min_check_in;
                $maxCheckIn = $grupKerja->max_check_in;
            }

            if ($data['check_in_time'] < $minCheckIn) {
                return $this->errorResponse('Check-in time is earlier than allowed', 400);
            }

            if ($data['check_in_time'] > $maxCheckIn) {
                return $this->errorResponse('Check-in time is later than allowed', 400);
            }

            $checkOutDate = $data['check_in_date'] ?? null;

            if($maxCheckIn < $minCheckIn) {
                $checkOutDate = Carbon::parse($data['check_in_date'])->addDay()->format('Y-m-d');
            }

            if ($request->hasFile('check_in_img')) {
                $file = $request->file('check_in_img');
                $fileName = 'presensi_checkin_' . $karyawan->kode_karyawan . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('presensi', $fileName, 'public');
                $data['check_in_img'] = $filePath;
            }

            $presensi = $this->presensi->create([
                'm_karyawan_id' => $karyawan->id,
                'check_in_time' => $data['check_in_time'],
                'check_in_latitude' => $data['check_in_latitude'],
                'check_in_longitude' => $data['check_in_longitude'],
                'check_in_img' => $data['check_in_img'],
                'check_in_info' => $data['check_in_info'] ?? null,
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $checkOutDate,
                'is_active' => true,
                'check_in_timezone' => $data['time_zone'] ?? null,
                'check_out_timezone' => $data['time_zone'] ?? null,
            ]);

            return $this->successResponse($presensi, 'Presensi stored successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[PresensiController@store] '.$e->getMessage());
            return $this->errorResponse('Failed to store presensi', 500);
        }
    }

    public function checkout(PresensiCheckoutFormRequest $request)
    {
        try {

            $data = $request->validated();

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            if (!$karyawan) {
                return $this->errorResponse('Karyawan not found', 404);
            }

            $grupKerja = MGrupJamKerja::findOrFail($karyawan->m_group_kerja_id);

            $presensi = $this->presensi->where('m_karyawan_id', $karyawan->id)
                ->where('is_active', true)
                ->first();

            if (!$presensi) {
                return $this->errorResponse('Active presensi not found', 404);
            }

            $day = strtolower(
                Carbon::parse($presensi->check_in_date)->format('l')
            );

            $minCheckOut = $grupKerja->{$day . '_min_check_out'};
            $maxCheckOut = $grupKerja->{$day . '_max_check_out'};

            if ($minCheckOut == null || $maxCheckOut == null) {
                $minCheckOut = $grupKerja->min_check_out;
                $maxCheckOut = $grupKerja->max_check_out;
            }

            if ($data['check_out_time'] < $minCheckOut) {
                return $this->errorResponse('Check-out time is earlier than allowed', 400);
            }

            if ($data['check_out_time'] > $maxCheckOut) {
                return $this->errorResponse('Check-out time is later than allowed', 400);
            }

            if ($request->hasFile('check_out_img')) {
                $file = $request->file('check_out_img');
                $fileName = 'presensi_checkout_' . $karyawan->kode_karyawan . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('presensi', $fileName, 'public');
                $data['check_out_img'] = $filePath;
            }

            $presensi->update([
                'check_out_time' => $data['check_out_time'],
                'check_out_latitude' => $data['check_out_latitude'],
                'check_out_longitude' => $data['check_out_longitude'],
                'check_out_img' => $data['check_out_img'],
                'check_out_info' => $data['check_out_info'] ?? null,
                'is_active' => false,
            ]);

            return $this->successResponse($presensi, 'Presensi checked out successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[PresensiController@checkout] '.$e->getMessage());
            return $this->errorResponse('Failed to checkout presensi', 500);
        }
    }

    public function history(Request $request)
    {
        try {

            $searchStartDate = trim((string) $request->query('searchStartDate', ''));
            $searchEndDate   = trim((string) $request->query('searchEndDate', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $user = auth('api')->user();
            $karyawan = MKaryawan::where('user_id', $user->id)->first();

            $query = $this->presensi->newQuery();

            if ($karyawan) {
                $query->where('m_karyawan_id', $karyawan->id);
            }
            if ($searchStartDate) {
                $query->whereDate('check_in_date', '>=', $searchStartDate);
            }
            if ($searchEndDate) {
                $query->whereDate('check_in_date', '<=', $searchEndDate);
            }

            $presensis = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

            return $this->successResponse($presensis, 'Presensi history fetched successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[PresensiController@history] '.$e->getMessage());
            return $this->errorResponse('Failed to load presensi history', 500);
        }
    }
}
