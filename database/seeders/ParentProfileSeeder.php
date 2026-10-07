<?php

namespace Database\Seeders;

use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.parent@test.com',
                'full_name' => 'Budi Santoso',
                'phone' => '081298765432',
                'address' => 'Jakarta',
                'students' => ['10001', '10002'],
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.parent@test.com',
                'full_name' => 'Siti Rahma',
                'phone' => '081298765433',
                'address' => 'Depok',
                'students' => ['10003'],
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.parent@test.com',
                'full_name' => 'Andi Wijaya',
                'phone' => '081298765434',
                'address' => 'Bekasi',
                'students' => ['10005', '10006'],
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.parent@test.com',
                'full_name' => 'Dewi Lestari',
                'phone' => '081298765435',
                'address' => 'Jakarta',
                'students' => ['10007'],
            ],
        ];

        foreach ($parents as $parentData) {
            $user = User::firstOrCreate(
                [
                    'email' => $parentData['email'],
                ],
                [
                    'name' => $parentData['name'],
                    'password' => Hash::make('Parent@123456'),
                ]
            );

            $user->assignRole('orang tua');

            $parentProfile = ParentProfile::firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'full_name' => $parentData['full_name'],
                    'phone' => $parentData['phone'],
                    'address' => $parentData['address'],
                ]
            );

            foreach ($parentData['students'] as $nis) {
                $student = Student::where('nis', $nis)->first();

                if ($student) {
                    $parentProfile->students()->syncWithoutDetaching([
                        $student->id => [
                            'relationship' => 'parent',
                        ],
                    ]);
                }
            }
        }
    }
}
