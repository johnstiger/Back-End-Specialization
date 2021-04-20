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
        $unit = $this->faker->randomDigit();
        return [
            'category_id' => $this->faker->randomElement(['1','2','3','4']),
            'name' => $this->faker->name,
            'unit_measure' => $unit,
            'avail_unit_measure' => $unit,
            'price' => $this->faker->randomFloat(2,0,1000),
            'status' => true,
            'description' => $this->faker->text,
            'image' => $this->faker->url
        ];
    }
}
