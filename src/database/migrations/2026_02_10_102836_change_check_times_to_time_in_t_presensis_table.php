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
        Schema::table('t_presensis', function (Blueprint $table) {
            $table->time('check_in_time')->nullable()->change();
            $table->time('check_out_time')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_presensis', function (Blueprint $table) {
            $table->timestamp('check_in_time')->nullable()->change();
            $table->timestamp('check_out_time')->nullable()->change();
        });
    }
};
