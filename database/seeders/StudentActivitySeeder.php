<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentActivity;
use Illuminate\Database\Seeder;

class StudentActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'nis' => '10001',
                'activity_date' => '2026-07-20',
                'category' => 'pembelajaran',
                'title' => 'Praktik Dasar Pemrograman',
                'description' => 'Mengikuti praktik pemrograman dasar menggunakan PHP.',
                'notes' => 'Aktif mengikuti praktik dan mampu menyelesaikan tugas dengan baik.',
            ],
            [
                'nis' => '10001',
                'activity_date' => '2026-08-05',
                'category' => 'ekstrakurikuler',
                'title' => 'Latihan Futsal',
                'description' => 'Mengikuti latihan rutin ekstrakurikuler futsal sekolah.',
                'notes' => 'Mengikuti latihan dengan disiplin.',
            ],
            [
                'nis' => '10002',
                'activity_date' => '2026-07-22',
                'category' => 'pembelajaran',
                'title' => 'Praktik Database MySQL',
                'description' => 'Melakukan praktik membuat database dan tabel menggunakan MySQL.',
                'notes' => 'Mampu mengikuti materi dengan baik.',
            ],
            [
                'nis' => '10002',
                'activity_date' => '2026-08-15',
                'category' => 'kegiatan_sekolah',
                'title' => 'Kerja Bakti Lingkungan Sekolah',
                'description' => 'Mengikuti kegiatan kerja bakti membersihkan lingkungan sekolah.',
                'notes' => 'Berpartisipasi aktif selama kegiatan.',
            ],
            [
                'nis' => '10003',
                'activity_date' => '2026-07-25',
                'category' => 'pembelajaran',
                'title' => 'Presentasi Proyek RPL',
                'description' => 'Mempresentasikan hasil proyek aplikasi sederhana di depan kelas.',
                'notes' => 'Presentasi berjalan dengan baik dan mampu menjawab pertanyaan.',
            ],
            [
                'nis' => '10003',
                'activity_date' => '2026-09-02',
                'category' => 'organisasi',
                'title' => 'Rapat OSIS',
                'description' => 'Mengikuti rapat organisasi untuk membahas agenda kegiatan sekolah.',
                'notes' => 'Aktif memberikan pendapat dalam diskusi.',
            ],
            [
                'nis' => '10004',
                'activity_date' => '2026-08-01',
                'category' => 'pembelajaran',
                'title' => 'Praktik Jaringan Komputer',
                'description' => 'Melakukan konfigurasi jaringan dasar dan pengujian konektivitas.',
                'notes' => 'Mampu melakukan konfigurasi dengan arahan guru.',
            ],
            [
                'nis' => '10005',
                'activity_date' => '2026-08-10',
                'category' => 'ekstrakurikuler',
                'title' => 'Latihan Paskibra',
                'description' => 'Mengikuti latihan rutin Paskibra sekolah.',
                'notes' => 'Disiplin dan mengikuti instruksi dengan baik.',
            ],
            [
                'nis' => '10006',
                'activity_date' => '2026-08-20',
                'category' => 'lomba',
                'title' => 'Seleksi Lomba Kompetensi Siswa',
                'description' => 'Mengikuti seleksi internal untuk persiapan LKS bidang IT.',
                'notes' => 'Menunjukkan kemampuan teknis yang baik.',
            ],
            [
                'nis' => '10007',
                'activity_date' => '2026-09-01',
                'category' => 'pembelajaran',
                'title' => 'Praktik Pengembangan Web',
                'description' => 'Membuat halaman web sederhana menggunakan HTML, CSS, dan JavaScript.',
                'notes' => 'Mampu menyelesaikan tugas sesuai instruksi.',
            ],
            [
                'nis' => '10008',
                'activity_date' => '2026-09-05',
                'category' => 'kegiatan_sekolah',
                'title' => 'Seminar Literasi Digital',
                'description' => 'Mengikuti seminar mengenai keamanan dan etika dalam penggunaan teknologi digital.',
                'notes' => 'Mengikuti kegiatan dengan tertib.',
            ],
            [
                'nis' => '10009',
                'activity_date' => '2026-09-10',
                'category' => 'ekstrakurikuler',
                'title' => 'Latihan Basket',
                'description' => 'Mengikuti latihan rutin ekstrakurikuler basket.',
                'notes' => 'Aktif mengikuti seluruh rangkaian latihan.',
            ],
            [
                'nis' => '10010',
                'activity_date' => '2026-09-15',
                'category' => 'organisasi',
                'title' => 'Persiapan Kegiatan Kelas',
                'description' => 'Membantu persiapan kegiatan kelas bersama teman-teman.',
                'notes' => 'Bertanggung jawab terhadap tugas yang diberikan.',
            ],
            [
                'nis' => '10011',
                'activity_date' => '2026-09-20',
                'category' => 'pembelajaran',
                'title' => 'Praktik Algoritma',
                'description' => 'Mengerjakan latihan algoritma dan logika pemrograman.',
                'notes' => 'Perlu meningkatkan ketelitian dalam menyelesaikan soal.',
            ],
            [
                'nis' => '10012',
                'activity_date' => '2026-09-25',
                'category' => 'lainnya',
                'title' => 'Kegiatan Sosial Sekolah',
                'description' => 'Mengikuti kegiatan sosial yang diselenggarakan oleh sekolah.',
                'notes' => 'Berpartisipasi dengan baik selama kegiatan berlangsung.',
            ],
        ];

        foreach ($activities as $activity) {
            $student = Student::where('nis', $activity['nis'])->first();

            if (! $student) {
                continue;
            }

            StudentActivity::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'activity_date' => $activity['activity_date'],
                    'title' => $activity['title'],
                ],
                [
                    'category' => $activity['category'],
                    'description' => $activity['description'],
                    'notes' => $activity['notes'],
                ]
            );
        }
    }
}
