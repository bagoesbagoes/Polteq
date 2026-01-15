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
        
        // Update data lama jika ada (safety measure)
        DB::table('proposals')
            ->where('status', 'under_review')
            ->update(['status' => 'submitted']);
        
        DB::statement("
            ALTER TABLE proposals 
            MODIFY COLUMN status 
            ENUM('draft', 'submitted', 'accepted', 'need_revision') 
            NOT NULL 
            DEFAULT 'draft'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Update data need_revision ke submitted dulu
        DB::table('proposals')
            ->where('status', 'need_revision')
            ->update(['status' => 'submitted']);
        
        // Kembalikan ke enum tanpa need_revision
        DB::statement("
            ALTER TABLE proposals 
            MODIFY COLUMN status 
            ENUM('draft', 'submitted', 'accepted') 
            NOT NULL 
            DEFAULT 'draft'
        ");
    }
};