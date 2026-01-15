<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel temporary dengan VARCHAR recommendation
        Schema::create('reviews_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->json('scores')->nullable();
            $table->integer('total_score')->nullable();
            $table->text('comment')->nullable();
            $table->string('recommendation', 50)->default('minor_revision'); // VARCHAR
            $table->timestamps();
        });

        // Copy data dari tabel lama ke tabel baru
        DB::statement('INSERT INTO reviews_temp SELECT * FROM reviews');

        // Drop tabel lama
        Schema::dropIfExists('reviews');

        // Rename tabel temp jadi reviews
        Schema::rename('reviews_temp', 'reviews');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tabel temporary dengan ENUM recommendation (rollback)
        Schema::create('reviews_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->json('scores')->nullable();
            $table->integer('total_score')->nullable();
            $table->text('comment')->nullable();
            $table->enum('recommendation', ['accept', 'minor_revision', 'major_revision'])->default('minor_revision'); // ENUM
            $table->timestamps();
        });

        // Copy data (need to map values first)
        DB::statement("
            INSERT INTO reviews_temp 
            SELECT 
                id, 
                proposal_id, 
                reviewer_id, 
                scores, 
                total_score, 
                comment, 
                CASE 
                    WHEN recommendation = 'setuju' THEN 'accept'
                    WHEN recommendation = 'tidak_setuju' THEN 'minor_revision'
                    ELSE recommendation
                END as recommendation,
                created_at,
                updated_at
            FROM reviews
        ");

        Schema::dropIfExists('reviews');
        Schema::rename('reviews_temp', 'reviews');
    }
};