<?php

namespace App\Models;

use App\Enums\CompanyStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'logo_path',
        'industry',
        'size',
        'location',
        'website',
        'email',
        'phone',
    ];

    protected function casts(): array
{
    return [
        'social_links' => 'array',
        'status' => CompanyStatus::class,
    ];
}

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /** @return HasMany<JobVacancy, $this> */
    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }
}
