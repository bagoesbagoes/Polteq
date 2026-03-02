<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Starting Database Seeding...');
        $this->command->info('================================');
        $this->command->info('');

        // ===================================
        // 1. USERS (Publishers, Reviewers, Admin)
        // ===================================
        $this->command->info('👥 Seeding Users...');
        
        // Create Admin
        // \App\Models\User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@politeq.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'admin',
        // ]);

        // Create Publishers (10 users)
        \App\Models\User::factory()->count(10)->create([
            'role' => 'publisher',
        ]);

        // Create Reviewers (5 users)
        \App\Models\User::factory()->count(5)->create([
            'role' => 'reviewer',
        ]);

        $this->command->info('✅ Users seeded: ' . \App\Models\User::count() . ' users');
        $this->command->info('   - Publishers: ' . \App\Models\User::where('role', 'publisher')->count());
        $this->command->info('   - Reviewers: ' . \App\Models\User::where('role', 'reviewer')->count());
        $this->command->info('   - Admin: ' . \App\Models\User::where('role', 'admin')->count());
        $this->command->info('');

        // ===================================
        // 2. PROPOSALS & REVIEWS
        // ===================================
        $this->call(ProposalSeeder::class);
        $this->call(PkmProposalSeeder::class);
        
        // ===================================
        // 3. POSTS (Optional - if you still use it)
        // ===================================
        // Uncomment if you still want to seed posts
        // $this->command->info('📝 Seeding Posts...');
        // $this->call(PostSeeder::class);

        // ===================================
        // FINAL SUMMARY
        // ===================================
        $this->command->info('');
        $this->command->info('================================');
        $this->command->info('✨ All Seeding Completed!');
        $this->command->info('================================');
        $this->command->info('');
        $this->command->info('📊 Database Overview:');
        $this->command->table(
            ['Table', 'Records'],
            [
                ['Users', \App\Models\User::count()],
                ['Proposals', \App\Models\Proposal::count()],
                ['Reviews', \App\Models\Review::count()],
                // ['Posts', \App\Models\Post::count()], // Uncomment if using posts
            ]
        );
        $this->command->info('');
        $this->command->info('🔑 Default Login Credentials:');
        $this->command->info('   Admin:');
        $this->command->info('   - Email: admin@politeq.com');
        $this->command->info('   - Password: password');
        $this->command->info('');
        $this->command->info('   Publishers & Reviewers:');
        $this->command->info('   - Check database for generated emails');
        $this->command->info('   - Password: password (default for all)');
        $this->command->info('');
    }
}