<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Concerns\ReadsJsonData;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('skills') as $name) {
            Skill::firstOrCreate(['name' => $name]);
        }

        $this->command->info('✅ skills seeded successfully');
    }
}
