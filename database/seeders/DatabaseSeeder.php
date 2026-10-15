<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Category::create(['name'=>'T-shert' , 'slug'=>'t-shert']);
        Category::create(['name'=>'Jaens' , 'slug'=>'jeans']);
        Product::factory(20)->create();
    }
}
