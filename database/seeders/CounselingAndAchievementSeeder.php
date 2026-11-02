<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\CounselingCategory;
use App\Models\Student;
use App\Models\StudentCounselingRecord;
use Illuminate\Database\Seeder;

class CounselingAndAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Pelanggaran
            ['name' => 'Terlambat Masuk Sekolah', 'type' => 'violation', 'point' => 5, 'description' => 'Tiba di sekolah setelah bel masuk berbunyi.'],
            ['name' => 'Atribut Seragam Tidak Lengkap', 'type' => 'violation', 'point' => 5, 'description' => 'Tidak memakai dasi, topi upacara, kaos kaki, atau sabuk sesuai ketentuan.'],
            ['name' => 'Tidak Mengerjakan Tugas / PR', 'type' => 'violation', 'point' => 5, 'description' => 'Tidak menyelesaikan pekerjaan rumah atau tugas proyek yang ditentukan.'],
            ['name' => 'Rambut Tidak Rapi / Pewarnaan', 'type' => 'violation', 'point' => 5, 'description' => 'Rambut gondrong melebihi kerah bagi putra atau diwarnai.'],
            ['name' => 'Meninggalkan Kelas / Bolos Pelajaran', 'type' => 'violation', 'point' => 15, 'description' => 'Meninggalkan ruang kelas tanpa izin saat jam belajar berlangsung.'],
            ['name' => 'Menggunakan HP Tanpa Izin Guru', 'type' => 'violation', 'point' => 10, 'description' => 'Bermain gawai di saat KBM berlangsung tanpa instruksi guru.'],
            ['name' => 'Merusak Fasilitas / Sarana Sekolah', 'type' => 'violation', 'point' => 25, 'description' => 'Mencoret meja/dinding, merusak kaca, kursi, atau properti sekolah.'],
            ['name' => 'Merokok di Lingkungan Sekolah', 'type' => 'violation', 'point' => 50, 'description' => 'Membawa atau menghisap rokok/vape di lingkungan sekolah dan sekitarnya.'],
            ['name' => 'Terlibat Perkelahian', 'type' => 'violation', 'point' => 75, 'description' => 'Terlibat kontak fisik/pertikaian antar siswa di dalam atau luar sekolah.'],

            // Prestasi & Karakter Positif
            ['name' => 'Juara Kelas / Prestasi Belajar', 'type' => 'achievement', 'point' => 15, 'description' => 'Meraih peringkat terbaik dalam evaluasi akademik kelas.'],
            ['name' => 'Juara Lomba Tingkat Sekolah / Antar Kelas', 'type' => 'achievement', 'point' => 10, 'description' => 'Memenangkan perlombaan / class meeting di sekolah.'],
            ['name' => 'Juara Lomba Tingkat Kecamatan / Kabupaten', 'type' => 'achievement', 'point' => 25, 'description' => 'Meraih juara dalam ajang resmi tingkat kecamatan atau kabupaten.'],
            ['name' => 'Juara Lomba Tingkat Provinsi / Nasional', 'type' => 'achievement', 'point' => 50, 'description' => 'Meraih medali / kejuaraan di level provinsi maupun nasional.'],
            ['name' => 'Pengurus OSIS / Petugas Upacara Aktif', 'type' => 'achievement', 'point' => 10, 'description' => 'Berdedikasi dalam organisasi siswa atau tim upacara bendera.'],
            ['name' => 'Aksi Kejujuran & Kepedulian Sosial', 'type' => 'achievement', 'point' => 15, 'description' => 'Mengembalikan barang temuan penting atau aktif kegiatan bakti sosial.'],
        ];

        foreach ($categories as $cat) {
            CounselingCategory::firstOrCreate(
                ['name' => $cat['name']],
                $cat
            );
        }

        // Sample initial achievements for the school
        $firstStudent = Student::first();

        $achievements = [
            [
                'title' => 'Juara 1 Olimpiade Sains Nasional (OSN) IPA',
                'student_name' => $firstStudent ? $firstStudent->student_name : 'Ahmad Fauzi Pratama',
                'student_id' => $firstStudent?->id,
                'category' => 'akademik',
                'level' => 'kabupaten',
                'rank' => 'Juara 1',
                'event_name' => 'Olimpiade Sains Nasional Tingkat SMP se-Kabupaten',
                'event_date' => now()->subMonths(2)->format('Y-m-d'),
                'description' => 'Berhasil meraih medali emas pada kompetisi bidang IPA Terpadu setelah melalui seleksi ketat.',
                'is_featured' => true,
            ],
            [
                'title' => 'Juara 2 FLS2N Tari Tradisional Kreasi',
                'student_name' => 'Tim Tari SMP 02',
                'student_id' => null,
                'category' => 'seni_budaya',
                'level' => 'provinsi',
                'rank' => 'Juara 2',
                'event_name' => 'Festival dan Lomba Seni Siswa Nasional (FLS2N)',
                'event_date' => now()->subMonths(4)->format('Y-m-d'),
                'description' => 'Menampilkan karya tari kreasi daerah dengan harmonisasi dan koreografi memukau.',
                'is_featured' => true,
            ],
            [
                'title' => 'Juara 1 Turnamen Futsal Pelajar Pelajar SMP',
                'student_name' => 'Tim Futsal Putra SMP 02',
                'student_id' => null,
                'category' => 'olahraga',
                'level' => 'kabupaten',
                'rank' => 'Juara 1',
                'event_name' => 'Piala Dispora Pelajar SMP Tingkat Kabupaten',
                'event_date' => now()->subMonths(1)->format('Y-m-d'),
                'description' => 'Meraih gelar juara umum dan trofi bergilir turnamen futsal tahunan.',
                'is_featured' => false,
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::firstOrCreate(
                ['title' => $ach['title']],
                $ach
            );
        }

        // Add a sample counseling record if a student exists
        if ($firstStudent) {
            $catAch = CounselingCategory::where('name', 'Juara Lomba Tingkat Kecamatan / Kabupaten')->first();
            if ($catAch) {
                StudentCounselingRecord::firstOrCreate(
                    [
                        'student_id' => $firstStudent->id,
                        'title' => 'Juara 1 OSN IPA Kabupaten',
                    ],
                    [
                        'counseling_category_id' => $catAch->id,
                        'type' => 'achievement',
                        'point' => 25,
                        'incident_date' => now()->subMonths(2)->format('Y-m-d'),
                        'description' => 'Mewakili sekolah dan meraih peringkat 1 OSN IPA.',
                        'action_taken' => 'Diberikan piagam penghargaan saat upacara bendera hari Senin.',
                        'status' => 'resolved',
                        'reported_by' => 'Guru Pembina OSN',
                    ]
                );
            }
        }
    }
}
