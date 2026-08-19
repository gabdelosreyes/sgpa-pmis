<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Personnel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personnel>
 */
class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = ['S1', 'S2', 'S3', 'S4', 'S5'];
        
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'age' => fake()->numberBetween(18, 60),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'department' => fake()->randomElement($departments),
            'date_started' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'is_active' => fake()->boolean(90), // 90% chance of being active
        ];
    }
}
