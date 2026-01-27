<!-- database/migrations/2026_01_13_021529_fix_recommendation_enum_in_reviews_table.php -->
<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek database driver
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // ✅ CARA SQLITE: Recreate table
            
            // 1. Buat tabel temporary
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
            
            // 2. Copy semua data
            DB::statement('INSERT INTO reviews_temp SELECT * FROM reviews');
            
            // 3. Drop tabel lama
            Schema::drop('reviews');
            
            // 4. Rename temporary jadi reviews
            Schema::rename('reviews_temp', 'reviews');
            
        } else {
            // ✅ CARA MYSQL/PostgreSQL: Gunakan MODIFY
            DB::statement("
                ALTER TABLE reviews 
                MODIFY COLUMN recommendation 
                VARCHAR(50) 
                NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // Recreate dengan ENUM lama
            Schema::create('reviews_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
                $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
                $table->json('scores')->nullable();
                $table->integer('total_score')->nullable();
                $table->text('comment')->nullable();
                $table->enum('recommendation', ['accept', 'minor_revision', 'need_revision'])
                      ->default('minor_revision');
                $table->timestamps();
            });
            
            // Map data sebelum copy
            DB::table('reviews')
                ->where('recommendation', 'setuju')
                ->update(['recommendation' => 'accept']);
            
            DB::table('reviews')
                ->where('recommendation', 'tidak_setuju')
                ->update(['recommendation' => 'minor_revision']);
            
            DB::statement('INSERT INTO reviews_temp SELECT * FROM reviews');
            
            Schema::drop('reviews');
            Schema::rename('reviews_temp', 'reviews');
            
        } else {
            // Update data ke format enum lama (MySQL)
            DB::table('reviews')
                ->where('recommendation', 'setuju')
                ->update(['recommendation' => 'accept']);
            
            DB::table('reviews')
                ->where('recommendation', 'tidak_setuju')
                ->update(['recommendation' => 'minor_revision']);
            
            // Kembalikan ke ENUM
            DB::statement("
                ALTER TABLE reviews 
                MODIFY COLUMN recommendation 
                ENUM('accept', 'minor_revision', 'need_revision') 
                NOT NULL 
                DEFAULT 'minor_revision'
            ");
        }
    }
};