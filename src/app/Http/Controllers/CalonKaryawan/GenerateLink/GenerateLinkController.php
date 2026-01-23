<?php

namespace App\Http\Controllers\CalonKaryawan\GenerateLink;

use App\Http\Controllers\Controller;
use App\Models\TTokenCalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenerateLinkController extends Controller
{
    private TTokenCalonKaryawan $token;

    public function __construct(TTokenCalonKaryawan $token)
    {
        $this->token = $token;
    }

    public function index(Request $request)
    {
        try {

            $searchToken = trim((string) $request->query('searchToken', ''));
            $searchIsUsed = trim((string) $request->query('searchIsUsed', ''));
            $searchLink = trim((string) $request->query('searchLink', ''));
            $perPage    = (int) $request->query('perPage', 10);
            $perPage    = max(1, min($perPage, 100));

            $query = $this->token->newQuery();

            if ($searchToken || $searchIsUsed || $searchLink) {
                $query->where(function ($query) use ($searchToken, $searchIsUsed, $searchLink) {
                    if ($searchToken) {
                        $query->where('token', 'like', '%' . $searchToken . '%');
                    }
                    if ($searchIsUsed) {
                        $query->where('is_used', (int)$searchIsUsed);
                    }
                    if ($searchLink) {
                        $query->where('link', 'like', '%' . $searchLink . '%');
                    }
                });
            }

            $tokens = $query->orderByDesc('id')->paginate($perPage)->withQueryString();
            return view('pages.calon-karyawan.generate-link.index', compact('tokens'));

        } catch (\Throwable $e) {
            Log::error('[GenerateLinkController@create] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Failed to load generate link data');
        }
    }

    public function store(Request $request)
    {
        try {

            $token = bin2hex(random_bytes(16));
            $link = url('/recruitment/register?token=' . $token);

            $data = $this->token->create([
                'token' => $token,
                'link' => $link,
                'is_used' => false,
            ]);

            return $this->successResponse($data, 'Link generated successfully', 201);

        } catch (\Throwable $e) {
            Log::error('[GenerateLinkController@store] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to generate link', 500);
        }
    }

    public function destroy($id)
    {
        try {

            $token = $this->token->find($id);
            if (!$token) {
                return $this->errorResponse('Link not found', 404);
            }

            $token->delete();

            return $this->successResponse(null, 'Link deleted successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[GenerateLinkController@destroy] ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Failed to delete link', 500);
        }
    }


}
