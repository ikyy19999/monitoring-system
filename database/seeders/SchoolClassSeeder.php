<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $homeroomTeacher = User::role('wali kelas')
            ->where('email', 'wali@test.com')
            ->first();

        SchoolClass::firstOrCreate(
            [
                'name' => 'XII RPL 1',
                'academic_year' => '2026/2027',
            ],
            [
                'grade' => '12',
                'homeroom_teacher_id' => $homeroomTeacher?->id,
            ]
        );

        SchoolClass::firstOrCreate(
            [
                'name' => 'XII RPL 2',
                'academic_year' => '2026/2027',
            ],
            [
                'grade' => '12',
                'homeroom_teacher_id' => $homeroomTeacher?->id,
            ]
        );

        SchoolClass::firstOrCreate(
            [
                'name' => 'XI RPL 1',
                'academic_year' => '2026/2027',
            ],
            [
                'grade' => '11',
                'homeroom_teacher_id' => $homeroomTeacher?->id,
            ]
        );

        SchoolClass::firstOrCreate(
            [
                'name' => 'XI RPL 2',
                'academic_year' => '2026/2027',
            ],
            [
                'grade' => '11',
                'homeroom_teacher_id' => $homeroomTeacher?->id,
            ]
        );

        SchoolClass::firstOrCreate(
            [
                'name' => 'X RPL 1',
                'academic_year' => '2026/2027',
            ],
            [
                'grade' => '10',
                'homeroom_teacher_id' => $homeroomTeacher?->id,
            ]
        );

        SchoolClass::firstOrCreate(
            [
                'name' => 'X RPL 2',
                'academic_year' => '2026/2027',
            ],
            [
                'grade' => '10',
                'homeroom_teacher_id' => $homeroomTeacher?->id,
            ]
        );
    }
}
