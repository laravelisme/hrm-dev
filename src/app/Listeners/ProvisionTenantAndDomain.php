<?php

namespace App\Listeners;

use App\Events\TenantCreated;
use App\Events\TenantProvisionRequested;
use App\Models\Tenant;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProvisionTenantAndDomain
{
    public function handle(TenantProvisionRequested $event): void
    {
        $data = $event->data;
        $tmpFiles = $event->tmpFiles;

        $storedPaths = [];

        try {
            DB::beginTransaction();

            foreach (['logo' => 'logos', 'background' => 'backgrounds', 'favicon' => 'favicons'] as $key => $dir) {
                $tmpPath = $tmpFiles[$key] ?? null;
                if (!$tmpPath) {
                    continue;
                }

                $storedPaths[$key] = Storage::disk('public')->putFile($dir, new File($tmpPath));
                $data[$key === 'logo' ? 'logo' : ($key === 'background' ? 'background' : 'favicon')] = $storedPaths[$key];
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

            try {
                foreach ($storedPaths as $path) {
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
