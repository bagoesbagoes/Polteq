<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('jenis_luaran')->nullable()->after('type');
            $table->string('jenis_luaran_lainnya')->nullable()->after('jenis_luaran');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['jenis_luaran', 'jenis_luaran_lainnya']);
        });
    }
};