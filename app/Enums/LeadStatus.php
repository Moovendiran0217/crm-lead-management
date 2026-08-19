<?php

namespace App\Enums;

enum LeadStatus: string
{
    case NEW = 'NEW';
    case CONTACTED = 'CONTACTED';
    case FOLLOW_UP = 'FOLLOW_UP';
    case CONVERTED = 'CONVERTED';
    case LOST = 'LOST';

    public function isActive(): bool
    {
        return in_array($this, [
            self::NEW,
            self::FOLLOW_UP,
        ], true);
    }
}
