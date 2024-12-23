<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an instance of the Faker library
        $faker = Faker::create();

        // Create 50 fake products
        foreach (range(1, 50) as $index) {
            Product::create([
                'name' => $faker->word,         // Random product name
                'price' => $faker->randomFloat(2, 5, 500), // Random price between 5 and 500
                'image' => $faker->imageUrl(640, 480, 'product', true), 
            ]);
        }
    }
}