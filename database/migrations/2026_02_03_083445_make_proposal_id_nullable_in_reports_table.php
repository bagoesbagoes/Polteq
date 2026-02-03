<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop unique constraint lama
            $table->dropUnique(['proposal_id', 'type']);
            
            // Drop foreign key lama
            $table->dropForeign(['proposal_id']);
            
            // Ubah proposal_id jadi nullable
            $table->foreignId('proposal_id')
                  ->nullable()
                  ->change();
            
            // Tambah foreign key baru dengan nullable
            $table->foreign('proposal_id')
                  ->references('id')
                  ->on('proposals')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Kembalikan seperti semula
            $table->dropForeign(['proposal_id']);
            
            $table->foreignId('proposal_id')
                  ->nullable(false)
                  ->change();
            
            $table->foreign('proposal_id')
                  ->references('id')
                  ->on('proposals')
                  ->onDelete('cascade');
            
            $table->unique(['proposal_id', 'type']);
        });
    }
};