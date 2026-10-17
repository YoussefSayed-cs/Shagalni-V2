<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

class ResumeSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('resumes') as $row) {
            $user = User::where('email', $row['user_email'])->firstOrFail();

            if ($user->role !== 'job_seeker') {
                throw new RuntimeException("Resume user must be a job seeker: {$row['user_email']}");
            }

            $resume = Resume::withTrashed()->updateOrCreate(
                ['user_id' => $user->id, 'file_path' => $row['file_path']],
                Arr::except($row, ['user_email']),
            );

            if ($resume->trashed()) {
                $resume->restore();
            }
        }

        $this->command->info('✅ resumes seeded successfully');
    }
}
