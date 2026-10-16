<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasUuids;

    protected $table = 'job_categories';

    protected $fillable = [
        'name',
    ];
}
