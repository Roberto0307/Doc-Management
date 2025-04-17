<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubProcess>
 */
class SubProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => fake()->sentence(3),
            'process_id' => fake()->randomElement([1, 2, 3]),
            'acronym' => fake()->randomElement(['SGI', 'RH', 'PRD']),
        ];
    }
}
