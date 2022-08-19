<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //Media type factory qurasdir
        return [
            'caption' => $this->faker->text(200),
            'user_id' => mt_rand(1,20),
        ];
    }
}
