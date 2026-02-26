<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Models\MSettingApp;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Throwable;

class SetupTenantEnvironment implements ShouldQueue
{
    public function handle(TenantCreated $event): void
    {
        $tenant = $event->tenant;
        $data = $event->data;
        $rawPassword = $event->rawPassword;

        try {

            tenancy()->initialize($tenant);

            DB::beginTransaction();

            $user = User::create([
                'name' => $data['username'] ?? 'Admin',
                'email' => $data['email'] ?? ('admin@' . $tenant->id),
                'username' => $data['username'] ?? 'admin',
                'password' => Hash::make($rawPassword),
            ]);

            Role::firstOrCreate(['name' => 'hr']);
            Role::firstOrCreate(['name' => 'admin']);

            $user->syncRoles(['hr']);

            MSettingApp::create([
                'app_name' => 'App HRM - ' . ($data['nama_company'] ?? $tenant->id),
                'app_logo' => $data['logo'] ?? null,
                'app_background' => $data['background'] ?? null,
                'app_favicon' => $data['favicon'] ?? null,
                'tenant_id' => $tenant->id,
            ]);

            DB::commit();

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Tenant setup failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;

        } finally {
            tenancy()->end();
        }
    }

    public function failed(TenantCreated $event, Throwable $exception): void
    {
        Log::critical('Queued Tenant Setup FAILED permanently', [
            'tenant_id' => $event->tenant->id ?? null,
            'error' => $exception->getMessage(),
        ]);
    }
}
