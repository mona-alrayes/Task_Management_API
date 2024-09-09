<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(50), 
            'description' => $this->faker->text(1000), 
            'priority' => $this->faker->randomElement(['highest', 'high', 'medium', 'low', 'lowest']),
            'assigned_to' => null, // Always null
            'status' => 'To Do', // Always 'To Do'
            'due_date' => Carbon::now()->addDays(rand(1, 30))->format('d-m-Y H:i'), 
        ];
    }
}
