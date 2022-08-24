<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        return [
            'name' => $name,
            'profile_photo' => $this->faker->imageUrl(),
            'username' => strtolower(str_replace(' ', '_', $name)),
            'dob' => $this->faker->date('Y-m-d'),
            'email' => $this->faker->email,
            'gender' => 'male',
            'password' => '$2y$10$mwWxXYwhKClNzgMpDmS9J.R8gQHaSVifQa9R1Gbd2X/pwMQu14v2y', // password
            'api_token' => Str::random(60), // password
            'remember_token' => Str::random(10),
        ];
    }
}
