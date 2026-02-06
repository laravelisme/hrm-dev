<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Models\MSettingApp;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SetupTenantEnvironment implements ShouldQueue
{
    public function handle(TenantCreated $event): void
    {
        $tenant = $event->tenant;
        $data = $event->data;
        $rawPassword = $event->rawPassword;

        // Masuk ke context tenant
        tenancy()->initialize($tenant);

        // Buat admin user tenant
        $user = User::create([
            'name' => $data['username'] ?? 'Admin',
            'email' => $data['email'] ?? ('admin@' . $tenant->id),
            'username' => $data['username'] ?? 'admin',
            'password' => Hash::make($rawPassword),
        ]);

        // Buat role default
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

        tenancy()->end();
    }
}
