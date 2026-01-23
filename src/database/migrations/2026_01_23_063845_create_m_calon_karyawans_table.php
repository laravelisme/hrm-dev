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
        Schema::create('m_calon_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_company_id')->constrained('m_companies')->cascadeOnDelete();
            $table->string('company_name');
            $table->foreignId('m_department_id')->constrained('m_departments')->cascadeOnDelete();
            $table->string('department_name');
            $table->string('nama_lengkap');
            $table->string('no_telp');
            $table->string('nama_panggilan');
            $table->string('nik')->unique();
            $table->enum('jenis_kelamin', ['MALE', 'FEMALE']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('alamat_ktp');
            $table->string('alamat_domisili');
            $table->enum('status_perkawinan', ['SINGLE', 'MARRIED', 'DIVORCED', 'WIDOWED']);
            $table->string('nama_ayah');
            $table->string('tempat_lahir_ayah');
            $table->date('tanggal_lahir_ayah');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu');
            $table->string('tempat_lahir_ibu');
            $table->date('tanggal_lahir_ibu');
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('nama_saudara_kandung_1')->nullable();
            $table->string('tempat_saudara_kandung_1')->nullable();
            $table->date('tanggal_saudara_kandung_1')->nullable();
            $table->string('pekerjaan_saudara_kandung_1')->nullable();
            $table->string('nama_saudara_kandung_2')->nullable();
            $table->string('tempat_saudara_kandung_2')->nullable();
            $table->date('tanggal_saudara_kandung_2')->nullable();
            $table->string('pekerjaan_saudara_kandung_2')->nullable();
            $table->string('nama_saudara_kandung_3')->nullable();
            $table->string('tempat_saudara_kandung_3')->nullable();
            $table->date('tanggal_saudara_kandung_3')->nullable();
            $table->string('pekerjaan_saudara_kandung_3')->nullable();
            $table->string('nama_saudara_kandung_4')->nullable();
            $table->string('tempat_saudara_kandung_4')->nullable();
            $table->date('tanggal_saudara_kandung_4')->nullable();
            $table->string('pekerjaan_saudara_kandung_4')->nullable();
            $table->string('pengalaman_kerja_1')->nullable();
            $table->string('industri_pengalaman_kerja_1')->nullable();
            $table->string('alamat_pengalaman_kerja_1')->nullable();
            $table->string('posisi_pengalaman_kerja_1')->nullable();
            $table->string('gaji_awal_pengalaman_kerja_1')->nullable();
            $table->string('gaji_akhir_pengalaman_kerja_1')->nullable();
            $table->string('alasan_berhenti_pengalaman_kerja_1')->nullable();
            $table->string('keterangan_pengalaman_kerja_1')->nullable();
            $table->string('pengalaman_kerja_2')->nullable();
            $table->string('industri_pengalaman_kerja_2')->nullable();
            $table->string('alamat_pengalaman_kerja_2')->nullable();
            $table->string('posisi_pengalaman_kerja_2')->nullable();
            $table->string('gaji_awal_pengalaman_kerja_2')->nullable();
            $table->string('gaji_akhir_pengalaman_kerja_2')->nullable();
            $table->string('alasan_berhenti_pengalaman_kerja_2')->nullable();
            $table->string('keterangan_pengalaman_kerja_2')->nullable();
            $table->string('pengalaman_kerja_3')->nullable();
            $table->string('industri_pengalaman_kerja_3')->nullable();
            $table->string('alamat_pengalaman_kerja_3')->nullable();
            $table->string('posisi_pengalaman_kerja_3')->nullable();
            $table->string('gaji_awal_pengalaman_kerja_3')->nullable();
            $table->string('gaji_akhir_pengalaman_kerja_3')->nullable();
            $table->string('alasan_berhenti_pengalaman_kerja_3')->nullable();
            $table->string('keterangan_pengalaman_kerja_3')->nullable();
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMA', 'D3', 'S1/D4', 'S2', 'S3']);
            $table->string('nama_sekolah_universitas')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('ipk_nilai_akhir')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('nama_lembaga_training')->nullable();
            $table->string('jenis_training')->nullable();
            $table->string('keahlian_bahasa_asing_1')->nullable();
            $table->integer('kemampuan_bicara_1')->nullable();
            $table->string('kemampuan_mendengar_1')->nullable();
            $table->string('kemampuan_menulis_1')->nullable();
            $table->string('kemampuan_membaca_1')->nullable();
            $table->string('keahlian_bahasa_asing_2')->nullable();
            $table->integer('kemampuan_bicara_2')->nullable();
            $table->string('kemampuan_mendengar_2')->nullable();
            $table->string('kemampuan_menulis_2')->nullable();
            $table->string('kemampuan_membaca_2')->nullable();
            $table->string('prestasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_calon_karyawans');
    }
};
