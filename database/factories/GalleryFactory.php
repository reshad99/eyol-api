<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'media_type' => 'image',
            'thumbnail' => 'string',
            'url' => $this->faker->imageUrl(),
            'post_id' => mt_rand(1,10),
        ];
    }
}
