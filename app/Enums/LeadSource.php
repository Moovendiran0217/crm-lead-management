<?php

namespace App\Enums;

enum LeadSource: string
{
    case WEBSITE = 'WEBSITE';
    case REFERRAL = 'REFERRAL';
    case PHONE = 'PHONE';
    case EMAIL = 'EMAIL';
    case CAMPAIGN = 'CAMPAIGN';
    case OTHER = 'OTHER';
}
