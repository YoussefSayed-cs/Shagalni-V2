<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\JobApplicationStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class JobApplicationSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_applications') as $row) {
            $user = User::where('email', $row['user_email'])
                ->firstOrFail();

            if ($user->role !== UserRole::JOB_SEEKER) {
                throw new RuntimeException(
                    "Application user must be a job seeker: {$row['user_email']}"
                );
            }

            $company = Company::where('email', $row['company_email'])
                ->firstOrFail();

            $vacancy = JobVacancy::where('company_id', $company->id)
                ->where('title', $row['vacancy_title'])
                ->firstOrFail();

            $status = JobApplicationStatus::tryFrom($row['status']);

            if ($status === null) {
                throw new RuntimeException(
                    "Invalid application status: {$row['status']}"
                );
            }

            $application = JobApplication::updateOrCreate(
                [
                    'job_vacancy_id' => $vacancy->id,
                    'user_id' => $user->id,
                ],
                [
                    'resume_id' => $user->resumes()->first()?->id,
                    'withdrawn_at' => null,
                ],
            );

            $application->status = $status;
            $application->save();
        }

        $this->command->info('Job applications seeded successfully');
    }
}
