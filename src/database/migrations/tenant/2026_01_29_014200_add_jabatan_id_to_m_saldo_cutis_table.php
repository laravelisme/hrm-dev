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
        Schema::table('m_saldo_cutis', function (Blueprint $table) {
            $table->foreignId('m_jabatan_id')->nullable()->constrained('m_jabatans')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_saldo_cutis', function (Blueprint $table) {
            $table->dropForeign(['m_jabatan_id']);
            $table->dropColumn('m_jabatan_id');
        });
    }
};
