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
        Schema::create('t_sp_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_sp_id')->constrained('t_sps')->cascadeOnDelete();
            $table->string('jenis');
            $table->string('keterangan');
            $table->string('file_pendukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sp_details');
    }
};
