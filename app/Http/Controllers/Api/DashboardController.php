<?php

namespace App\Http\Controllers\Api;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($request->user()->isSales()) {
            $query->where(
                'assigned_to',
                $request->user()->id
            );
        }

        $counts = $query
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return response()->json([
            'total_leads' => $counts->sum(),

            'new' => $counts->get(
                LeadStatus::NEW->value,
                0
            ),

            'contacted' => $counts->get(
                LeadStatus::CONTACTED->value,
                0
            ),

            'follow_up' => $counts->get(
                LeadStatus::FOLLOW_UP->value,
                0
            ),

            'converted' => $counts->get(
                LeadStatus::CONVERTED->value,
                0
            ),

            'lost' => $counts->get(
                LeadStatus::LOST->value,
                0
            ),
        ]);
    }
}
