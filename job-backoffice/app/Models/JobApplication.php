<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'job_applications';

    protected $fillable = [
        'job_vacancy_id',
        'user_id',
        'resume_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobApplicationStatus::class,
        ];
    }

    /** @return BelongsTo<JobVacancy, $this> */
    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }

    /** @return BelongsTo<Resume, $this> */
    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
