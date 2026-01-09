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
        // Step 1: Drop kolom role lama
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Step 2: Tambah lagi dengan enum baru (include 'admin')
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['dosen', 'reviewer', 'publisher', 'admin'])
                ->default('publisher')
                ->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Drop kolom role baru
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Step 2: Restore ke enum lama
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['dosen', 'reviewer', 'publisher'])
                ->default('publisher')
                ->after('password');
        });
    }
};