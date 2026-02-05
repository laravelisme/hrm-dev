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
        Schema::create('t_ijins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_karyawan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->foreignId('m_company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('durasi')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->string('keperluan');
            $table->enum('status', ['SUBMITED', 'PENDING_APPROVED', 'APPROVED', 'REJECTED'])->default('SUBMITED');
            $table->foreignId('atasan1_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->foreignId('atasan2_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_atasan1')->nullable();
            $table->string('nama_atasan2')->nullable();
            $table->boolean('is_approved_atasan1')->default(false);
            $table->boolean('is_approved_atasan2')->default(false);
            $table->string('note_atasan1')->nullable();
            $table->string('note_atasan2')->nullable();
            $table->date('atasan_1_approval_date')->nullable();
            $table->date('atasan_2_approval_date')->nullable();
            $table->date('hr_verify_date')->nullable();
            $table->string('nama_hr_approval')->nullable();
            $table->foreignId('m_jenis_izin_id')->nullable()->constrained('m_jenis_izins')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_ijins');
    }
};
