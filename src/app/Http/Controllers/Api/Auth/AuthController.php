<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginFormRequest;
use App\Models\MKaryawan;
use App\Models\MRefreshToken;
use App\Models\MUserDevices;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private User $user;
    private MKaryawan $mkaryawan;

    public function __construct(User $user, MKaryawan $mkaryawan)
    {
        $this->user = $user;
        $this->mkaryawan = $mkaryawan;
    }

    public function login(LoginFormRequest $request)
    {
        try {

            $data = $request->validated();

            $karyawan = $this->mkaryawan->where('nik', $data['data']['username'])->first();
            if (!$karyawan) {
                return $this->errorResponse('Invalid NIK or password', 401);
            }

            $user = $this->user->findOrFail($karyawan->user_id);
            if (!Hash::check($data['data']['password'], $user->password)) {
                return $this->errorResponse('Invalid NIK or password', 401);
            }

            $credentials = [
                'email' => $user->email,
                'password' => $data['data']['password'],
            ];

            $token = auth('api')->attempt($credentials);
            if (!$token) {
                return $this->errorResponse('Failed to create token', 500);
            }

            $refreshToken = Str::uuid();

            DB::beginTransaction();

            MRefreshToken::updateOrCreate([
                    'user_id' => $user->id,
            ],
            [
                'user_id' => $user->id,
                'token' => $refreshToken,
                'expires_at' => now()->addDays((int) 30)->toDateTimeString(),
            ]);

            MUserDevices::updateOrCreate([
                'user_id' => $user->id,
            ],[
               'user_id' => $user->id,
                'device_token' => $data['data']['device_token'] ?? null,
                'unique_id' => $data['data']['unique_id'] ?? null,
                'device_info' => $data['data']['device_info'] ?? null,
                'bundle_id' => $data['data']['bundle_id'] ?? null,
                'os' => $data['data']['os'] ?? null,
            ]);

            $user->last_login = Carbon::now();
            $user->save();

            DB::commit();

            return $this->successResponse([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'refresh_token' => $refreshToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'karyawan_id' => $karyawan->id,
                ],
            ], 'Login successful', 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[AuthController@login] '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Login failed', 500);
        }
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        try {
            $refresh = MRefreshToken::where('token', $request->refresh_token)
                ->where('expires_at', '>', now())
                ->first();

            if (!$refresh) {
                return $this->errorResponse('Invalid or expired refresh token', 401);
            }

            $user = User::find($refresh->user_id);
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            DB::beginTransaction();

            $refresh->delete();

            $newAccessToken = auth('api')->login($user);

            $newRefreshToken = Str::uuid()->toString();

            MRefreshToken::create([
                'user_id' => $user->id,
                'token' => $newRefreshToken,
                'expires_at' => now()->addDays(30),
            ]);

            DB::commit();

            return $this->successResponse([
                'access_token' => $newAccessToken,
                'token_type' => 'Bearer',
                'refresh_token' => $newRefreshToken,
            ], 'Token refreshed successfully');

        } catch (\Throwable $e) {
            Log::error('[AuthController@refreshToken] '.$e->getMessage());
            return $this->errorResponse('Failed to refresh token', 500);
        }
    }

    public function me()
    {
        try {

            $user = auth('api')->user();
            if (!$user) {
                return $this->errorResponse('User not authenticated', 401);
            }

            $karyawan = $this->mkaryawan->where('user_id', $user->id)->first();

            return $this->successResponse([
                'karyawan' => $karyawan,
                'user' => $user,
            ], 'User data retrieved successfully');

        } catch (\Throwable $e) {
            Log::error('[AuthController@me] '.$e->getMessage());
            return $this->errorResponse('Failed to get user data', 500);
        }
    }

    public function logout()
    {
        try {
            $user = auth('api')->user();

            DB::beginTransaction();
            $user->last_login = Carbon::now();
            $user->save();

            MRefreshToken::where('user_id', $user->id)->delete();

            auth('api')->logout();

            DB::commit();

            return $this->successResponse(null, 'Logout successful', 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[AuthController@logout] '.$e->getMessage());
            return $this->errorResponse('Failed to logout', 500);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = auth('api')->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->errorResponse('Current password is incorrect', 401);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->successResponse(null, 'Password changed successfully', 200);

        } catch (\Throwable $e) {
            Log::error('[AuthController@changePassword] '.$e->getMessage());
            return $this->errorResponse('Failed to change password', 500);
        }
    }

}
