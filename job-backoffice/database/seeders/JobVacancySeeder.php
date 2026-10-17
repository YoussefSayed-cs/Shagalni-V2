<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\Skill;
use App\Concerns\ReadsJsonData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

class JobVacancySeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_vacancies') as $row) {
            $company = Company::where('email', $row['company_email'])->firstOrFail();
            $category = JobCategory::where('name', $row['category'])->firstOrFail();

            $skillIds = Skill::whereIn('name', $row['skills'])->pluck('id');

            if ($skillIds->count() !== count($row['skills'])) {
                throw new RuntimeException("Unknown skill in vacancy: {$row['title']}");
            }

            $vacancy = JobVacancy::updateOrCreate(
                ['company_id' => $company->id, 'title' => $row['title']],
                [
                    'job_category_id' => $category->id,
                    ...Arr::except($row, ['company_email', 'category', 'skills', 'title']),
                ],
            );

            $vacancy->skills()->sync($skillIds);
        }

        $this->command->info('✅ job vacancies seeded successfully');
    }
}
