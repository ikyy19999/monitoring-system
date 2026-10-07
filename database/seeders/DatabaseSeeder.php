<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            SchoolClassSeeder::class,
            SubjectSeeder::class,
            StudentSeeder::class,
            ParentProfileSeeder::class,
            TeachingAssignmentSeeder::class,
            AcademicCalendarSeeder::class,
            AcademicGradeSeeder::class,
            StudentActivitySeeder::class,
            WaliUserSeeder::class,
        ]);
    }
}
