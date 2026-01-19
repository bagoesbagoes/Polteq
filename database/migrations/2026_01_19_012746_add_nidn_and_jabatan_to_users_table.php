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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nidn_nuptk', 16)->unique()->nullable()->after('email');
            $table->string('jabatan_fungsional')->nullable()->after('nidn_nuptk');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nidn_nuptk', 'jabatan_fungsional']);
        });
    }
};
