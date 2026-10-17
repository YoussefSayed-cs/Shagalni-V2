<?php

namespace App\Enums;

enum JobApplicationStatus: string
{
    case Pending = 'pending';
    case UnderReview = 'under_review';
    case Shortlisted = 'shortlisted';
    case Interview = 'interview';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Withdrawn = 'withdrawn';
}
