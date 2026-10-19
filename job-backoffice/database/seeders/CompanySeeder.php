<?php

namespace Database\Seeders;

use App\Concerns\ReadsJsonData;
use App\Enums\CompanyStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

class CompanySeeder extends Seeder
{
    use ReadsJsonData;

    public function run(): void
    {
        foreach ($this->readJson('companies') as $row) {
            $owner = User::where('email', $row['owner_email'])->firstOrFail();

            if ($owner->role !== UserRole::COMPANY) {
                throw new RuntimeException(
                    "Company owner must have the company-owner role: {$row['owner_email']}"
                );
            }

            // owner_id is unique on companies, so one company per owner.
            $company = Company::withTrashed()->updateOrCreate(
                ['owner_id' => $owner->id],
                Arr::except($row, ['owner_email', 'status']),
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
