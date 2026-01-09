<?php

namespace Database\Factories;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // List of realistic research topics
        $topics = [
            'Penerapan Machine Learning dalam Prediksi',
            'Analisis Sentimen Media Sosial Menggunakan',
            'Sistem Informasi Manajemen Berbasis Web untuk',
            'Implementasi Blockchain dalam Sistem',
            'Pengembangan Aplikasi Mobile untuk',
            'Studi Komparasi Algoritma dalam',
            'Optimasi Performa Database dengan',
            'Desain dan Implementasi IoT untuk',
            'Analisis Big Data menggunakan',
            'Pengembangan E-Learning berbasis',
            'Implementasi Keamanan Siber pada',
            'Perancangan Sistem Pakar untuk',
            'Analisis Kinerja Jaringan Komputer',
            'Pengembangan Chatbot menggunakan',
            'Implementasi Cloud Computing untuk',
        ];

        $subtopics = [
            'Deep Learning Neural Network',
            'Natural Language Processing',
            'Data Mining dan Visualisasi',
            'Artificial Intelligence',
            'Computer Vision',
            'Rekayasa Perangkat Lunak',
            'Keamanan Informasi',
            'Manajemen Proyek IT',
            'Basis Data Terdistribusi',
            'Internet of Things',
            'Augmented Reality',
            'Virtual Reality',
            'Smart City',
            'E-Government',
            'Financial Technology',
        ];

        $institutions = [
            'Sektor Pendidikan',
            'Industri Manufaktur',
            'Perusahaan Startup',
            'Instansi Pemerintahan',
            'Rumah Sakit',
            'Perbankan',
            'E-Commerce',
            'Telekomunikasi',
            'Transportasi Publik',
            'Pertanian Modern',
        ];

        // Generate judul
        $judul = $this->faker->randomElement($topics) . ' ' . 
                 $this->faker->randomElement($subtopics) . ' di ' . 
                 $this->faker->randomElement($institutions);

        // Generate deskripsi (abstrak)
        $deskripsi = "Penelitian ini bertujuan untuk " . strtolower($judul) . ". " .
                     "Metode yang digunakan adalah " . $this->faker->randomElement([
                         'metode kuantitatif dengan pendekatan eksperimental',
                         'metode kualitatif dengan studi kasus',
                         'mixed method research',
                         'research and development (R&D)',
                         'metode deskriptif analitis'
                     ]) . ". " .
                     "Penelitian dilakukan di " . $this->faker->city() . " " .
                     "dengan sampel sebanyak " . $this->faker->numberBetween(30, 200) . " responden. " .
                     "Hasil penelitian menunjukkan bahwa implementasi sistem yang diusulkan " .
                     "dapat meningkatkan efisiensi sebesar " . $this->faker->numberBetween(20, 80) . "% " .
                     "dibandingkan dengan sistem konvensional. " .
                     "Penelitian ini diharapkan dapat memberikan kontribusi dalam " .
                     "pengembangan " . strtolower($this->faker->randomElement($subtopics)) . " " .
                     "dan menjadi referensi untuk penelitian selanjutnya.";

        return [
            'user_id' => User::where('role', 'publisher')->inRandomOrder()->first()?->id 
                         ?? User::factory()->create(['role' => 'publisher'])->id,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'file_usulan' => 'proposals/dummy_proposal_' . $this->faker->uuid() . '.pdf',
            'status' => $this->faker->randomElement([
                'draft',
                'submitted',
                'submitted',
                'submitted', // More submitted for testing
                'under_review',
                'accepted',
                'need_revision',
                'rejected',
            ]),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the proposal is in draft status.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the proposal is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }

    /**
     * Indicate that the proposal is under review.
     */
    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'under_review',
        ]);
    }

    /**
     * Indicate that the proposal is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
            'created_at' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
        ]);
    }

    /**
     * Indicate that the proposal needs revision.
     */
    public function needRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'need_revision',
        ]);
    }

    /**
     * Indicate that the proposal is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'created_at' => $this->faker->dateTimeBetween('-4 months', '-2 months'),
        ]);
    }
}