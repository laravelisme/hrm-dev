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
        Schema::create('m_karyawan_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->foreignId('m_jabatan_id')->constrained('m_jabatans')->cascadeOnDelete();
            $table->foreignId('m_department_id')->constrained('m_departments')->cascadeOnDelete();
            $table->foreignId('m_company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->string('tugas_utama')->nullable();
            $table->string('tugas')->nullable();
            $table->string('aplikasi')->nullable();
            $table->string('nama_jabatan')->nullable();
            $table->string('nama_department')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('nama_company')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawan_jabatans');
    }
};
