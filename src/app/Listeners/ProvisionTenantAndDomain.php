<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Events\TenantProvisionRequested;
use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProvisionTenantAndDomain implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TenantProvisionRequested $event): void
    {
        $data = $event->data;
        $uploadedPaths = $event->uploadedPaths;

        try {
            DB::beginTransaction();

            foreach (['logo', 'background', 'favicon'] as $key) {
                if (!empty($uploadedPaths[$key])) {
                    $data[$key] = $uploadedPaths[$key];
                }
            }

            /** @var Tenant $tenant */
            $tenant = Tenant::create([
                'id' => $data['domain'],
                'nama_company' => $data['nama_company'] ?? $data['domain'],
                'username' => $data['username'] ?? null,
                'password' => $event->rawPassword,
                'email' => $data['email'] ?? null,
            ]);

            $tenant->domains()->create([
                'domain' => $data['domain'],
            ]);

            DB::commit();

            event(new TenantCreated($tenant, $data, $event->rawPassword));

        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup already-stored files if DB insert failed
            try {
                foreach (['logo', 'background', 'favicon'] as $key) {
                    $path = $uploadedPaths[$key] ?? null;
                    if ($path) {
                        Storage::disk('public')->delete($path);
                    }
                }
            } catch (\Throwable $cleanupError) {
                Log::warning('Failed cleaning up tenant provisioning files', [
                    'error' => $cleanupError->getMessage(),
                ]);
            }

            Log::error('[ProvisionTenantAndDomain] ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
