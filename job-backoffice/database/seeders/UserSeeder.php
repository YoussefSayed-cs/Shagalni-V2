<?php

namespace Database\Seeders;

use App\Models\User;
use App\Concerns\ReadsJsonData;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use ReadsJsonData;

    /**
     * Demo password for every seeded user (development only).
     */
    private const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        foreach ($this->readJson('users') as $row) {
            $user = User::withTrashed()->firstOrNew(['email' => $row['email']]);

            $user->fill([
                'name' => $row['name'],
                'password' => self::DEMO_PASSWORD,
            ]);

            // role / is_active are not mass-assignable on purpose, so set them directly.
            $user->role = $row['role'];
            $user->is_active = $row['is_active'] ?? true;
            $user->email_verified_at ??= now();

            $user->save();

            $this->command->info('✅ users seeded successfully'); 
        }
    }
}
