<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class UserSkillSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('skill_user') as $row) {
            $user = User::where('email', $row['user_email'])->firstOrFail();
            $skill = Skill::where('name', $row['skill'])->firstOrFail();

            if ($user->role !== 'job_seeker') {
                throw new RuntimeException("Skill user must be a job seeker: {$row['user_email']}");
            }

            $user->skills()->syncWithoutDetaching([$skill->id]);
        }

        $this->command->info('✅ user skills seeded successfully');
    }
}
