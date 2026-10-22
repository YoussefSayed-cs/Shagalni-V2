<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case COMPANY_Owner = 'company_owner';
    case JOB_SEEKER = 'job_seeker';
}
