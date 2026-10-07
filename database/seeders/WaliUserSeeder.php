<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class WaliUserSeeder extends Seeder
{
    public function run(): void
    {
        $wali = User::updateOrCreate(
            ['email' => 'wali@test.com'],
            [
                'name' => 'Wali Kelas',
                'password' => 'Wali@123456',
            ]
        );

        $wali->assignRole('wali kelas');
    }
}
