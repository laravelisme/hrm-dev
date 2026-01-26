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
        Schema::create('m_karyawan_saudaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->integer('anak_ke')->nullable();
            $table->string('nama')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawan_saudaras');
    }
};
