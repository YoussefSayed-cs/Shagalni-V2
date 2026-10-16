<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasUuids;

    protected $table = 'skills';

    protected $fillable = [
        'name',
    ];

    public function jobVacancies(): BelongsToMany
    {
        return $this->belongsToMany(JobVacancy::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
