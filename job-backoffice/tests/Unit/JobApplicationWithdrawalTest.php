<?php

namespace Tests\Unit;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use LogicException;
use PHPUnit\Framework\TestCase;

class JobApplicationWithdrawalTest extends TestCase
{
    public function test_non_final_applications_can_be_withdrawn(): void
    {
        foreach ([
            JobApplicationStatus::PENDING,
            JobApplicationStatus::UNDER_REVIEW,
            JobApplicationStatus::SHORTLISTED,
            JobApplicationStatus::INTERVIEW,
        ] as $status) {
            $application = new JobApplication();
            $application->status = $status;

            $this->assertTrue($application->canBeWithdrawn());
        }
    }

    public function test_final_applications_cannot_be_withdrawn(): void
    {
        foreach ([
            JobApplicationStatus::ACCEPTED,
            JobApplicationStatus::REJECTED,
        ] as $status) {
            $application = new JobApplication();
            $application->status = $status;

            $this->assertFalse($application->canBeWithdrawn());
            $this->expectNotToPerformAssertions();
        }
    }

    public function test_withdrawal_is_rejected_for_a_final_application(): void
    {
        $application = new JobApplication();
        $application->status = JobApplicationStatus::ACCEPTED;

        $this->expectException(LogicException::class);

        $application->withdraw();
    }

    public function test_already_withdrawn_applications_cannot_be_withdrawn_again(): void
    {
        $application = new JobApplication();
        $application->status = JobApplicationStatus::INTERVIEW;
        $application->withdrawn_at = now();

        $this->assertFalse($application->canBeWithdrawn());
    }
}
