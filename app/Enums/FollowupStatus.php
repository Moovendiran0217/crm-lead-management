<?php

namespace App\Enums;

enum FollowupStatus: string
{
    case PENDING = 'PENDING';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
}