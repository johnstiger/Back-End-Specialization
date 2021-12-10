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
            'category_id' => $this->faker->randomElement(['1','2','3','4']),
            'name' => $this->faker->firstName,
            'price' => $this->faker->randomFloat(2,0,1000),
            'status' => true,
            'is_sale' => $this->faker->randomElement([true, false]),
            'description' => $this->faker->text,
            'image' => 'unnamed.png'
        ];
    }
}
