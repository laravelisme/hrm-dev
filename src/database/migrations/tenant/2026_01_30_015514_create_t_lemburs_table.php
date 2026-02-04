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
        Schema::create('t_lemburs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_karyawan')->nullable();
            $table->foreignId('m_company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->string('nama_company')->nullable();
            $table->date('date');
            $table->string('note')->nullable();
            $table->enum('status', ['SUBMITED', 'APPROVED_PENDING', 'APPROVED', 'REJECTED'])->default('SUBMITED');
            $table->foreignId('atasan1_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->foreignId('atasan2_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_atasan1')->nullable();
            $table->string('nama_atasan2')->nullable();
            $table->string('atasan1_note')->nullable();
            $table->string('atasan2_note')->nullable();
            $table->date('atasan1_approval_date')->nullable();
            $table->date('atasan2_approval_date')->nullable();
            $table->boolean('is_approved_atasan1')->default(false);
            $table->boolean('is_approved_atasan2')->default(false);
            $table->date('hr_verify_date')->nullable();
            $table->string('nama_hr_approval')->nullable();
            $table->string('durasi_diajukan_menit')->nullable();
            $table->string('durasi_disetujui_menit')->nullable();
            $table->string('durasi_verifikasi_menit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_lemburs');
    }
};
