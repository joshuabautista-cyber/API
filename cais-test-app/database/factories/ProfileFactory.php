<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => $this->faker->randomDigitNotZero(),
            'fname' => $this->faker->firstName(),
            'mname' => $this->faker->optional()->firstName(),
            'lname' => $this->faker->lastName(),
            'contact_email' => $this->faker->unique()->safeEmail(),
            'contact' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->date(),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'dept_id' => $this->faker->randomDigitNotZero(),
            'college_id' => $this->faker->randomDigitNotZero(),
            'p_email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
