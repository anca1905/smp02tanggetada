# Task 05: Validasi & Pengujian Absensi Siswa Menggunakan ID Card

> **Ref Brief:** Poin #2  
> **Tingkat Urgensi:** Prioritas 2 (Fungsionalitas Presensi ID Card / Barcode)

---

## 1. Masalah & Temuan Analisis
Dari penelusuran arsitektur kartu pelajar dan kiosk absensi:
1. **Barcode Kartu:** Dihasilkan menggunakan library JS `JsBarcode` dengan input `NIS` (`format: CODE128`) pada view `resources/views/tu/student_card.blade.php`.
2. **Kiosk Scan:** Endpoint `POST /presensi/scan` menerima input barcode `nis`, `session_type`, `class`, `date`.
3. **Bug Kritis Model & Schema:**
   Pada `app/Models/StudentAttendance.php`:
   - Properti `$primaryKey` diset `'student_attendance_detail_id'`, sedangkan di skema database nama kolomnya adalah `'id'`.
   - Relasi `$this->belongsTo(Student::class, 'nis', 'nis')` keliru karena kolom di tabel `student_attendance_details` adalah `student_id` (foreign key ke `students.id`), bukan `nis`. Hal ini menyebabkan relasi `$a->student` menghasilkan `null` saat presensi divalidasi.

---

## 2. Berkas yang Terlibat
- `app/Models/StudentAttendance.php`
- `app/Models/StudentAttendanceDetail.php` (sinkronisasi kedua model/alias)
- `app/Actions/Teacher/StudentPresence/ScanBarcodeAction.php`
- `app/Http/Controllers/Teacher/StudentPresenceController.php`
- `resources/views/tu/student_card.blade.php`
- `resources/views/presence.blade.php` (halaman kiosk scanner)
- `tests/Feature/Teacher/StudentIdCardAttendanceTest.php`

---

## 3. Langkah-Langkah Implementasi

### Langkah 1: Perbaiki Relasi & Primary Key pada Model
Di `app/Models/StudentAttendance.php`:
```php
protected $primaryKey = 'id';

public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Student::class, 'student_id');
}
```

### Langkah 2: Verifikasi Alur Scan Barcode
Di `ScanBarcodeAction.php`:
- Pastikan pencarian siswa via `$student = Student::where('nis', $nis)->first()` berfungsi saat diinput manual maupun melalui USB barcode scanner (yang mengirim karakter NIS + Enter).
- Pastikan pengecekan duplikasi presensi pada sesi yang sama bekerja (mencegah double-tap).
- Record disimpan ke `StudentAttendance` dengan `student_id = $student->id` dan status `present`.

### Langkah 3: Pengujian Cetak Barcode pada ID Card
Di `resources/views/tu/student_card.blade.php`:
- Pastikan SVG `#barcode` ter-render sempurna tanpa pemotongan margin print.
- Pastikan nilai barcode tepat sama dengan nilai string `$student->nis`.

### Langkah 4: Buat Feature Test
Buat test di `tests/Feature/Teacher/StudentIdCardAttendanceTest.php`:
- Test 1: Scan barcode dengan NIS valid -> mengembalikan respon JSON success, nama siswa, dan status `present` tersimpan.
- Test 2: Scan barcode NIS yang sama dua kali -> respon gagal "sudah absen di sesi ini".
- Test 3: Scan barcode dengan NIS siswa kelas lain -> respon ditolak (bukan bagian dari kelas).
- Test 4: Scan barcode dengan NIS fiktif -> respon 404/not found.

---

## 4. Exit Criteria (Definisi Selesai)
- [ ] Model `StudentAttendance` sinkron dengan tabel `student_attendance_details` (primary key `id` dan foreign key `student_id`).
- [ ] Barcode pada kartu pelajar terbaca dengan scanner dan berhasil memicu absensi `present`.
- [ ] Endpoint `/presensi/scan` mengembalikan data JSON nama siswa yang benar (bukan null/error).
- [ ] Test otomatis `php artisan test --filter=StudentIdCardAttendanceTest` lulus 100%.
