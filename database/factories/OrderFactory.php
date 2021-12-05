<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::select('id')->get();
        $userIds = $users->map(function($user) {
            return $user->id;
        });

        return [
            'user_id' => $this->faker->randomElement($userIds->all()),
            'total' => $this->faker->randomElement([5,6,2,7]),
            'tracking_code' => $this->faker->firstname . ' ' . $this->faker->lastname,
        ];
    }
}
