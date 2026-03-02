<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PkmProposal;

class PkmProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publisherCount = User::where('role', 'publisher')->count();

        if ($publisherCount <3) {
            echo " Tidak cukup user dengan role publisher. membuat 5 publisher dummy...\n";

            User::factory()->count(5)->create([
                'role' => 'publisher',
                'nidn_nuptk' => fake()->numerify('###########'),
                'jabatan_fungsional' => fake()->randomElement([
                    'Asisten Ahli',
                    'Lektor',
                    'Lektor Kepala',
                    'Proffesor',
                ]),
                'prodi' => fake()->randomElement([
                    'English for Business & Professional Communication',
                    'Bisnis Kreatif',
                    'Teknologi Produksi Tanaman Perkebunan',
                    'Teknologi Pangan',
                ])
            ]);

            echo "5 publisher dummy berhasil dibuat.\n";
        }

        echo "Membuat PKM dummy data...\n";

        PkmProposal::factory()->count(10)->draft()->create();
        echo "✅ 10 PKM Draft\n";

        PkmProposal::factory()->count(25)->submitted()->create();
        echo "✅ 25 PKM Submitted\n";

        PkmProposal::factory()->count(12)->accepted()->create();
        echo "✅ 12 PKM Accepted\n";

        PkmProposal::factory()->count(8)->needRevision()->create();
        echo "✅ 8 PKM Need Revision\n";

        // Create some with specific sumber dana
        PkmProposal::factory()->count(5)->dipa()->submitted()->create();
        echo "✅ 5 PKM DIPA (Submitted)\n";

        PkmProposal::factory()->count(5)->nonDipa()->accepted()->create();
        echo "✅ 5 PKM Non-DIPA (Accepted)\n";

        $total = PkmProposal::count();
        echo "\n🎉 Total: {$total} PKM dummy berhasil dibuat!\n";
    }
}
