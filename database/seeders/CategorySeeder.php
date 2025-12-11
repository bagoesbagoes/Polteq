<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category::factory(3)->create();

        Category::create([
            'name' => 'Web Programming', 
            'slug' => 'web-programming']);

        Category::create([
            'name' => 'Machine Learning', 
            'slug' => 'Machine-learning']);

        Category::create([
            'name' => 'Data Science', 
            'slug' => 'Data-science']);
    }
}
