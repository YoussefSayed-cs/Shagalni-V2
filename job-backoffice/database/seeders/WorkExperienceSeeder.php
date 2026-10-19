<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

class WorkExperienceSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('work_experiences') as $row) {
            $user = User::where('email', $row['user_email'])
                ->firstOrFail();

            if ($user->role !== UserRole::JOB_SEEKER) {
                throw new RuntimeException(
                    "Work experience user must be a job seeker: {$row['user_email']}"
                );
            }

            $user->workExperiences()->updateOrCreate(
                [
                    'company_name' => $row['company_name'],
                    'job_title' => $row['job_title'],
                ],
                Arr::except($row, ['user_email']),
            );
        }

        $this->command->info('Work experiences seeded successfully');
    }
}