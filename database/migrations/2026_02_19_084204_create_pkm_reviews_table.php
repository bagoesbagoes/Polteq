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
        Schema::create('pkm_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkm_proposal_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('score')->nullable(); // 0-100
            $table->text('comments')->nullable();
            $table->enum('recommendation', ['accept', 'revise', 'reject'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkm_reviews');
    }
};
