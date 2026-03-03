<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN device_token DROP NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN unique_id DROP NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN device_info DROP NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN bundle_id DROP NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN os DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN device_token SET NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN unique_id SET NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN device_info SET NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN bundle_id SET NOT NULL');
        DB::statement('ALTER TABLE m_user_devices ALTER COLUMN os SET NOT NULL');
    }
};
