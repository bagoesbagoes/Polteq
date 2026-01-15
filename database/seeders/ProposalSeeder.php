<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\User;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada users dengan role publisher dan reviewer
        $publishers = User::where('role', 'publisher')->get();
        $reviewers = User::where('role', 'reviewer')->get();

        // Kalau belum ada publisher, create
        if ($publishers->isEmpty()) {
            $publishers = User::factory()->count(5)->create(['role' => 'publisher']);
        }

        // Kalau belum ada reviewer, create
        if ($reviewers->isEmpty()) {
            $reviewers = User::factory()->count(3)->create(['role' => 'reviewer']);
        }

        $this->command->info('🚀 Starting to seed proposals...');

        // ===================================
        // 1. DRAFT PROPOSALS (10 proposals)
        // ===================================
        $this->command->info('📝 Creating draft proposals...');
        Proposal::factory()
            ->count(10)
            ->draft()
            ->create();

        // ===================================
        // 2. SUBMITTED PROPOSALS (20 proposals)
        // ===================================
        $this->command->info('📤 Creating submitted proposals...');
        Proposal::factory()
            ->count(20)
            ->submitted()
            ->create();

        // ===================================
        // 3. ACCEPTED PROPOSALS with REVIEWS (25 proposals)
        // ===================================
        $this->command->info('✅ Creating accepted proposals with reviews...');
        Proposal::factory()
            ->count(25)
            ->accepted()
            ->create()
            ->each(function ($proposal) use ($reviewers) {
                // Create 1 review per accepted proposal
                $reviewer = $reviewers->random();
                
                Review::create([
                    'proposal_id' => $proposal->id,
                    'reviewer_id' => $reviewer->id,
                    'scores' => [
                        'pendahuluan' => rand(80, 100),
                        'tinjauan_pustaka' => rand(80, 100),
                        'metodologi' => rand(80, 100),
                        'kelayakan' => rand(80, 100),
                    ],
                    'total_score' => rand(80, 100),
                    'recommendation' => 'setuju',
                    'comment' => 'Proposal sangat baik dan memenuhi semua kriteria penilaian. Layak untuk dilanjutkan ke tahap penelitian.',
                    'created_at' => $proposal->created_at,
                    'updated_at' => now(),
                ]);
            });

        // ===================================
        // 5. NEED REVISION PROPOSALS with REVIEWS (15 proposals)
        // ===================================
        $this->command->info('📝 Creating need revision proposals with reviews...');
        Proposal::factory()
            ->count(15)
            ->needRevision()
            ->create()
            ->each(function ($proposal) use ($reviewers) {
                // Create 1 review per need_revision proposal
                $reviewer = $reviewers->random();
                
                Review::create([
                    'proposal_id' => $proposal->id,
                    'reviewer_id' => $reviewer->id,
                    'scores' => [
                        'pendahuluan' => rand(40, 70),
                        'tinjauan_pustaka' => rand(40, 70),
                        'metodologi' => rand(40, 70),
                        'kelayakan' => rand(40, 70),
                    ],
                    'total_score' => rand(40, 70),
                    'recommendation' => 'tidak_setuju',
                    'comment' => 'Proposal perlu perbaikan di beberapa bagian, terutama metodologi penelitian dan tinjauan pustaka. Silakan lakukan revisi sesuai catatan.',
                    'created_at' => $proposal->created_at,
                    'updated_at' => now(),
                ]);
            });

        // ===================================
        // SUMMARY
        // ===================================
        $totalProposals = Proposal::count();
        $totalReviews = Review::count();

        $this->command->info('');
        $this->command->info('✨ Seeding completed successfully!');
        $this->command->info('📊 Summary:');
        $this->command->table(
            ['Status', 'Count'],
            [
                ['Draft', Proposal::where('status', 'draft')->count()],
                ['Submitted', Proposal::where('status', 'submitted')->count()],
                ['Accepted', Proposal::where('status', 'accepted')->count()],
                ['Need Revision', Proposal::where('status', 'need_revision')->count()],
                ['---', '---'],
                ['TOTAL PROPOSALS', $totalProposals],
                ['TOTAL REVIEWS', $totalReviews],
            ]
        );
    }
}