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
        Schema::create('m_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_karyawan')->unique();
            $table->string('nama_karyawan');
            $table->string('email')->unique();
            $table->foreignId('user_id')->constrained('p_users')->cascadeOnDelete();
            $table->foreignId('m_jabatan_id')->nullable()->constrained('m_jabatans')->cascadeOnDelete();
            $table->string('nama_jabatan')->nullable();
            $table->integer('level')->nullable();
            $table->foreignId('m_department_id')->nullable()->constrained('m_departments')->cascadeOnDelete();
            $table->string('nama_departement')->nullable();
            $table->foreignId('m_company_id')->nullable()->constrained('m_companies')->cascadeOnDelete();
            $table->string('nama_company')->nullable();
            $table->foreignId('m_group_kerja_id')->nullable()->constrained('m_grup_jam_kerja')->cascadeOnDelete();
            $table->foreignId('atasan1_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_atasan1')->nullable();
            $table->foreignId('atasan2_id')->nullable()->constrained('m_karyawans')->cascadeOnDelete();
            $table->string('nama_atasan2')->nullable();
            $table->string('created_by')->nullable();
            $table->string('nik')->unique();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->string('alamat_domisili')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('darurat_nama')->nullable();
            $table->string('darurat_hubungan')->nullable();
            $table->string('darurat_hp')->nullable();
            $table->string('darurat_alamat')->nullable();
            $table->string('foto')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->string('tempat_lahir_pasangan')->nullable();
            $table->date('tanggal_lahir_pasangan')->nullable();
            $table->string('pekerjaan_pasangan')->nullable();
            $table->string('nama_perusahaan_pasangan')->nullable();
            $table->string('jabatan_pasangan')->nullable();
            $table->integer('jumlah_anak')->nullable();
            $table->integer('jumlah_saudara_kandung')->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->string('jabatan_tugas_utama')->nullable();
            $table->string('jabatan_tugas')->nullable();
            $table->string('jabatan_aplikasi')->nullable();
            $table->string('fasilitas')->nullable();
            $table->string('rencana_karir')->nullable();
            $table->date('jabatan_tanggal_mulai')->nullable();
            $table->date('jabatan_tanggal_selesai')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('riwayat_penyakit')->nullable();
            $table->string('kendaraan')->nullable();
            $table->string('sim')->nullable();
            $table->boolean('is_active_organisasi')->default(0);
            $table->boolean('is_active_daerah_lain')->default(0);
            $table->string('alaasan_daerah_lain')->nullable();
            $table->boolean('is_perjalanan_dinas')->default(0);
            $table->string('alasan_perjalanan_dinas')->nullable();
            $table->string('ktp_file')->nullable();
            $table->string('kk_file')->nullable();
            $table->string('npwp_file')->nullable();
            $table->string('sim_file')->nullable();
            $table->boolean('is_active')->default(0);
            $table->string('status_karyawan')->nullable();
            $table->boolean('is_presensi')->default(0);
            $table->boolean('is_cuti')->default(0);
            $table->boolean('is_izin')->default(0);
            $table->boolean('is_lembur')->default(0);
            $table->boolean('is_pembaruan_data')->default(0);
            $table->boolean('is_resign')->default(0);
            $table->boolean('is_data_benar')->default(1);
            $table->string('ijazah_file')->nullable();
            $table->foreignId('m_lokasi_kerja_id')->nullable()->constrained('m_lokasi_kerjas')->cascadeOnDelete();
            $table->string('nama_lokasi_kerja')->nullable();
            $table->boolean('skip_level_two')->default(0);
            $table->string('cv_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawans');
    }
};
