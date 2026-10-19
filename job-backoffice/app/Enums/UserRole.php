<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case COMPANY = 'company_owner';
    case CANDIDATE = 'job_seeker';
}
