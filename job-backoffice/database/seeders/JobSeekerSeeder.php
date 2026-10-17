<?php

namespace Database\Seeders;

use App\Models\JobSeekerProfile;
use App\Models\JobVacancy;
use App\Models\Skill;
use App\Models\User;
use App\Concerns\ReadsJsonData;
use Illuminate\Database\Seeder;
use RuntimeException;

class JobSeekerSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_seekers') as $row) {
            $user = User::where('email', $row['email'])->firstOrFail();

            JobSeekerProfile::updateOrCreate(['user_id' => $user->id], $row['profile']);

            $skillIds = Skill::whereIn('name', $row['skills'])->pluck('id');

            if ($skillIds->count() !== count($row['skills'])) {
                throw new RuntimeException("Unknown skill for user: {$row['email']}");
            }

            $user->skills()->sync($skillIds);

            foreach ($row['educations'] as $education) {
                $user->educations()->updateOrCreate(
                    ['school' => $education['school'], 'degree' => $education['degree']],
                    $education,
                );
            }

            foreach ($row['work_experiences'] as $experience) {
                $user->workExperiences()->updateOrCreate(
                    ['company_name' => $experience['company_name'], 'job_title' => $experience['job_title']],
                    $experience,
                );
            }

            $user->resumes()->updateOrCreate(
                ['file_path' => $row['resume']['file_path']],
                $row['resume'],
            );

            $savedIds = JobVacancy::whereIn('title', $row['saved_jobs'])->pluck('id');

            if ($savedIds->count() !== count($row['saved_jobs'])) {
                throw new RuntimeException("Unknown saved job for user: {$row['email']}");
            }

            $user->savedJobs()->sync($savedIds);
        }

        $this->command->info('✅ job seekers seeded successfully');
    }
}
