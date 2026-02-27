<?php

namespace App\Http\Controllers\Tenancy;

use App\Events\TenantCreated;
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

            $generatedPassword = null;
            $rawPassword = $data['password'] ?? null;
            if (empty($rawPassword)) {
                $rawPassword = Str::random(12);
                $generatedPassword = $rawPassword;
            }

            DB::beginTransaction();

            $tenant = Tenant::create([
                'id' => $data['domain'],
                'nama_company' => $data['nama_company'] ?? $data['domain'],
                'username' => $data['username'] ?? null,
                'password' => $rawPassword ?? null,
                'email' => $data['email'] ?? null,
            ]);

            $tenant->domains()->create([
                'domain' => $data['domain'],
            ]);

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $data['logo'] = $path;
            }

            if ($request->hasFile('background')) {
                $path = $request->file('background')->store('backgrounds', 'public');
                $data['background'] = $path;
            }

            if ($request->hasFile('favicon')) {
                $path = $request->file('favicon')->store('favicons', 'public');
                $data['favicon'] = $path;
            }

            DB::commit();

            event(new TenantCreated($tenant, $data, $rawPassword));

            return $this->successResponse(null, 'Domain created successfully', 201);

        } catch (\Throwable $e) {
            DB::rollBack();
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
