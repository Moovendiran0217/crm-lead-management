<?php

namespace Database\Factories;

use App\Enums\FollowupStatus;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadFollowup>
 */
class LeadFollowupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'followup_date' => fake()->dateTimeBetween('now', '+30 days'),
            'notes' => fake()->sentence(),
            'status' => FollowupStatus::PENDING,
            'created_by' => User::factory(),
        ];
    }
}
