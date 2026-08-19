<?php

namespace App\Http\Controllers\Api;

use App\Enums\FollowupStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowupRequest;
use App\Http\Requests\UpdateFollowupRequest;
use App\Http\Resources\FollowupResource;
use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FollowupController extends Controller
{
    public function store(
        StoreFollowupRequest $request,
        Lead $lead
    ) {
        $user = $request->user();

        if (
            $user->isSales() &&
            $lead->assigned_to !== $user->id
        ) {
            abort(403, 'You can only manage your assigned leads.');
        }

        if ($lead->isClosed()) {
            throw ValidationException::withMessages([
                'lead' => 'Converted or lost leads cannot receive follow-ups.',
            ]);
        }

        $followup = $lead->followups()->create([
            'followup_date' => $request->followup_date,
            'notes' => $request->notes,
            'status' => $request->status
                ?? FollowupStatus::PENDING->value,
            'created_by' => $user->id,
        ]);

        return response()->json([
            'message' => 'Follow-up created successfully.',
            'data' => new FollowupResource(
                $followup->load('creator')
            ),
        ], 201);
    }

    public function index(
        Request $request,
        Lead $lead
    ) {
        $user = $request->user();

        if (
            $user->isSales() &&
            $lead->assigned_to !== $user->id
        ) {
            abort(403);
        }

        $followups = $lead->followups()
            ->with('creator')
            ->latest('followup_date')
            ->paginate(15);

        return FollowupResource::collection($followups);
    }

    public function update(
        UpdateFollowupRequest $request,
        LeadFollowup $followup
    ) {
        $user = $request->user();

        $lead = $followup->lead;

        if (
            $user->isSales() &&
            $lead->assigned_to !== $user->id
        ) {
            abort(403);
        }

        if ($lead->isConverted()) {
            throw ValidationException::withMessages([
                'followup' => 'Follow-ups for converted leads cannot be edited.',
            ]);
        }

        $followup->update($request->validated());

        return response()->json([
            'message' => 'Follow-up updated successfully.',
            'data' => new FollowupResource(
                $followup->fresh()->load('creator')
            ),
        ]);
    }
}
