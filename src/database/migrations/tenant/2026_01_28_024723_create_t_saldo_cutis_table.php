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
        Schema::create('t_saldo_cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_karyawan')->nullable();
            $table->string('tahun')->nullable();
            $table->integer('saldo')->default(0);
            $table->integer('sisa_saldo')->default(0);
            $table->foreignId('m_company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->string('nama_perusahaan')->nullable();
            $table->foreignId('m_department_id')->constrained('m_departments')->cascadeOnDelete();
            $table->string('nama_department')->nullable();
            $table->foreignId('m_karyawan_jabatan_id')->constrained('m_karyawan_jabatans')->cascadeOnDelete();
            $table->string('nama_jabatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_saldo_cutis');
    }
};
