<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FollowupTest extends TestCase
{
    use RefreshDatabase;

    public function test_followup_cannot_be_created_for_converted_lead(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        $lead = Lead::factory()->create([
            'status' => LeadStatus::CONVERTED,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson(
            "/api/leads/{$lead->id}/followups",
            [
                'followup_date' => now()
                    ->addDay()
                    ->format('Y-m-d H:i:s'),

                'notes' => 'Should not be allowed.',
                'status' => 'PENDING',
            ]
        );

        $response->assertStatus(422);
    }
}
