<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Volunteer>
 */
class VolunteerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'mail' => $this->faker->unique()->email(),
            'phone' => $this->faker->phoneNumber(),
            'birthday' => $this->faker->date(),
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', null])

        ];
    }
}
