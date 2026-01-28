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
        Schema::create('t_test_tulis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_calon_karyawan_id')->constrained('m_calon_karyawans')->cascadeOnDelete();
            $table->string('test_psikologi')->nullable();
            $table->string('test_teknikal')->nullable();
            $table->timestamp('deadline_psikologi')->nullable();
            $table->timestamp('deadline_teknikal')->nullable();
            $table->enum('status_psikologi', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('status_teknikal', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('result_psikologi')->nullable();
            $table->string('result_teknikal')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_test_tulis');
    }
};
