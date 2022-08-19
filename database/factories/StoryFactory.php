<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'media' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean(),
            'user_id' => mt_rand(1,20),
        ];
    }
}
