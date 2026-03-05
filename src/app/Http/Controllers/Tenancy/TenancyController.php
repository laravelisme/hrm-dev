<?php

namespace App\Http\Controllers\Tenancy;

use App\Events\TenantCreated;
use App\Events\TenantProvisionRequested;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\Domain\DomainStoreFormRequest;
use App\Models\MSettingApp;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Storage;

class TenancyController extends Controller
{
    private Domain $domain;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function index(Request $request)
    {
        try {
            $searchDomain = $request->query('domain');
            $perPage = (int) $request->query('perPage', 10);
            $perPage = max(1, min($perPage, 100));

            $query = $this->domain->newQuery()->with('tenant');

            if ($searchDomain) {
                $query->where('domain', 'like', "%{$searchDomain}%");
            }

            $domains = $query->paginate($perPage)->withQueryString();

            return view('pages.tenancy.domain.index', compact('domains'));

        } catch (\Throwable $e) {
            Log::error('[TenancyController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load domain data');
        }
    }

    public function store(DomainStoreFormRequest $request)
    {
        try {
            $data = $request->validated();

            $rawPassword = $data['password'] ?? null;
            if (empty($rawPassword)) {
                $rawPassword = Str::random(12);
            }

            $uploadedPaths = [
                'logo' => $request->file('logo')?->store('logos', 'public'),
                'background' => $request->file('background')?->store('backgrounds', 'public'),
                'favicon' => $request->file('favicon')?->store('favicons', 'public'),
            ];

            foreach ($uploadedPaths as $k => $path) {
                if ($path) {
                    $data[$k] = $path;
                }
            }

            $data = array_filter(
                $data,
                fn ($v) => is_null($v) || is_scalar($v),
            );

            event(new TenantProvisionRequested($data, $uploadedPaths, $rawPassword));

            return $this->successResponse(null, 'Domain created successfully', 201);

        } catch (\Throwable $e) {
            if (app()->environment('local')) {
                Log::error('[TenancyController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                return response()->json([
                    'message' => 'Failed to create domain and tenant',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ], 500);
            }

            Log::error('[TenancyController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to create domain and tenant', 500);
        }
    }

    public function create()
    {
        try {

            return view('pages.tenancy.domain.create');

        } catch (\Throwable $e) {
            Log::error('[TenancyController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load create domain form');
        }
    }

    public function destroy($id)
    {
        try {
            $domain = Domain::findOrFail($id);

            if ($domain->tenant) {
                $domain->tenant->delete();
            }

            $domain->delete();

            return response()->json([
                'message' => 'Domain deleted successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('[TenancyController@destroy] ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete domain'
            ], 500);
        }
    }
}
