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
        Schema::create('t_presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_karyawan_id')->constrained('m_karyawans')->cascadeOnDelete();
            $table->timestamp('check_in_time')->nullable();
            $table->string('check_in_latitude')->nullable();
            $table->string('check_in_longitude')->nullable();
            $table->string('check_in_img')->nullable();
            $table->string('check_in_info')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->string('check_out_latitude')->nullable();
            $table->string('check_out_longitude')->nullable();
            $table->string('check_out_img')->nullable();
            $table->string('check_out_info')->nullable();
            $table->string('check_in_timezone')->nullable();
            $table->string('check_out_timezone')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_presensis');
    }
};
