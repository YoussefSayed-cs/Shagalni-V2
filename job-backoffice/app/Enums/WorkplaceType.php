<?php

namespace App\Enums;

enum WorkplaceType: string
{
    case Remote = 'remote';
    case OnSite = 'on-site';
    case Hybrid = 'hybrid';
}
