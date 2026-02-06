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
        Schema::create('m_user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('p_users')->cascadeOnDelete();
            $table->string('device_token')->unique();
            $table->string('unique_id')->nullable();
            $table->string('device_info')->nullable();
            $table->string('bundle_id')->nullable();
            $table->string('os')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user_devices');
    }
};
