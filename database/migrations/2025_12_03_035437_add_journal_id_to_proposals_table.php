<!-- database/migrations/2025_12_03_035437_add_journal_id_to_proposals_table.php -->
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->foreignId('journal_id')->nullable()->constrained('journals')->onDelete('cascade')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeignIdFor('Journal');
        });
    }
};