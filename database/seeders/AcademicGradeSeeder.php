<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use App\Models\AcademicGrade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AcademicGradeSeeder extends Seeder
{
    public function run(): void
    {
        $calendar = AcademicCalendar::where('academic_year', '2026/2027')
            ->where('semester', 1)
            ->first();

        if (! $calendar) {
            return;
        }

        $grades = [
            [
                'nis' => '10001',
                'subjects' => [
                    'MTK' => [87, 'Sangat baik dalam memahami materi matematika.'],
                    'BIN' => [82, 'Mampu memahami dan menyampaikan materi dengan baik.'],
                    'RPL' => [90, 'Sangat baik dalam praktik dan pemahaman pemrograman.'],
                ],
            ],
            [
                'nis' => '10002',
                'subjects' => [
                    'MTK' => [78, 'Perlu meningkatkan pemahaman pada beberapa materi.'],
                    'BIN' => [85, 'Aktif dalam pembelajaran dan memiliki kemampuan yang baik.'],
                    'RPL' => [88, 'Baik dalam praktik dan penyelesaian tugas.'],
                ],
            ],
            [
                'nis' => '10003',
                'subjects' => [
                    'MTK' => [91, 'Sangat baik dan konsisten dalam pembelajaran.'],
                    'BIN' => [89, 'Memiliki kemampuan analisis dan komunikasi yang baik.'],
                ],
            ],
            [
                'nis' => '10004',
                'subjects' => [
                    'MTK' => [80, 'Mampu mengikuti pembelajaran dengan baik.'],
                    'BIN' => [84, 'Baik dalam memahami materi.'],
                ],
            ],
        ];

        foreach ($grades as $studentGrade) {
            $student = Student::where('nis', $studentGrade['nis'])->first();

            if (! $student) {
                continue;
            }

            foreach ($studentGrade['subjects'] as $subjectCode => $data) {
                $subject = Subject::where('code', $subjectCode)->first();

                if (! $subject) {
                    continue;
                }

                $score = (float) $data[0];

                $grade = match (true) {
                    $score >= 90 => 'A',
                    $score >= 80 => 'B',
                    $score >= 70 => 'C',
                    default => 'D',
                };

                AcademicGrade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'academic_calendar_id' => $calendar->id,
                    ],
                    [
                        'score' => $score,
                        'grade' => $grade,
                        'notes' => $data[1],
                    ]
                );
            }
        }
    }
}
