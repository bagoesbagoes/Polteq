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
        Schema::table('reviews', function (Blueprint $table) {
            // Tambah kolom untuk menyimpan detail scoring (JSON format)
            $table->json('scores')->nullable()->after('reviewer_id');
            
            // Tambah kolom untuk total nilai
            $table->integer('total_score')->nullable()->after('scores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['scores', 'total_score']);
        });
    }
};
