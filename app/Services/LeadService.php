<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeadService
{
    public function create(array $data, User $user): Lead
    {
        return DB::transaction(function () use ($data, $user) {

            $this->validateAssignment($data['assigned_to'] ?? null);

            $this->checkDuplicateActiveLead($data['email']);

            if ($user->isSales()) {
                $data['assigned_to'] = $user->id;
            }

            $data['lead_code'] = $this->generateLeadCode();

            $data['status'] ??= LeadStatus::NEW->value;

            return Lead::create($data);
        });
    }

    public function update(
        Lead $lead,
        array $data,
        User $user
    ): Lead {

        return DB::transaction(function () use ($lead, $data, $user) {

            if ($lead->isConverted()) {
                throw ValidationException::withMessages([
                    'lead' => 'Converted leads cannot be edited.',
                ]);
            }

            if ($user->isSales()) {
                unset($data['assigned_to']);
            }

            if (array_key_exists('assigned_to', $data)) {
                $this->validateAssignment($data['assigned_to']);
            }

            if (isset($data['email']) && $data['email'] !== $lead->email) {
                $this->checkDuplicateActiveLead(
                    $data['email'],
                    $lead->id
                );
            }

            if (isset($data['status'])) {
                $newStatus = LeadStatus::from($data['status']);

                $this->validateStatusTransition(
                    $lead->status,
                    $newStatus
                );
            }

            $lead->update($data);

            return $lead->fresh();
        });
    }

    public function delete(Lead $lead): void
    {
        if ($lead->isConverted()) {
            throw ValidationException::withMessages([
                'lead' => 'Converted leads cannot be deleted.',
            ]);
        }

        $lead->delete();
    }

    private function validateAssignment(?int $userId): void
    {
        if ($userId === null) {
            return;
        }

        $user = User::find($userId);

        if (
            !$user ||
            $user->role !== UserRole::SALES ||
            $user->status !== UserStatus::ACTIVE
        ) {
            throw ValidationException::withMessages([
                'assigned_to' => 'Lead can only be assigned to an active SALES user.',
            ]);
        }
    }

    private function checkDuplicateActiveLead(
        string $email,
        ?int $ignoreLeadId = null
    ): void {
        $query = Lead::query()
            ->where('email', $email)
            ->whereIn('status', [
                LeadStatus::NEW->value,
                LeadStatus::FOLLOW_UP->value,
            ]);

        if ($ignoreLeadId) {
            $query->where('id', '!=', $ignoreLeadId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'email' => 'An active lead already exists for this email.',
            ]);
        }
    }

    private function validateStatusTransition(
        LeadStatus $current,
        LeadStatus $new
    ): void {
        $allowed = [
            LeadStatus::NEW->value => [
                LeadStatus::CONTACTED->value,
                LeadStatus::LOST->value,
            ],

            LeadStatus::CONTACTED->value => [
                LeadStatus::FOLLOW_UP->value,
                LeadStatus::LOST->value,
            ],

            LeadStatus::FOLLOW_UP->value => [
                LeadStatus::CONTACTED->value,
                LeadStatus::CONVERTED->value,
                LeadStatus::LOST->value,
            ],

            LeadStatus::CONVERTED->value => [],

            LeadStatus::LOST->value => [],
        ];

        if ($current === $new) {
            return;
        }

        if (!in_array($new->value, $allowed[$current->value], true)) {
            throw ValidationException::withMessages([
                'status' => sprintf(
                    'Invalid status transition from %s to %s.',
                    $current->value,
                    $new->value
                ),
            ]);
        }
    }

    private function generateLeadCode(): string
    {
        do {
            $code = 'LEAD-' . now()->format('Ymd') . '-' .
                strtoupper(substr(uniqid(), -6));
        } while (Lead::where('lead_code', $code)->exists());

        return $code;
    }
}
