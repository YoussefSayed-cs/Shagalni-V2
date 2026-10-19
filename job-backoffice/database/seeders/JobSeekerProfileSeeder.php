<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\UserRole;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

class JobSeekerProfileSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_seeker_profiles') as $row) {
            $user = User::where('email', $row['user_email'])
                ->firstOrFail();

            if ($user->role !== UserRole::JOB_SEEKER) {
                throw new RuntimeException(
                    "Profile user must be a job seeker: {$row['user_email']}"
                );
            }

            JobSeekerProfile::updateOrCreate(
                ['user_id' => $user->id],
                Arr::except($row, ['user_email']),
            );
        }

        $this->command->info(
            'Job seeker profiles seeded successfully'
        );
    }
}