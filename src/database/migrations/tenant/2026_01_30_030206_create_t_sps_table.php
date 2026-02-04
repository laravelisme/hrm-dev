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
        Schema::create('t_sps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_karyawan')->nullable();
            $table->foreignId('m_company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->string('nama_perusahaan')->nullable();
            $table->foreignId('m_jenis_sp_id')->constrained('m_jenis_sps')->cascadeOnDelete();
            $table->string('nomor')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->date('tanggal_start')->nullable();
            $table->date('tanggal_end')->nullable();
            $table->string('create_by')->nullable();
            $table->foreignId('atasan_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_atasan')->nullable();
            $table->string('atasan_note')->nullable();
            $table->string('file_surat')->nullable();
            $table->enum('status', ['SUBMITED', 'PENDING_APPROVED', 'APPROVED', 'REJECTED'])->default('SUBMITED');
            $table->boolean('hr_approved')->default(false);
            $table->string('hr_note')->nullable();
            $table->date('hr_approval_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sps');
    }
};
