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
        Schema::table('reports', function (Blueprint $table) {
            $table->string('surat_tugas_path')->nullable()->after('file_path');
            $table->float('surat_tugas_size')->nullable()->after('surat_tugas_path'); // KB
            $table->string('surat_tugas_type')->nullable()->after('surat_tugas_size'); // pdf
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['surat_tugas_path', 'surat_tugas_size', 'surat_tugas_type']);
        });
    }
};