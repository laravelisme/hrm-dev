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
        Schema::create('m_hari_libur_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->foreignId('hari_libur_id')->constrained('m_hari_liburs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_hari_libur_companies');
    }
};
