<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Concerns\ReadsJsonData;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('job_categories') as $name) {
            JobCategory::firstOrCreate(['name' => $name]);
        }
        $this->command->info('✅ job categories seeded successfully');
    }
}
