<?php

namespace Database\Factories;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lead_code' => 'LEAD-' . fake()->unique()->numerify('######'),

            'customer_name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'phone' => '+91' . fake()->numerify('##########'),

            'source' => fake()->randomElement(
                LeadSource::cases()
            ),

            'assigned_to' => User::factory(),

            'status' => LeadStatus::NEW,

            'remarks' => fake()->sentence(),
        ];
    }
}
