<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = SchoolClass::where('academic_year', '2026/2027')
            ->get()
            ->keyBy('name');

        $students = [
            [
                'nis' => '10001',
                'nisn' => '0061234501',
                'full_name' => 'Ahmad Rizky',
                'gender' => 'L',
                'birth_date' => '2010-05-12',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567890',
                'class' => 'X RPL 1',
            ],
            [
                'nis' => '10002',
                'nisn' => '0061234502',
                'full_name' => 'Aulia Putri',
                'gender' => 'P',
                'birth_date' => '2010-08-21',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567891',
                'class' => 'X RPL 1',
            ],
            [
                'nis' => '10003',
                'nisn' => '0051234503',
                'full_name' => 'Fajar Ramadhan',
                'gender' => 'L',
                'birth_date' => '2009-03-15',
                'birth_place' => 'Depok',
                'address' => 'Depok',
                'phone' => '081234567892',
                'class' => 'XI RPL 1',
            ],
            [
                'nis' => '10004',
                'nisn' => '0051234504',
                'full_name' => 'Nabila Salsabila',
                'gender' => 'P',
                'birth_date' => '2009-11-03',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567893',
                'class' => 'XI RPL 1',
            ],
            [
                'nis' => '10005',
                'nisn' => '0041234505',
                'full_name' => 'Rizky Maulana',
                'gender' => 'L',
                'birth_date' => '2008-07-19',
                'birth_place' => 'Bekasi',
                'address' => 'Bekasi',
                'phone' => '081234567894',
                'class' => 'XII RPL 1',
            ],
            [
                'nis' => '10006',
                'nisn' => '0041234506',
                'full_name' => 'Siti Aisyah',
                'gender' => 'P',
                'birth_date' => '2008-09-27',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567895',
                'class' => 'XII RPL 1',
            ],
            [
                'nis' => '10007',
                'nisn' => '0061234507',
                'full_name' => 'Bagas Pratama',
                'gender' => 'L',
                'birth_date' => '2010-01-28',
                'birth_place' => 'Tangerang',
                'address' => 'Tangerang',
                'phone' => '081234567896',
                'class' => 'X RPL 2',
            ],
            [
                'nis' => '10008',
                'nisn' => '0061234508',
                'full_name' => 'Citra Lestari',
                'gender' => 'P',
                'birth_date' => '2010-12-10',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567897',
                'class' => 'X RPL 2',
            ],
            [
                'nis' => '10009',
                'nisn' => '0051234509',
                'full_name' => 'Dimas Saputra',
                'gender' => 'L',
                'birth_date' => '2009-06-14',
                'birth_place' => 'Bogor',
                'address' => 'Bogor',
                'phone' => '081234567898',
                'class' => 'XI RPL 2',
            ],
            [
                'nis' => '10010',
                'nisn' => '0051234510',
                'full_name' => 'Intan Permata',
                'gender' => 'P',
                'birth_date' => '2009-10-25',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567899',
                'class' => 'XI RPL 2',
            ],
            [
                'nis' => '10011',
                'nisn' => '0041234511',
                'full_name' => 'Andi Setiawan',
                'gender' => 'L',
                'birth_date' => '2008-04-08',
                'birth_place' => 'Bekasi',
                'address' => 'Bekasi',
                'phone' => '081234567900',
                'class' => 'XII RPL 2',
            ],
            [
                'nis' => '10012',
                'nisn' => '0041234512',
                'full_name' => 'Dewi Anggraini',
                'gender' => 'P',
                'birth_date' => '2008-02-17',
                'birth_place' => 'Jakarta',
                'address' => 'Jakarta',
                'phone' => '081234567901',
                'class' => 'XII RPL 2',
            ],
        ];

        foreach ($students as $student) {
            $schoolClass = $classes->get($student['class']);

            if (! $schoolClass) {
                continue;
            }

            Student::firstOrCreate(
                [
                    'nis' => $student['nis'],
                ],
                [
                    'user_id' => null,
                    'school_class_id' => $schoolClass->id,
                    'nisn' => $student['nisn'],
                    'full_name' => $student['full_name'],
                    'gender' => $student['gender'],
                    'birth_date' => $student['birth_date'],
                    'birth_place' => $student['birth_place'],
                    'address' => $student['address'],
                    'phone' => $student['phone'],
                    'status' => 'active',
                ]
            );
        }
    }
}
