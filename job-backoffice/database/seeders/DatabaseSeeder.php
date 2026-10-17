<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database from the JSON files in database/data.
     * The order matters: each seeder depends on the ones before it.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JobCategorySeeder::class,
            SkillSeeder::class,
            CompanySeeder::class,
            JobVacancySeeder::class,
            JobVacancySkillSeeder::class,
            JobSeekerProfileSeeder::class,
            EducationSeeder::class,
            WorkExperienceSeeder::class,
            ResumeSeeder::class,
            UserSkillSeeder::class,
            SavedJobSeeder::class,
            JobApplicationSeeder::class,
        ]);
    }
}
