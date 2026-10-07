<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@madebyrizky.id',
            ],
            [
                'name' => 'Administrator',
                'password' => 'Kiki9090..',
            ]
        );

        $admin->assignRole('admin');
    }
}
