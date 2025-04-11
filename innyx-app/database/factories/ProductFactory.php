<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 999),
            'valid_until' => $this->faker->date(),
            'image' => $this->faker->image('storage/app/public/products', 640, 480, null, false),
            'category_id' => Category::factory(),
        ];
    }
}

