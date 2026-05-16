<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });

        // Populate existing documents with UUIDs
        DB::table('documentos')->get()->each(function ($doc) {
            DB::table('documentos')
                ->where('id', $doc->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        // Change to NOT NULL after populating
        Schema::table('documentos', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
