<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert ENUM to VARCHAR(50) untuk support 'setuju' dan 'tidak_setuju'
        DB::statement("
            ALTER TABLE reviews 
            MODIFY COLUMN recommendation 
            VARCHAR(50) 
            NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Update data ke format enum lama
        DB::table('reviews')
            ->where('recommendation', 'setuju')
            ->update(['recommendation' => 'accept']);
        
        DB::table('reviews')
            ->where('recommendation', 'tidak_setuju')
            ->update(['recommendation' => 'minor_revision']);
        
        // Kembalikan ke ENUM
        DB::statement("
            ALTER TABLE reviews 
            MODIFY COLUMN recommendation 
            ENUM('accept', 'minor_revision', 'need_revision') 
            NOT NULL 
            DEFAULT 'minor_revision'
        ");
    }
};