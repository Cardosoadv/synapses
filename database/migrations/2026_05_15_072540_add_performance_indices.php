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
        Schema::table('processos', function (Blueprint $table) {
            $table->index('status');
            $table->index('data_abertura');
            $table->index('created_at');
        });

        Schema::table('tipos_processos', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['data_abertura']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('tipos_processos', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
