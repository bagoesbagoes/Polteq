<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // 'username' => fake()->unique()->username(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'publisher',
            'nidn_nuptk' => fake()->numerify('##########'),
            'jabatan_fungsional' => fake()->randomElement([
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Profesor',
            ]),
            'prodi' => fake ()->randomElement([
                'English for Business & Profesional Communication',
                'Bisnis kreatif', 
                'Teknologi Produksi Tanaman Perkebunan',
                'Teknologi pangan'
            ])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function publisher(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'publisher',
        ]);
    }

    public function reviewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'reviewer',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

}
