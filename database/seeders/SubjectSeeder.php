<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'code' => 'MTK',
                'name' => 'Matematika',
                'description' => 'Mata pelajaran Matematika.',
                'is_active' => true,
            ],
            [
                'code' => 'BIN',
                'name' => 'Bahasa Indonesia',
                'description' => 'Mata pelajaran Bahasa Indonesia.',
                'is_active' => true,
            ],
            [
                'code' => 'BIG',
                'name' => 'Bahasa Inggris',
                'description' => 'Mata pelajaran Bahasa Inggris.',
                'is_active' => true,
            ],
            [
                'code' => 'RPL',
                'name' => 'Rekayasa Perangkat Lunak',
                'description' => 'Mata pelajaran kejuruan Rekayasa Perangkat Lunak.',
                'is_active' => true,
            ],
            [
                'code' => 'PKN',
                'name' => 'Pendidikan Pancasila',
                'description' => 'Mata pelajaran Pendidikan Pancasila.',
                'is_active' => true,
            ],
            [
                'code' => 'PAI',
                'name' => 'Pendidikan Agama Islam',
                'description' => 'Mata pelajaran Pendidikan Agama Islam.',
                'is_active' => true,
            ],
            [
                'code' => 'SEJ',
                'name' => 'Sejarah',
                'description' => 'Mata pelajaran Sejarah.',
                'is_active' => true,
            ],
            [
                'code' => 'PJOK',
                'name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'description' => 'Mata pelajaran Pendidikan Jasmani, Olahraga, dan Kesehatan.',
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
}
