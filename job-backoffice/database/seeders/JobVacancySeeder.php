<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class JobVacancySeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_vacancies') as $row) {
            $company = Company::where('email', $row['company_email'])->firstOrFail();
            $category = JobCategory::where('name', $row['category'])->firstOrFail();

            $vacancy = JobVacancy::withTrashed()->updateOrCreate(
                ['company_id' => $company->id, 'title' => $row['title']],
                [
                    'job_category_id' => $category->id,
                    ...Arr::except($row, ['company_email', 'category', 'title']),
                ],
            );

            if ($vacancy->trashed()) {
                $vacancy->restore();
            }
        }

        $this->command->info('✅ job vacancies seeded successfully');
    }
}
