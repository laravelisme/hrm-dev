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
        Schema::table('t_presensis', function (Blueprint $table) {
            $table->unique(['m_karyawan_id', 'check_in_date'], 'unique_karyawan_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_presensis', function (Blueprint $table) {

        });
    }
};
