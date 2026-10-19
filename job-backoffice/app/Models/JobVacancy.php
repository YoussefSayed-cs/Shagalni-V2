<?php

namespace App\Models;

use App\Enums\ExperienceLevel;
use App\Enums\JobVacancyStatus;
use App\Enums\JobType;
use App\Enums\WorkplaceType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancy extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'job_vacancies';

    protected $fillable = [
        'company_id',
        'job_category_id',
        'title',
        'description',
        'requirements',
        'responsibilities',
        'location',
        'salary_min',
        'salary_max',
        'application_deadline',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'application_deadline' => 'date',
            'published_at' => 'datetime',
            'experience_level' => ExperienceLevel::class,
            'status' => JobVacancyStatus::class,
            'type' => JobType::class,
            'workplace_type' => WorkplaceType::class,
        ];
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<JobCategory, $this> */
    public function jobCategory(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    /** @return HasMany<JobApplication, $this> */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs')->withTimestamps();
    }

    /** @return BelongsToMany<Skill, $this> */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}
