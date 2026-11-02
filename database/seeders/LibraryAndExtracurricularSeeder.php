<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LibraryAndExtracurricularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Library Books
        $books = [
            [
                'book_code' => 'BOS-7-MAT',
                'title' => 'Matematika SMP/MTs Kelas VII (Kurikulum Merdeka)',
                'author' => 'Tim Kemendikbudristek',
                'publisher' => 'Pusat Perbukuan Kemendikbudristek',
                'publish_year' => '2023',
                'isbn' => '978-602-244-882-2',
                'category' => 'buku_paket_bos',
                'class_level' => '7',
                'total_stock' => 120,
                'available_stock' => 120,
                'location_shelf' => 'Rak BOS 7A',
                'description' => 'Buku teks utama mata pelajaran Matematika untuk siswa kelas 7 SMP negeri maupun swasta.',
            ],
            [
                'book_code' => 'BOS-7-IPA',
                'title' => 'Ilmu Pengetahuan Alam (IPA) SMP Kelas VII',
                'author' => 'Victoriani Inabuy, dkk.',
                'publisher' => 'Kementerian Pendidikan dan Kebudayaan',
                'publish_year' => '2023',
                'isbn' => '978-602-244-383-4',
                'category' => 'buku_paket_bos',
                'class_level' => '7',
                'total_stock' => 120,
                'available_stock' => 120,
                'location_shelf' => 'Rak BOS 7B',
                'description' => 'Buku paket BOS IPA Terpadu Kelas VII kurikulum sekolah penggerak dan merdeka.',
            ],
            [
                'book_code' => 'BOS-7-BIN',
                'title' => 'Bahasa Indonesia: Bergerak Bersama Kelas VII',
                'author' => 'Rakhma Subarna, dkk.',
                'publisher' => 'Pusat Kurikulum dan Perbukuan',
                'publish_year' => '2022',
                'isbn' => '978-602-244-644-6',
                'category' => 'buku_paket_bos',
                'class_level' => '7',
                'total_stock' => 100,
                'available_stock' => 100,
                'location_shelf' => 'Rak BOS 7C',
                'description' => 'Buku teks pengajaran Bahasa Indonesia kurikulum merdeka.',
            ],
            [
                'book_code' => 'BOS-8-MAT',
                'title' => 'Matematika SMP/MTs Kelas VIII',
                'author' => 'Tim Puskurbuk Kemendikbudristek',
                'publisher' => 'Pusat Perbukuan',
                'publish_year' => '2023',
                'isbn' => '978-602-244-900-3',
                'category' => 'buku_paket_bos',
                'class_level' => '8',
                'total_stock' => 110,
                'available_stock' => 110,
                'location_shelf' => 'Rak BOS 8A',
                'description' => 'Buku paket matematika siswa kelas 8 SMP.',
            ],
            [
                'book_code' => 'BOS-9-IPA',
                'title' => 'Ilmu Pengetahuan Alam Kelas IX Semester 1 & 2',
                'author' => 'Siti Zubaidah, dkk.',
                'publisher' => 'Kemendikbud RI',
                'publish_year' => '2022',
                'isbn' => '978-602-244-789-4',
                'category' => 'buku_paket_bos',
                'class_level' => '9',
                'total_stock' => 90,
                'available_stock' => 90,
                'location_shelf' => 'Rak BOS 9B',
                'description' => 'Buku paket BOS IPA Kelas IX persiapan asesmen dan kelulusan.',
            ],
            [
                'book_code' => 'LIT-001',
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publish_year' => '2005',
                'isbn' => '978-979-3062-79-2',
                'category' => 'fiksi',
                'class_level' => 'semua',
                'total_stock' => 15,
                'available_stock' => 15,
                'location_shelf' => 'Rak Sastra 01',
                'description' => 'Kisah inspiratif tentang perjuangan anak-anak Belitong dalam menuntut ilmu.',
            ],
            [
                'book_code' => 'REF-001',
                'title' => 'Kamus Besar Bahasa Indonesia (KBBI) Edisi V',
                'author' => 'Badan Pengembangan dan Pembinaan Bahasa',
                'publisher' => 'Balai Pustaka',
                'publish_year' => '2016',
                'isbn' => '978-979-407-182-3',
                'category' => 'referensi',
                'class_level' => 'semua',
                'total_stock' => 5,
                'available_stock' => 5,
                'location_shelf' => 'Meja Referensi',
                'description' => 'Kamus rujukan baku bahasa Indonesia untuk pembelajaran di perpustakaan.',
            ],
        ];

        foreach ($books as $b) {
            LibraryBook::updateOrCreate(['book_code' => $b['book_code']], $b);
        }

        // 2. Seed Extracurriculars
        $ekskuls = [
            [
                'name' => 'Gerakan Pramuka (Gugus Depan SMPN 2 Tanggetada)',
                'category' => 'wajib',
                'instructor_name' => 'Syarifuddin, S.Pd.',
                'schedule_day' => 'Jumat, 15:30 - 17:30 WITA',
                'location' => 'Lapangan Utama Sekolah',
                'description' => 'Kegiatan kepanduan wajib untuk melatih kedisiplinan, kemandirian, kepemimpinan, dan kecintaan pada alam.',
                'is_active' => true,
            ],
            [
                'name' => 'PMR Madya (Palang Merah Remaja)',
                'category' => 'pilihan',
                'instructor_name' => 'Hj. Nurhayati, S.Kep.',
                'schedule_day' => 'Sabtu, 08:00 - 10:00 WITA',
                'location' => 'Ruang UKS & Halaman',
                'description' => 'Wadah pembinaan kepalangmerahan, pertolongan pertama pada kecelakaan (P3K), donor darah remaja, dan peduli sesama.',
                'is_active' => true,
            ],
            [
                'name' => 'Paskibra Sekolah',
                'category' => 'pilihan',
                'instructor_name' => 'Bripka Andi (Pelatih Kodim/Polsek) & Pembina',
                'schedule_day' => 'Selasa & Kamis, 16:00 - 17:30 WITA',
                'location' => 'Lapangan Upacara',
                'description' => 'Pasukan Pengibar Bendera Pusaka untuk upacara bendera hari Senin dan peringatan HUT Proklamasi Kemerdekaan RI.',
                'is_active' => true,
            ],
            [
                'name' => 'Futsal & Sepak Bola Pelajar',
                'category' => 'olahraga',
                'instructor_name' => 'Rahmat Hidayat, S.Pd. (Guru PJOK)',
                'schedule_day' => 'Rabu, 16:00 - 18:00 WITA',
                'location' => 'Lapangan Olahraga SMPN 2 Tanggetada',
                'description' => 'Pembinaan bakat olahraga sepak bola dan futsal guna menyongsong kejuaraan O2SN tingkat kabupaten dan provinsi.',
                'is_active' => true,
            ],
            [
                'name' => 'Seni Tari Tradisional Tolaki & Nusantara',
                'category' => 'seni_budaya',
                'instructor_name' => 'Hasriani, S.Sn.',
                'schedule_day' => 'Kamis, 15:30 - 17:00 WITA',
                'location' => 'Panggung Seni / Aula Terbuka',
                'description' => 'Melestarikan kearifan tari lulo, tari mondotambe, dan tari kreasi nusantara untuk ajang FLS2N.',
                'is_active' => true,
            ],
            [
                'name' => 'Rohis & Tahfidz Al-Qur\'an',
                'category' => 'keagamaan',
                'instructor_name' => 'Ust. Muh. Arfah, S.Ag.',
                'schedule_day' => 'Jumat, 13:30 - 15:00 WITA',
                'location' => 'Musholla At-Tarbiyah SMPN 2 Tanggetada',
                'description' => 'Bimbingan tilawah, hafalan juz 30, serta pembinaan akhlak mulia dan kepribadian Islami pelajar.',
                'is_active' => true,
            ],
            [
                'name' => 'Klub Sains & OSN Matematika-IPA',
                'category' => 'akademik',
                'instructor_name' => 'Dra. Mardiana',
                'schedule_day' => 'Senin, 15:00 - 16:30 WITA',
                'location' => 'Laboratorium IPA',
                'description' => 'Bimbingan intensif persiapan Olimpiade Sains Nasional (OSN) tingkat Kabupaten Kolaka dan Provinsi Sulawesi Tenggara.',
                'is_active' => true,
            ],
        ];

        foreach ($ekskuls as $ek) {
            Extracurricular::updateOrCreate(['name' => $ek['name']], $ek);
        }

        // 3. Sample sample loan & registration if student exists
        $student = Student::first();
        if ($student) {
            $book = LibraryBook::first();
            if ($book && $book->available_stock > 0) {
                LibraryLoan::updateOrCreate(
                    ['loan_code' => 'PINJAM-2026-0001'],
                    [
                        'book_id' => $book->id,
                        'student_id' => $student->id,
                        'loan_date' => Carbon::now()->subDays(5),
                        'due_date' => Carbon::now()->addDays(9),
                        'status' => 'borrowed',
                        'fine_amount' => 0,
                        'officer_name' => 'Petugas Perpustakaan TU',
                        'notes' => 'Peminjaman buku paket BOS semester ganjil.',
                    ]
                );
                // Decrement stock
                $book->decrement('available_stock');
            }

            $ekskul = Extracurricular::where('category', 'olahraga')->first() ?? Extracurricular::first();
            if ($ekskul) {
                ExtracurricularRegistration::updateOrCreate(
                    [
                        'extracurricular_id' => $ekskul->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'student_name' => $student->student_name,
                        'classroom_name' => $student->classroom ? $student->classroom->name : 'Kelas VII-A',
                        'phone' => $student->phone_number ?? '081234567890',
                        'reason' => 'Ingin mengembangkan bakat dan ikut seleksi O2SN Kabupaten.',
                        'status' => 'accepted',
                        'notes' => 'Telah memenuhi syarat dan mendapat izin orang tua.',
                    ]
                );
            }
        }
    }
}
