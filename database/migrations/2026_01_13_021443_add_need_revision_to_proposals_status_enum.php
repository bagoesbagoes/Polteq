<!-- database/migrations/2026_01_13_021443_add_need_revision_to_proposals_status_enum.php -->
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
        // Cek database driver
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            
            // 1. Buat tabel temporary dengan SEMUA kolom yang ada
            Schema::create('proposals_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('journal_id')->nullable()->constrained('journals')->onDelete('cascade');
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('file_usulan');
                $table->string('file_revisi')->nullable();
                $table->enum('status', ['draft', 'submitted', 'accepted', 'need_revision'])
                      ->default('draft');
                $table->timestamps();
            });
            
            // 2. Copy SEMUA data dari tabel lama ke temporary
            DB::statement('INSERT INTO proposals_temp SELECT * FROM proposals');
            
            // 3. Drop tabel lama
            Schema::drop('proposals');
            
            // 4. Rename tabel temporary jadi proposals
            Schema::rename('proposals_temp', 'proposals');
            
        } else {
            // ✅ CARA MYSQL/PostgreSQL: Gunakan MODIFY
            DB::statement("
                ALTER TABLE proposals 
                MODIFY COLUMN status 
                ENUM('draft', 'submitted', 'accepted', 'need_revision') 
                NOT NULL 
                DEFAULT 'draft'
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
            // Recreate dengan status lama (tanpa need_revision)
            Schema::create('proposals_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('journal_id')->nullable()->constrained('journals')->onDelete('cascade');
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('file_usulan');
                $table->string('file_revisi')->nullable();
                $table->enum('status', ['draft', 'submitted', 'accepted'])
                      ->default('draft');
                $table->timestamps();
            });
            
            DB::statement('INSERT INTO proposals_temp SELECT id, user_id, journal_id, judul, deskripsi, file_usulan, file_revisi,
                          CASE WHEN status = "need_revision" THEN "draft" ELSE status END as status,
                          created_at, updated_at FROM proposals');
            
            Schema::drop('proposals');
            Schema::rename('proposals_temp', 'proposals');
            
        } else {
            DB::statement("
                ALTER TABLE proposals 
                MODIFY COLUMN status 
                ENUM('draft', 'submitted', 'accepted') 
                NOT NULL 
                DEFAULT 'draft'
            ");
        }
    }
};