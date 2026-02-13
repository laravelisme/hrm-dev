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
        Schema::create('t_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_calon_karyawan_id')->constrained('m_calon_karyawans')->cascadeOnDelete();
            $table->date('interview_date_hr')->nullable();
            $table->time('interview_time_hr')->nullable();
            $table->date('interview_date_user')->nullable();
            $table->time('interview_time_user')->nullable();
            $table->string('interview_hr_location')->nullable();
            $table->string('interview_user_location')->nullable();
            $table->string('interview_hr_status')->nullable();
            $table->string('interview_user_status')->nullable();
            $table->text('interview_hr_notes')->nullable();
            $table->text('interview_user_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_interviews');
    }
};
