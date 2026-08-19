<?php

namespace App\Models;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_code',
        'customer_name',
        'email',
        'phone',
        'source',
        'assigned_to',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'source' => LeadSource::class,
            'status' => LeadStatus::class,
        ];
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class);
    }

    public function isConverted(): bool
    {
        return $this->status === LeadStatus::CONVERTED;
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [
            LeadStatus::CONVERTED,
            LeadStatus::LOST,
        ], true);
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}
