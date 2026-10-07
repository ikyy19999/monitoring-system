<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeachingAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::firstOrCreate(
            [
                'email' => 'budi.guru@test.com',
            ],
            [
                'name' => 'Budi Santoso',
                'password' => 'Guru@123456',
            ]
        );

        $teacher->assignRole('guru mata pelajaran');

        $rpl = Subject::where('code', 'RPL')->first();
        $matematika = Subject::where('code', 'MTK')->first();
        $bahasaIndonesia = Subject::where('code', 'BIN')->first();

        $kelasX1 = SchoolClass::where('name', 'X RPL 1')
            ->where('academic_year', '2026/2027')
            ->first();

        $kelasX2 = SchoolClass::where('name', 'X RPL 2')
            ->where('academic_year', '2026/2027')
            ->first();

        $kelasXI1 = SchoolClass::where('name', 'XI RPL 1')
            ->where('academic_year', '2026/2027')
            ->first();

        if ($rpl && $kelasX1) {
            TeachingAssignment::firstOrCreate([
                'teacher_id' => $teacher->id,
                'subject_id' => $rpl->id,
                'school_class_id' => $kelasX1->id,
            ]);
        }

        if ($rpl && $kelasX2) {
            TeachingAssignment::firstOrCreate([
                'teacher_id' => $teacher->id,
                'subject_id' => $rpl->id,
                'school_class_id' => $kelasX2->id,
            ]);
        }

        if ($matematika && $kelasXI1) {
            TeachingAssignment::firstOrCreate([
                'teacher_id' => $teacher->id,
                'subject_id' => $matematika->id,
                'school_class_id' => $kelasXI1->id,
            ]);
        }

        if ($bahasaIndonesia && $kelasXI1) {
            TeachingAssignment::firstOrCreate([
                'teacher_id' => $teacher->id,
                'subject_id' => $bahasaIndonesia->id,
                'school_class_id' => $kelasXI1->id,
            ]);
        }
    }
}
