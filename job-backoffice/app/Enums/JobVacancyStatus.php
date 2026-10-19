<?php

namespace App\Enums;

enum JobVacancyStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
}
