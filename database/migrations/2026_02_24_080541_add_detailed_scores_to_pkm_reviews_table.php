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
        Schema::table('pkm_reviews', function (Blueprint $table) {
            $table->integer('score')->nullable()->change();
            $table->json('scores')->nullable()->after('score');
            $table->float('total_score', 5, 2)->nullable()->after('scores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pkm_reviews', function (Blueprint $table) {
            $table->dropColumn(['scores','total_score']);
        });
    }
};
