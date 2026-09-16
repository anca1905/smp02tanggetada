<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyContentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. SETUP AKUN KEPALA SEKOLAH (PRINCIPAL) & OPERATOR TAMBAHAN
        DB::table('operators')->insertOrIgnore([
            [
                'name' => 'Dr. H. Hendra Saputra, M.Pd.',
                'username' => 'kepsek',
                'password' => Hash::make('password'),
                'role' => 'SMA',
                'role_operator' => 'Kepala Sekolah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah (Staf TU)',
                'username' => 'operator2',
                'password' => Hash::make('password'),
                'role' => 'SMA',
                'role_operator' => 'Tata Usaha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. SETUP DATA ORANG TUA UNTUK SEMUA SISWA (UNTUK LOGIN PARENT DI MOBILE APP)
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            DB::table('students')
                ->where('id', $student->id)
                ->update([
                    'parent_name' => 'Bpk/Ibu '.$student->student_name,
                    'parent_phone' => '0821'.rand(10000000, 99999999),
                    'parent_password' => Hash::make('ortu'.$student->nis), // password = ortu + NIS (misal: ortu120001)
                ]);
        }

        // Ambil ID dari seeder sebelumnya
        $teacherId = DB::table('teachers')->where('username', 'guru')->value('id');
        $scheduleId = DB::table('schedules')->first()->id ?? 1;
        $assignmentId = DB::table('assignments')->first()->id ?? 1;
        $subjectId = DB::table('subjects')->first()->id ?? 1;
        $attendanceId = DB::table('attendances')->first()->id ?? 1;

        // 3. SETUP MATERIpELAJARAN (LMS)
        if ($scheduleId) {
            DB::table('materials')->insert([
                [
                    'schedule_id' => $scheduleId,
                    'title' => 'Pengantar Aljabar Linear & Matriks',
                    'description' => 'Materi dasar mengenai operasi matriks dan vektor pada Aljabar Linear.',
                    'type' => 'pdf',
                    'file_path' => 'materials/sample-aljabar.pdf',
                    'file_name' => 'sample-aljabar.pdf',
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(5),
                ],
                [
                    'schedule_id' => $scheduleId,
                    'title' => 'Video Pembelajaran: Determinans & Invers',
                    'description' => 'Tonton penjelasan video berikut sebelum mengerjakan latihan soal di kelas.',
                    'type' => 'youtube',
                    'file_path' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'file_name' => null,
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ],
                [
                    'schedule_id' => $scheduleId,
                    'title' => 'Referensi Kalkulator Matriks Online',
                    'description' => 'Tautan tools online untuk memeriksa perhitungan determinan dan invers kalian.',
                    'type' => 'link',
                    'file_path' => 'https://matrixcalc.org',
                    'file_name' => null,
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subDays(1),
                ],
            ]);
        }

        // 4. SETUP ASSIGNMENT SUBMISSIONS (PENGUMPULAN TUGAS OLEH SISWA)
        if ($assignmentId && $students->count() >= 15) {
            foreach ($students->take(15) as $index => $student) {
                $isGraded = $index < 10; // 10 pertama sudah dinilai guru, sisanya belum
                DB::table('assignment_submissions')->insert([
                    'assignment_id' => $assignmentId,
                    'student_id' => $student->id,
                    'file_path' => 'submissions/tugas_aljabar_'.$student->nis.'.pdf',
                    'student_note' => 'Berikut tugas LKS saya Pak, mohon dikoreksi. Terima kasih.',
                    'score' => $isGraded ? rand(75, 98) : null,
                    'teacher_feedback' => $isGraded ? 'Pengerjaan sangat rapi dan jawabannya tepat!' : null,
                    'submitted_at' => now()->subHours(rand(2, 48)),
                    'created_at' => now()->subHours(rand(2, 48)),
                    'updated_at' => now(),
                ]);
            }
        }

        // 5. SETUP NILAI (GRADES) & ABSENSI SISWA LAINNYA
        $statuses = ['present', 'present', 'present', 'sick', 'permission', 'present'];
        $attendanceDetails = [];
        $grades = [];

        foreach ($students->slice(1) as $student) { // Skip siswa 1 karena sudah ada di InitialDataSeeder
            // Absensi untuk kelas
            if ($attendanceId) {
                $attendanceDetails[] = [
                    'attendance_id' => $attendanceId,
                    'student_id' => $student->id,
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Nilai UTS dan Tugas untuk siswa
            if ($subjectId) {
                $grades[] = [
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                    'type' => 'UTS',
                    'score' => rand(70, 95),
                    'description' => 'Nilai UTS Semester Ganjil',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $grades[] = [
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                    'type' => 'Tugas',
                    'score' => rand(75, 98),
                    'description' => 'Tugas Harian 1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($attendanceDetails)) {
            DB::table('student_attendance_details')->insert($attendanceDetails);
        }
        if (! empty($grades)) {
            DB::table('student_grades')->insert($grades);
        }

        // 6. SETUP TAGIHAN KEUANGAN (BILLS) UNTUK SISWA
        $bills = [];
        foreach ($students->take(20) as $index => $student) {
            // Tagihan SPP Juli (Lunas)
            $bills[] = [
                'student_id' => $student->id,
                'title' => 'SPP Bulan Juli 2026',
                'type' => 'SPP Bulanan',
                'amount' => 350000.00,
                'due_date' => '2026-07-10',
                'status' => 'paid',
                'paid_at' => Carbon::parse('2026-07-05 10:30:00'),
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subMonths(1),
            ];

            // Tagihan SPP Agustus (Sebagian lunas, sebagian belum)
            $isPaid = $index % 2 === 0;
            $bills[] = [
                'student_id' => $student->id,
                'title' => 'SPP Bulan Agustus 2026',
                'type' => 'SPP Bulanan',
                'amount' => 350000.00,
                'due_date' => '2026-08-10',
                'status' => $isPaid ? 'paid' : 'unpaid',
                'paid_at' => $isPaid ? Carbon::parse('2026-08-02 14:15:00') : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Uang Kegiatan / Ekstrakurikuler
            $bills[] = [
                'student_id' => $student->id,
                'title' => 'Iuran Kegiatan Kunjungan Industri & LKS',
                'type' => 'Uang Kegiatan',
                'amount' => 150000.00,
                'due_date' => '2026-08-25',
                'status' => 'unpaid',
                'paid_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('bills')->insert($bills);

        // 7. SETUP DAFTAR RUANGAN (ROOMS)
        $rooms = [
            ['room_name' => 'Lab Komputer 1', 'location' => 'Lantai 2 - Gedung Barat', 'description' => 'Fasilitas 35 PC Spesifikasi Tinggi & Proyektor interactive.'],
            ['room_name' => 'Aula Utama (Grand Hall)', 'location' => 'Lantai 1 - Gedung Pusat', 'description' => 'Kapasitas 500 kursi dengan panggung dan sound system.'],
            ['room_name' => 'Ruang Audio Visual & Rapat', 'location' => 'Lantai 1 - Gedung Timur', 'description' => 'Untuk seminar kecil, presentasi guru, dan pertemuan dewan.'],
            ['room_name' => 'Lapangan Basket Indoor', 'location' => 'Gedung Olahraga', 'description' => 'Lapangan olahraga serbaguna beratap dengan tribun penonton.'],
        ];
        foreach ($rooms as $room) {
            DB::table('rooms')->insert([
                'room_name' => $room['room_name'],
                'location' => $room['location'],
                'description' => $room['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 8. SETUP PEMINJAMAN RUANGAN (ROOM BORROWINGS)
        $borrowings = [
            [
                'full_name' => 'Randi Pratama (Ketua OSIS)',
                'nis' => '120005',
                'class' => 'XII IPA 1',
                'phone_number' => '08123456001',
                'room_type' => 'Aula Utama (Grand Hall)',
                'borrow_date' => now()->addDays(3)->format('Y-m-d'),
                'activity_description' => 'Persiapan acara Pensi dan Latihan Gabungan OSIS.',
                'start_time' => '13:30:00',
                'end_time' => '16:30:00',
                'responsible_person' => 'Budi Santoso, S.Pd',
                'status' => 'upcoming',
            ],
            [
                'full_name' => 'Kelompok Karya Ilmiah Remaja (KIR)',
                'nis' => '120012',
                'class' => 'XII IPA 1',
                'phone_number' => '08123456002',
                'room_type' => 'Lab Komputer 1',
                'borrow_date' => now()->format('Y-m-d'),
                'activity_description' => 'Analisis data lomba pemrograman tingkat provinsi.',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'responsible_person' => 'Siti Aminah (Staf TU)',
                'status' => 'ongoing',
            ],
            [
                'full_name' => 'Tim Basket Sekolah',
                'nis' => '120020',
                'class' => 'XII IPA 1',
                'phone_number' => '08123456003',
                'room_type' => 'Lapangan Basket Indoor',
                'borrow_date' => now()->subDays(2)->format('Y-m-d'),
                'activity_description' => 'Pertandingan persahabatan antar kelas (Class Meeting).',
                'start_time' => '15:00:00',
                'end_time' => '17:30:00',
                'responsible_person' => 'Dr. H. Hendra Saputra, M.Pd.',
                'status' => 'completed',
            ],
        ];
        foreach ($borrowings as $b) {
            $b['created_at'] = now();
            $b['updated_at'] = now();
            DB::table('room_borrowings')->insert($b);
        }

        // 9. SETUP PENDAFTARAN SISWA BARU (PPDB)
        $ppdbStatuses = ['Pending', 'Accepted', 'Rejected'];
        $berkasStatuses = ['Belum Dicek', 'Lengkap', 'Tidak Lengkap'];
        $jurusans = ['IPA', 'IPS', 'Bahasa'];

        for ($i = 1; $i <= 15; $i++) {
            $gender = $i % 2 === 0 ? 'P' : 'L';
            $name = $faker->name($gender == 'L' ? 'male' : 'female');

            DB::table('ppdb')->insert([
                'no_registrasi' => 'PPDB-2026-'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_lengkap' => $name,
                'nisn' => '00'.rand(10000000, 99999999),
                'nik' => '3201'.rand(100000000000, 999999999999),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2010-12-31'),
                'jenis_kelamin' => $gender,
                'alamat' => $faker->address,
                'asal_sekolah' => 'SMP Negeri '.rand(1, 10).' '.$faker->city,
                'tahun_lulus' => 2026,
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'no_hp' => '08'.rand(1111111111, 9999999999),
                'status_pendaftaran' => $ppdbStatuses[array_rand($ppdbStatuses)],
                'status_berkas' => $berkasStatuses[array_rand($berkasStatuses)],
                'doc_kk' => 'ppdb/kk_sample.pdf',
                'doc_ijazah' => 'ppdb/ijazah_sample.pdf',
                'doc_akta' => 'ppdb/akta_sample.pdf',
                'tanggal_daftar' => now()->subDays(rand(1, 14)),
            ]);
        }

        // 10. SETUP CMS PORTAL PUBLIK (POSTS, EVENTS, FACILITIES, MESSAGES)
        // Posts (Berita)
        $posts = [
            [
                'title' => 'Prestasi Membanggakan: Tim SIMS Juara 1 Lomba Inovasi Digital Tingkat Nasional 2026',
                'slug' => 'tim-sims-juara-1-lomba-inovasi-digital-2026',
                'category' => 'Prestasi',
                'content' => 'Siswa-siswi SMA SIMS kembali menorehkan prestasi luar biasa di panggung nasional. Dalam ajang Kompetisi Inovasi Digital Antar Sekolah yang diadakan awal pekan ini, tim perwakilan kita sukses meraih medali emas melalui proyek sistem manajemen kelas terintegrasi.',
                'image' => null,
                'is_published' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'Pembukaan Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Resmi Diluncurkan',
                'slug' => 'pembukaan-ppdb-2026-2027',
                'category' => 'Pengumuman',
                'content' => 'Kabar gembira bagi calon peserta didik dan orang tua! Sekolah resmi membuka portal pendaftaran siswa baru secara daring (online). Proses seleksi, unggah berkas, dan pemantauan pengumuman dapat dilakukan dari rumah secara terpadu melalui platform PPDB kami.',
                'image' => null,
                'is_published' => true,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'title' => 'Kegiatan Kuliah Tamu & Pembekalan Karier Bersama Para Alumni Sukses',
                'slug' => 'kuliah-tamu-dan-pembekalan-karier-alumni',
                'category' => 'Kegiatan',
                'content' => 'Mengawali semester ganjil, pihak sekolah menyelenggarakan sesi berbagi (sharing session) dan pengenalan dunia kerja bersama ikatan alumni SMA SIMS yang saat ini telah berkarir di perusahaan teknologi tingkat global.',
                'image' => null,
                'is_published' => true,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];
        DB::table('posts')->insert($posts);

        // Events
        $events = [
            [
                'title' => 'Ujian Tengah Semester (UTS) Ganjil',
                'description' => 'Pelaksanaan evaluasi pembelajaran UTS semester ganjil berbasis komputer untuk seluruh tingkatan kelas.',
                'start_date' => now()->addDays(7)->format('Y-m-d'),
                'end_date' => now()->addDays(14)->format('Y-m-d'),
                'type' => 'academic',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pentas Seni & Festival Kreatif Sekolah (SIMS Fest 2026)',
                'description' => 'Ajang pertunjukan minat talent dari ekstrakurikuler musik, tari, teater, dan bazar pameran karya siswa.',
                'start_date' => now()->addDays(20)->format('Y-m-d'),
                'end_date' => now()->addDays(21)->format('Y-m-d'),
                'type' => 'event',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Rapport Day (Pembagian Hasil Evaluasi Belajar)',
                'description' => 'Pertemuan wali kelas dengan orang tua / wali siswa untuk penyerahan rapor tengah semester.',
                'start_date' => now()->addDays(25)->format('Y-m-d'),
                'end_date' => now()->addDays(25)->format('Y-m-d'),
                'type' => 'academic',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('events')->insert($events);

        // Facilities (Fasilitas di Landing Page)
        $facilities = [
            ['title' => 'Gedung Laboratorium Sains Terpadu', 'image_path' => 'facilities/sample_lab.jpg'],
            ['title' => 'Perpustakaan Digital & Ruang Baca Modern', 'image_path' => 'facilities/sample_library.jpg'],
            ['title' => 'Lapangan Olahraga & Outdoor Sport Center', 'image_path' => 'facilities/sample_sport.jpg'],
            ['title' => 'Smart Classroom Ekosistem Interaktif', 'image_path' => 'facilities/sample_class.jpg'],
        ];
        foreach ($facilities as $facility) {
            DB::table('facilities')->insert([
                'title' => $facility['title'],
                'image_path' => $facility['image_path'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Messages (Inbox Pesan Kontak Web)
        $messages = [
            [
                'name' => 'Bapak Kusuma (Calon Orang Tua Siswa)',
                'email' => 'kusuma@gmail.com',
                'subject' => 'Pertanyaan Syarat Berkas PPDB Jalur Prestasi',
                'message' => 'Selamat siang, saya ingin bertnaya apakah sertifikat juara renang tingkat kota dapat dilampirkan sebagai dokumen pendukung untuk seleksi PPDB jalur prestasi olahraga? Terima kasih.',
                'is_read' => false,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name' => 'Anisa (Alumni 2022)',
                'email' => 'anisa.alumni@yahoo.com',
                'subject' => 'Permohonan Legalisir Ijazah Online',
                'message' => 'Halo Bapak/Ibu TU. Saya membutuhkan salinan legalisir ijazah untuk keperluan pendaftaran beasiswa pasca sarjana. Apakah sekolah melayani pengajuan legalisir jarak jauh/dikirim melalui kurir?',
                'is_read' => true,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name' => 'PT Solusi Edukasi Digital',
                'email' => 'kemitraan@solusiedu.co.id',
                'subject' => 'Undangan Kemitraan Program Pelatihan Guru Modern',
                'message' => 'Dengan hormat, bermaksud mengundang jajaran perwakilan Guru dan Kepala Sekolah SMA SIMS dalam workshop gratis pemanfaaatan kecerdasan buatan dalam pengelolaan kurikulum dan LMS.',
                'is_read' => false,
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ],
        ];
        DB::table('messages')->insert($messages);
    }
}
