<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use RuntimeException;

class JobVacancySkillSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_vacancy_skill') as $row) {
            $company = Company::where('email', $row['company_email'])->firstOrFail();
            $vacancy = JobVacancy::where('company_id', $company->id)
                ->where('title', $row['vacancy_title'])
                ->firstOrFail();
            $skillIds = Skill::whereIn('name', $row['skills'])->pluck('id');

            if ($skillIds->count() !== count($row['skills'])) {
                throw new RuntimeException("Unknown skill in vacancy: {$row['vacancy_title']}");
            }

            $vacancy->skills()->syncWithoutDetaching($skillIds);
        }

        $this->command->info('✅ job vacancy skills seeded successfully');
    }
}
