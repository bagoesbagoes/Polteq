<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
// use Illuminate\Foundation\Auth\User;
use App\Models\User;
use App\Models\Post;                         
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;          

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'author_id' => User::factory(),
            'category_id' => Category::factory(),
            'slug' => Str::slug(fake()->sentence()),
            'body' => fake()->text(),
        ];
    }
}
