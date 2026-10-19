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

    public function isWithdrawn(): bool
    {
        return $this->withdrawn_at !== null;
    }

    public function canBeWithdrawn(): bool
    {
        return ! $this->isWithdrawn()
            && in_array($this->status, [
                JobApplicationStatus::Pending,
                JobApplicationStatus::UnderReview,
                JobApplicationStatus::Shortlisted,
                JobApplicationStatus::Interview,
            ], true);
    }

    /**
     * Determine whether the application can move to the requested status.
     */
    public function canTransitionTo(JobApplicationStatus $nextStatus): bool
    {
        if ($this->isWithdrawn()) {
            return false;
        }

        $allowedTransitions = match ($this->status) {
            JobApplicationStatus::Pending => [
                JobApplicationStatus::UnderReview,
                JobApplicationStatus::Rejected,
            ],
            JobApplicationStatus::UnderReview => [
                JobApplicationStatus::Shortlisted,
                JobApplicationStatus::Rejected,
            ],
            JobApplicationStatus::Shortlisted => [
                JobApplicationStatus::Interview,
                JobApplicationStatus::Rejected,
            ],
            JobApplicationStatus::Interview => [
                JobApplicationStatus::Accepted,
                JobApplicationStatus::Rejected,
            ],
            JobApplicationStatus::Accepted,
            JobApplicationStatus::Rejected => [],
            default => [],
        };

        return in_array($nextStatus, $allowedTransitions, true);
    }

    /**
     * Move the application through its permitted lifecycle.
     *
     * @throws LogicException When the requested transition is not permitted.
     */
    public function transitionTo(JobApplicationStatus $nextStatus): void
    {
        if (! $this->canTransitionTo($nextStatus)) {
            throw new LogicException('This application status transition is not allowed.');
        }

        $this->forceFill(['status' => $nextStatus])->save();
    }

    /**
     * Mark the application as withdrawn without deleting its history.
     *
     * @throws LogicException When the application is already withdrawn or final.
     */
    public function withdraw(): void
    {
        if (! $this->canBeWithdrawn()) {
            throw new LogicException('This application can no longer be withdrawn.');
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
