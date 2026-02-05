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
        Schema::create('p_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('p_users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('p_roles')->cascadeOnDelete();
            $table->boolean('is_default_role')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_user_roles');
    }
};
