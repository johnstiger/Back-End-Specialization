<?php

namespace Database\Factories;

use App\Models\Sizes;
use Illuminate\Database\Eloquent\Factories\Factory;

class SizesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sizes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'size' => $this->faker->unique()->randomElement(['M','S','L','XXL','XL','N/A']),
        ];
    }
}
