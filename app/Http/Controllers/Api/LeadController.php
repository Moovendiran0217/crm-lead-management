<?php

namespace App\Http\Controllers\Api;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private readonly LeadService $leadService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Lead::query()
            ->with('assignedUser')
            ->latest();

        if ($user->isSales()) {
            $query->where('assigned_to', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($q) use ($search) {
                $q->where('lead_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if (
            $request->filled('assigned_to') &&
            $user->isAdmin()
        ) {
            $query->where(
                'assigned_to',
                $request->assigned_to
            );
        }

        $perPage = min(
            max((int) $request->get('per_page', 15), 1),
            100
        );

        return LeadResource::collection(
            $query->paginate($perPage)
        );
    }

    public function store(StoreLeadRequest $request)
    {
        $this->authorize('create', Lead::class);

        $lead = $this->leadService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Lead created successfully.',
            'data' => new LeadResource(
                $lead->load('assignedUser')
            ),
        ], 201);
    }

    public function show(Request $request, Lead $lead)
    {
        $this->authorize('view', $lead);

        return new LeadResource(
            $lead->load([
                'assignedUser',
                'followups.creator',
            ])
        );
    }

    public function update(
        UpdateLeadRequest $request,
        Lead $lead
    ) {
        $this->authorize('update', $lead);

        $lead = $this->leadService->update(
            $lead,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Lead updated successfully.',
            'data' => new LeadResource(
                $lead->load('assignedUser')
            ),
        ]);
    }

    public function destroy(
        Request $request,
        Lead $lead
    ): JsonResponse {
        $this->authorize('delete', $lead);

        $this->leadService->delete($lead);

        return response()->json([
            'message' => 'Lead deleted successfully.',
        ]);
    }
}
