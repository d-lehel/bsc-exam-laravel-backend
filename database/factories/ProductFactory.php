<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(3,12),
            'is_active' => 1,
            'name' => $this->faker->word,
            'description' => $this->faker->text(100),
            'amount' => rand(1,5),
            'expiration' => $this->faker->dateTimeBetween('now','+1 week'),
            'pickup_adress' => $this->faker->streetName,
            'longitude' => $this->faker->longitude(22.82,26.87), // min max
            'latitude' => $this->faker->latitude(44.21,47.24),   // min max
            'price' => rand(1,1000),
        ];
    }
}
