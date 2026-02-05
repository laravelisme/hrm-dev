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
        Schema::create('m_karyawan_pengalaman_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('tahun_mulai')->nullable();
            $table->string('tahun_selesai')->nullable();
            $table->integer('urutan')->nullable();
            $table->string('riwayat_tugas')->nullable();
            $table->string('riwayat_alamat_perusahaan')->nullable();
            $table->string('riwayat_berhenti')->nullable();
            $table->string('gaji')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawan_pengalaman_kerjas');
    }
};
