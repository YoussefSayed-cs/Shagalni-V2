<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class SavedJobSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('saved_jobs') as $row) {
            $user = User::where('email', $row['user_email'])
                ->firstOrFail();

            $company = Company::where('email', $row['company_email'])
                ->firstOrFail();

            $vacancy = JobVacancy::where('company_id', $company->id)
                ->where('title', $row['vacancy_title'])
                ->firstOrFail();

            if ($user->role !== UserRole::JOB_SEEKER) {
                throw new RuntimeException(
                    "Saved job user must be a job seeker: {$row['user_email']}"
                );
            }

            $user->savedJobs()->syncWithoutDetaching([$vacancy->id]);
        }

        $this->command->info('Saved jobs seeded successfully');
    }
}
