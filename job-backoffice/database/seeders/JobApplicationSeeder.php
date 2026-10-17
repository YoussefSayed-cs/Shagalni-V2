<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use App\Concerns\ReadsJsonData;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_applications') as $row) {
            $user = User::where('email', $row['user_email'])->firstOrFail();
            $vacancy = JobVacancy::where('title', $row['vacancy_title'])->firstOrFail();

            JobApplication::updateOrCreate(
                ['job_vacancy_id' => $vacancy->id, 'user_id' => $user->id],
                [
                    'resume_id' => $user->resumes()->first()?->id,
                    'status' => $row['status'],
                ],
            );
        }

        $this->command->info('✅ job applications seeded successfully');
    }
}
