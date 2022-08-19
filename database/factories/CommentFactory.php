<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->text(200),
            'user_id' => mt_rand(1,21),
            'post_id' => mt_rand(1,10),
        ];
    }
}
