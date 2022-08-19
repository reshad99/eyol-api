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
            'category_id' => mt_rand(1,2),
            'username' => strtolower(str_replace(' ', '_', $name)),
            'bio' => $this->faker->text(100),
            'dob' => $this->faker->date('Y-m-d'),
            'phone' => $this->faker->phoneNumber(),
            'gender' => 'male',
            'alcohol' => 'non-alcohol',
            'zodiac' => $this->faker->sentence(1),
            'height' => mt_rand(155,200),
            'interest' => 'female',
            'verified' => false,

            'phone_verified_at' => now(),
            'password' => '$2y$10$mwWxXYwhKClNzgMpDmS9J.R8gQHaSVifQa9R1Gbd2X/pwMQu14v2y', // password
            'api_token' => Str::random(60), // password
            'remember_token' => Str::random(10),
        ];
    }
}
