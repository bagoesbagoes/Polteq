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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke proposal yang sudah accepted
            $table->foreignId('proposal_id')
                  ->constrained('proposals')
                  ->onDelete('cascade');
            
            // User yang submit (publisher)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Tipe: laporan_akhir atau luaran
            $table->enum('type', ['laporan_akhir', 'luaran']);
            
            // Data
            $table->string('title');
            $table->text('description')->nullable();
            
            // File upload (untuk keduanya, nullable untuk luaran jika pakai link)
            $table->string('file_path')->nullable();
            $table->string('file_size')->nullable(); // in KB
            $table->string('file_type')->nullable(); // pdf, doc, jpg, etc
            
            // Hyperlink (khusus untuk luaran)
            $table->string('hyperlink')->nullable();
            
            // Tipe luaran: 'file' atau 'link'
            $table->enum('luaran_type', ['file', 'link'])->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['proposal_id', 'type']);
            $table->index('user_id');
            
            // Unique constraint: 1 proposal hanya bisa punya 1 laporan_akhir
            $table->unique(['proposal_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
