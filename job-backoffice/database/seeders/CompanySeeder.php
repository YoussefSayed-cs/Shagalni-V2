<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Models\Company;
use App\Models\User;
use App\Enums\CompanyStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CompanySeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('companies') as $row) {
            $owner = User::where('email', $row['owner_email'])->firstOrFail();

            // owner_id is unique on companies, so one company per owner.
            $company = Company::withTrashed()->updateOrCreate(
                ['owner_id' => $owner->id],
                Arr::except($row, ['owner_email']),
            );

            if ($company->trashed()) {
                $company->restore();
            }

            $company->status = CompanyStatus::from($row['status'] ?? 'pending');
            $company->save();
        }
        $this->command->info('✅ companies seeded successfully');
    }
}
