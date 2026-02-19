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
        // Ubah kolom luaran_type dari enum ke string untuk support value 'both'
        Schema::table('reports', function (Blueprint $table) {
            $table->string('luaran_type', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('luaran_type', 20)->nullable()->change();
        });
    }
};