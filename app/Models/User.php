<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'status' => UserStatus::class,
            'password' => 'hashed',
        ];
    }

    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isSales(): bool
    {
        return $this->role === UserRole::SALES;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }
}
