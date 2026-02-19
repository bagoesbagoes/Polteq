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
        Schema::create('pkm_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->year('tahun_pelaksanaan');
            $table->enum('sumber_dana', ['DIPA', 'Non-DIPA']);
            $table->string('kategori_pkm');
            $table->string('kelompok_riset')->nullable();
            $table->text('anggota_tim')->nullable(); // JSON
            $table->text('abstrak');
            $table->string('file_usulan');
            $table->float('file_size')->nullable(); // KB
            $table->enum('status', ['draft', 'submitted', 'accepted', 'need_revision'])->default('draft');
            $table->text('revision_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkm_proposals');
    }
};
