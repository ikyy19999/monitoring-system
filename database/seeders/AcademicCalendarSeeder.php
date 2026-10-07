<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use Illuminate\Database\Seeder;

class AcademicCalendarSeeder extends Seeder
{
    public function run(): void
    {
        AcademicCalendar::updateOrCreate(
            [
                'academic_year' => '2026/2027',
                'semester' => 1,
            ],
            [
                'start_date' => '2026-07-13',
                'end_date' => '2026-12-18',
                'grade_input_start_date' => '2026-12-18',
                'grade_input_end_date' => '2026-12-23',
                'is_active' => true,
                'notes' => 'Semester 1 Tahun Ajaran 2026/2027.',
            ]
        );

        AcademicCalendar::updateOrCreate(
            [
                'academic_year' => '2026/2027',
                'semester' => 2,
            ],
            [
                'start_date' => '2027-01-04',
                'end_date' => '2027-06-18',
                'grade_input_start_date' => '2027-06-18',
                'grade_input_end_date' => '2027-06-23',
                'is_active' => true,
                'notes' => 'Semester 2 Tahun Ajaran 2026/2027.',
            ]
        );
    }
}
