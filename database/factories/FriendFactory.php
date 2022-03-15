<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'friend_id' => $this->faker->numberBetween(1, 99),
        ];
    }
}