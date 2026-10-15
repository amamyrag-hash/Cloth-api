<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id'=>Category::inRandomOrder()->value('id'),
            'name'=>fake()->words(2 , true),
            'slug'=>fake()->unique()->slug(),
            'description'=>fake()->sentence(),
            'price'=>fake()->randomFloat(2,10,200),
            'stock'=>fake()->numberBetween(0,50)
        ];
    }
}
