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
        Schema::create('m_status_recruitments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_calon_karyawan_id')->constrained('m_calon_karyawans')->cascadeOnDelete();
            $table->enum('status', ['SHORTLIST_ADMIN', 'TES_TULIS', 'INTERVIEW', 'OFFERING', 'TALENT_POOL', 'REJECTED']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_status_recruitments');
    }
};
