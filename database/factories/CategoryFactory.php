<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category_name =  rtrim($this->faker->sentence(1), '.');
        return [
            'name' => $category_name,
            'slug' => Str::slug($category_name),
        ];
    }
}
