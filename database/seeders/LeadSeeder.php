<?php

namespace Database\Seeders;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $sales = User::where('role', UserRole::SALES)
            ->where('email', 'sales1@example.com')
            ->first();

        Lead::create([
            'lead_code' => 'LEAD-SEED-001',
            'customer_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+919876543210',
            'source' => LeadSource::WEBSITE,
            'assigned_to' => $sales?->id,
            'status' => LeadStatus::NEW,
            'remarks' => 'Website enquiry.',
        ]);

        Lead::create([
            'lead_code' => 'LEAD-SEED-002',
            'customer_name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '+919876543211',
            'source' => LeadSource::REFERRAL,
            'assigned_to' => $sales?->id,
            'status' => LeadStatus::FOLLOW_UP,
            'remarks' => 'Interested in insurance plan.',
        ]);
    }
}
