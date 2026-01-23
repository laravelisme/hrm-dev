<?php

namespace App\Http\Controllers\MasterData\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Setting\SettingStoreFormRequest;
use App\Http\Requests\MasterData\Setting\SettingUpdateFormRequest;
use App\Models\MSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    private MSetting $setting;

    public function __construct(MSetting $setting)
    {
        $this->setting = $setting;
    }

    public function index(Request $request)
    {
        try {

            $searchName = trim((string) $request->query('searchName', ''));
            $searchVal = trim((string) $request->query('searchVal', ''));
            $searchKode = trim((string) $request->query('searchKode', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->setting->newQuery();

            if ($searchName || $searchVal || $searchKode) {
                $query->where(function ($query) use ($searchName, $searchVal, $searchKode) {
                    if ($searchName) {
                        $query->where('name', 'like', '%' . $searchName . '%');
                    }
                    if ($searchVal) {
                        $query->where('val', 'like', '%' . $searchVal . '%');
                    }
                    if ($searchKode) {
                        $query->where('kode', 'like', '%' . $searchKode . '%');
                    }
                });
            }

            $settings = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
            return view('pages.master-data.setting.index', compact('settings'));

        } catch (\Throwable $e) {
            Log::error('[SettingController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load setting data');
        }
    }

    public function create()
    {
        try {

            return view('pages.master-data.setting.create');

        } catch (\Throwable $e) {
            Log::error('[SettingController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create setting form');
        }
    }

    public function store(SettingStoreFormRequest $request)
    {
        try {

            $data = $request->validated();

            $setting = $this->setting->create($data);

            if ($setting) {
                return $this->successResponse($setting, 'Setting stored successfully', 201);
            } else {
                return $this->errorResponse('Failed to store setting', 500);
            }

        } catch (\Throwable $e) {
            Log::error('[SettingController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to store setting', 500);
        }
    }

    public function edit($id)
    {
        try {

            $setting = $this->setting->findOrFail($id);
            return view('pages.master-data.setting.edit', compact('setting'));

        } catch (\Throwable $e) {
            Log::error('[SettingController@edit] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load edit setting form');
        }
    }

    public function update(SettingUpdateFormRequest $request, $id)
    {
        try {

            $data = $request->validated();

            $setting = $this->setting->findOrFail($id);
            $setting->update($data);

            return $this->successResponse($setting, 'Setting updated successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SettingController@update] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to update setting', 500);
        }
    }

    public function destroy($id)
    {
        try {

            $setting = $this->setting->findOrFail($id);
            $setting->delete();

            return $this->successResponse(null, 'Setting deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[SettingController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete setting', 500);
        }
    }
}
