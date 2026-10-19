<?php

namespace Tests\\Unit;

use App\\Enums\\JobApplicationStatus;
use App\\Models\\JobApplication;
use LogicException;
use PHPUnit\\Framework\\TestCase;

class JobApplicationLifecycleTest extends TestCase
{
    public function test_pending_application_can_move_to_under_review_or_rejected(): void
    {
        $application = $this->applicationWithStatus(JobApplicationStatus::Pending);

        $this->assertTrue($application->canTransitionTo(JobApplicationStatus::UnderReview));
        $this->assertTrue($application->canTransitionTo(JobApplicationStatus::Rejected));
        $this->assertFalse($application->canTransitionTo(JobApplicationStatus::Accepted));
    }

    public function test_each_non_final_status_only_allows_valid_next_steps(): void
    {
        $cases = [
            [
                JobApplicationStatus::UnderReview,
                [JobApplicationStatus::Shortlisted, JobApplicationStatus::Rejected],
            ],
            [
                JobApplicationStatus::Shortlisted,
                [JobApplicationStatus::Interview, JobApplicationStatus::Rejected],
            ],
            [
                JobApplicationStatus::Interview,
                [JobApplicationStatus::Accepted, JobApplicationStatus::Rejected],
            ],
        ];

        foreach ($cases as [$currentStatus, $allowedStatuses]) {
            $application = $this->applicationWithStatus($currentStatus);

            foreach (JobApplicationStatus::cases() as $nextStatus) {
                $this->assertSame(
                    in_array($nextStatus, $allowedStatuses, true),
                    $application->canTransitionTo($nextStatus),
                    sprintf('%s should %s transition to %s', $currentStatus->value, in_array($nextStatus, $allowedStatuses, true) ? 'allow' : 'not allow', $nextStatus->value),
                );
            }
        }
    }

    public function test_final_statuses_cannot_transition_to_another_status(): void
    {
        foreach ([JobApplicationStatus::Accepted, JobApplicationStatus::Rejected] as $status) {
            $application = $this->applicationWithStatus($status);

            foreach (JobApplicationStatus::cases() as $nextStatus) {
                $this->assertFalse($application->canTransitionTo($nextStatus));
            }
        }
    }

    public function test_withdrawal_is_allowed_only_before_a_final_status_and_only_once(): void
    {
        foreach ([
            JobApplicationStatus::Pending,
            JobApplicationStatus::UnderReview,
            JobApplicationStatus::Shortlisted,
            JobApplicationStatus::Interview,
        ] as $status) {
            $application = $this->applicationWithStatus($status);
            $this->assertTrue($application->canBeWithdrawn());
        }

        foreach ([JobApplicationStatus::Accepted, JobApplicationStatus::Rejected] as $status) {
            $application = $this->applicationWithStatus($status);
            $this->assertFalse($application->canBeWithdrawn());
        }

        $withdrawnApplication = $this->applicationWithStatus(JobApplicationStatus::Pending);
        $withdrawnApplication->withdrawn_at = now();

        $this->assertTrue($withdrawnApplication->isWithdrawn());
        $this->assertFalse($withdrawnApplication->canBeWithdrawn());
        $this->assertFalse($withdrawnApplication->canTransitionTo(JobApplicationStatus::UnderReview));
    }

    public function test_same_or_skipped_status_transition_is_not_allowed(): void
    {
        $application = $this->applicationWithStatus(JobApplicationStatus::Pending);

        $this->assertFalse($application->canTransitionTo(JobApplicationStatus::Pending));
        $this->assertFalse($application->canTransitionTo(JobApplicationStatus::Shortlisted));
        $this->assertFalse($application->canTransitionTo(JobApplicationStatus::Interview));
    }

    private function applicationWithStatus(JobApplicationStatus $status): JobApplication
    {
        $application = new JobApplication();
        $application->status = $status;

        return $application;
    }
}
