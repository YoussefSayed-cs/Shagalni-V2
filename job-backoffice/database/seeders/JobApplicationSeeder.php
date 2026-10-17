<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\JobApplicationStatus;
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
            $user = User::where('email', $row['user_email'])->firstOrFail();
            $vacancy = JobVacancy::where('title', $row['vacancy_title'])->firstOrFail();
            $status = JobApplicationStatus::tryFrom($row['status'])
                ?? throw new RuntimeException("Invalid application status: {$row['status']}");

            $application = JobApplication::withTrashed()->updateOrCreate(
                ['job_vacancy_id' => $vacancy->id, 'user_id' => $user->id],
                [
                    'resume_id' => $user->resumes()->first()?->id,
                    'status' => $status,
                ],
            );

            if ($application->trashed()) {
                $application->restore();
            }
        }

        $this->command->info('✅ job applications seeded successfully');
    }
}
