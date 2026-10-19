<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

class EducationSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('educations') as $row) {
            $user = User::where('email', $row['user_email'])
                ->firstOrFail();

            if ($user->role !== UserRole::JOB_SEEKER) {
                throw new RuntimeException(
                    "Education user must be a job seeker: {$row['user_email']}"
                );
            }

            $user->educations()->updateOrCreate(
                [
                    'school' => $row['school'],
                    'degree' => $row['degree'],
                ],
                Arr::except($row, ['user_email']),
            );
        }

        $this->command->info('Educations seeded successfully');
    }
}