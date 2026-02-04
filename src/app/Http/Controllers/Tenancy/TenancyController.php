<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            $searchDomain = $request->query('domain', null);
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->domain->newQuery();

            if ($searchDomain) {
                $query->where('domain', 'like', '%' . $searchDomain . '%');
            }

            $domains = $query->paginate($perPage);

            return view('pages.tenancy.domain.index', compact('domains'));

        } catch (\Throwable $e) {
            Log::error('[TenancyController@index] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load domain data');
        }
    }

    // Added: store a new domain (supports AJAX and normal POST)
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'domain' => ['required', 'string', 'max:255', 'unique:domains,domain']
            ]);

            $domain = $this->domain->create([
                'domain' => trim($data['domain'])
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Domain added', 'data' => $domain]);
            }

            return redirect()->back()->with('success', 'Domain added');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $ve->errors()], 422);
            }
            throw $ve;
        } catch (\Throwable $e) {
            Log::error('[TenancyController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Failed to add domain'], 500);
            }
            abort(500, 'Failed to add domain');
        }
    }

    // Added: delete a domain (supports AJAX and normal DELETE)
    public function destroy(Request $request, $id)
    {
        try {
            $domain = $this->domain->newQuery()->findOrFail($id);
            $domain->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Domain deleted']);
            }

            return redirect()->back()->with('success', 'Domain deleted');
        } catch (\Throwable $e) {
            Log::error('[TenancyController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete domain'], 500);
            }
            abort(500, 'Failed to delete domain');
        }
    }
}
