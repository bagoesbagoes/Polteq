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
        
        //tabel temporary
        Schema::create('reviews_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->json('scores')->nullable();
            $table->integer('total_score')->nullable();
            $table->text('comment')->nullable();
            $table->string('recommendation')->default('minor_revision');
            $table->timestamps();
        });

        //Copy data dari tabel lama ke tabel baru
        DB::statement('INSERT INTO reviews_temp SELECT * FROM reviews');

        //Drop tabel lama
        Schema::dropIfExists('reviews');

        //Rename tabel temp jadi reviews
        Schema::rename('reviews_temp', 'reviews');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::create('reviews_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->json('scores')->nullable();
            $table->integer('total_score')->nullable();
            $table->text('comment')->nullable();
            $table->enum('recommendation', ['accept', 'minor_revision', 'major_revision'])->default('minor_revision');
            $table->timestamps();
        });

        DB::statement('INSERT INTO reviews_temp SELECT * FROM reviews');
        Schema::dropIfExists('reviews');
        Schema::rename('reviews_temp', 'reviews');
    }
};
