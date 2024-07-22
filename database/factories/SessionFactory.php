<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start' => fake()->dateTimeBetween(now()->subDay(), now()),
            'end' => fake()->dateTimeBetween(now()->subHour(), now()),
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
        ];
    }
}
