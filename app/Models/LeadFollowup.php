<?php

namespace App\Models;

use App\Enums\FollowupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    protected $table = 'lead_followups';

    protected $fillable = [
        'lead_id',
        'followup_date',
        'notes',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'followup_date' => 'datetime',
            'status' => FollowupStatus::class,
        ];
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
