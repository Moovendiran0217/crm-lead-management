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

class LeadManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);
    }

    private function sales(): User
    {
        return User::factory()->create([
            'role' => UserRole::SALES,
            'status' => UserStatus::ACTIVE,
        ]);
    }

    public function test_lead_can_be_created(): void
    {
        $admin = $this->admin();

        $sales = $this->sales();

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Test Customer',
            'email' => 'test@example.com',
            'phone' => '+919876543210',
            'source' => 'WEBSITE',
            'assigned_to' => $sales->id,
            'status' => 'NEW',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath(
                'data.email',
                'test@example.com'
            );

        $this->assertDatabaseHas('leads', [
            'email' => 'test@example.com',
            'status' => LeadStatus::NEW->value,
        ]);
    }

    public function test_duplicate_active_lead_is_rejected(): void
    {
        $admin = $this->admin();

        Sanctum::actingAs($admin);

        Lead::factory()->create([
            'email' => 'duplicate@example.com',
            'status' => LeadStatus::NEW,
        ]);

        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Duplicate',
            'email' => 'duplicate@example.com',
            'phone' => '+919876543210',
            'source' => 'WEBSITE',
            'status' => 'NEW',
        ]);

        $response->assertStatus(422);
    }

    public function test_inactive_user_cannot_be_assigned(): void
    {
        $admin = $this->admin();

        $inactive = User::factory()->create([
            'role' => UserRole::SALES,
            'status' => UserStatus::INACTIVE,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/leads', [
            'customer_name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => '+919876543210',
            'source' => 'WEBSITE',
            'assigned_to' => $inactive->id,
            'status' => 'NEW',
        ]);

        $response->assertStatus(422);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $admin = $this->admin();

        $lead = Lead::factory()->create([
            'status' => LeadStatus::CONVERTED,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->putJson(
            "/api/leads/{$lead->id}",
            [
                'status' => 'NEW',
            ]
        );

        $response->assertStatus(422);
    }

    public function test_converted_lead_cannot_be_deleted(): void
    {
        $admin = $this->admin();

        $lead = Lead::factory()->create([
            'status' => LeadStatus::CONVERTED,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson(
            "/api/leads/{$lead->id}"
        );

        $response->assertStatus(422);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
        ]);
    }
}
