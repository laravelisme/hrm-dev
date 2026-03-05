<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure we check schema on the currently active connection (tenants:migrate uses 'tenant')
        $connection = DB::getDefaultConnection();
        $schema = Schema::connection($connection);

        if (!$schema->hasTable('m_user_devices')) {
            return;
        }

        foreach (['device_token', 'unique_id', 'device_info', 'bundle_id', 'os'] as $column) {
            if (!$schema->hasColumn('m_user_devices', $column)) {
                continue;
            }

            try {
                DB::statement("ALTER TABLE m_user_devices ALTER COLUMN {$column} DROP NOT NULL");
            } catch (\Throwable $e) {
                return;
            }
        }
    }

    public function down(): void
    {
        $connection = DB::getDefaultConnection();
        $schema = Schema::connection($connection);

        if (!$schema->hasTable('m_user_devices')) {
            return;
        }

        // Only enforce NOT NULL for columns that were originally NOT NULL.
        // From create migration: only device_token was NOT NULL.
        if ($schema->hasColumn('m_user_devices', 'device_token')) {
            try {
                DB::statement('ALTER TABLE m_user_devices ALTER COLUMN device_token SET NOT NULL');
            } catch (\Throwable $e) {
                return;
            }
        }
    }
};
