<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class JobApplication extends Model
{
    use HasUuids;

    protected $table = 'job_applications';

    protected $fillable = [
        'job_vacancy_id',
        'user_id',
        'resume_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobApplicationStatus::class,
            'withdrawn_at' => 'datetime',
        ];
    }

    public function canBeWithdrawn(): bool
    {
        return $this->withdrawn_at === null
            && in_array($this->status, [
                JobApplicationStatus::Pending,
                JobApplicationStatus::UnderReview,
                JobApplicationStatus::Shortlisted,
                JobApplicationStatus::Interview,
            ], true);
    }

    public function withdraw(): void
    {
        if (! $this->canBeWithdrawn()) {
            throw new LogicException(
                'This application can no longer be withdrawn.'
            );
        }

        $this->forceFill(['withdrawn_at' => now()])->save();
    }

    /** @return BelongsTo<JobVacancy, $this> */
    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }

    /** @return BelongsTo<Resume, $this> */
    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class)->withTrashed();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
