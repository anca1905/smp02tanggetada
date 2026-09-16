# Task 03: Integrasi Absensi Siswa dengan Mata Pelajaran

> **Ref Brief:** Poin #13  
> **Tingkat Urgensi:** Prioritas 2 (Arsitektur Relasi Database & Fitur Presensi Guru)

---

## 1. Masalah & Analisis
Pada modul presensi siswa guru (`Teacher/StudentPresenceController` & model `Attendance`):
1. Tabel `attendances` saat ini hanya mencatat:
   `teacher_id`, `class`, `session_type` (`apel`, `kelas`, `pulang`), `date`, `start_time`, `end_time`, `qr_token`, `qr_expires_at`.
2. Ketika `session_type = 'kelas'`, presensi siswa **tidak terhubung dengan mata pelajaran (`subject_id`)**. Akibatnya:
   - Jika ada dua guru atau dua mapel berbeda di kelas yang sama pada hari yang sama, data sesi akan tumpang tindih atau tidak dapat dibedakan per mapel.
   - Rekap nilai dan rekap kehadiran per mata pelajaran tidak dapat dihitung dengan presisi.
   - Di mobile app siswa/wali murid, siswa tidak bisa melihat riwayat presensi per mata pelajaran tertentu.

---

## 2. Pilihan Desain (Opsi & Rekomendasi)

### Opsi A (Rekomendasi - Lean & Kompatibel):
- Tambahkan kolom **`subject_id`** (`bigint unsigned`, `nullable`, foreign key ke `subjects.id` dengan onDelete cascade/set null) pada tabel `attendances`.
- Kolom bersifat `nullable` agar sesi umum seperti `apel` dan `pulang` tetap bisa berjalan tanpa mapel.
- Pada form input presensi manual guru (`student_presence.blade.php`) dan form generate QR:
  - Tampilkan dropdown "Mata Pelajaran" yang mengambil daftar mapel yang diampu oleh guru tersebut (berdasarkan relasi `schedules` atau `Teacher->subjects`).
  - Dropdown wajib dipilih jika tipe sesi adalah `kelas`.

### Opsi B (Terikat Penuh ke Jadwal / `schedule_id`):
- Menyimpan `schedule_id` langsung ke tabel `attendances`.
- *Kekurangan Opsi B:* Kurang fleksibel jika ada jam tambahan di luar jadwal resmi atau pergantian jadwal darurat.
- *Kesimpulan:* **Opsi A jauh lebih fleksibel dan minim breaking changes.**

---

## 3. Berkas yang Terlibat
- **Database:**
  - Migrasi baru: `database/migrations/YYYY_MM_DD_add_subject_id_to_attendances_table.php`
- **Model:**
  - `app/Models/Attendance.php` (tambahkan `$fillable` dan `public function subject()`)
- **Actions & Requests:**
  - `app/Http/Requests/Teacher/StoreStudentAttendanceRequest.php`
  - `app/Http/Requests/Teacher/GenerateQrRequest.php`
  - `app/Actions/Teacher/StudentPresence/GetStudentPresenceDataAction.php`
  - `app/Actions/Teacher/StudentPresence/StoreStudentAttendanceAction.php`
  - `app/Actions/Teacher/StudentPresence/GenerateQrSessionAction.php`
- **Views & UI:**
  - `resources/views/student_presence.blade.php` (tambahkan filter/pilihan mata pelajaran)
- **API & Mobile Response:**
  - `app/Http/Controllers/Api/StudentApiController.php` (sertakan nama mapel pada riwayat presensi siswa)
- **Pengujian:**
  - `tests/Feature/Teacher/StudentPresenceSubjectTest.php`

---

## 4. Langkah-Langkah Implementasi

### Langkah 1: Buat Migrasi Penambahan `subject_id`
```bash
php artisan make:migration add_subject_id_to_attendances_table --no-interaction
```
Tambahkan:
```php
$table->foreignId('subject_id')->nullable()->after('class')->constrained('subjects')->nullOnDelete();
```

### Langkah 2: Update Model `Attendance`
- Tambahkan `'subject_id'` ke properti `$fillable`.
- Tambahkan relasi:
```php
public function subject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Subject::class, 'subject_id');
}
```

### Langkah 3: Update Validasi Request & Form Presensi Guru
- Di `StoreStudentAttendanceRequest` dan `GenerateQrRequest`:
  - Tambahkan rule `'subject_id' => 'nullable|exists:subjects,id'`.
  - Jika `session_type === 'kelas'`, buat `subject_id` wajib diisi (`required_if:session_type,kelas`).
- Di `GetStudentPresenceDataAction`:
  - Kirim data `$subjects` (mata pelajaran yang diampu guru yang sedang login).
- Di Blade view `student_presence.blade.php`:
  - Tambahkan dropdown pilihan Mata Pelajaran pada bagian filter / header form.

### Langkah 4: Sesuaikan Query Pencarian Header Presensi
- Di method `scanList()`, `closeSession()`, atau pencarian record sesi:
  - Sertakan `subject_id` dalam kondisi pencarian `Attendance::where(...)` jika tipenya `kelas`.

---

## 5. Exit Criteria (Definisi Selesai)
- [x] Kolom `subject_id` tersimpan di tabel `attendances` saat guru membuat sesi presensi kelas.
- [x] Sesi apel/pulang tetap berjalan normal dengan `subject_id = null`.
- [x] Guru dapat memilih mata pelajaran di form presensi dashboard guru.
- [x] Data presensi di API siswa (`/api/student/attendances`) menyertakan informasi nama mata pelajaran.
- [x] Test otomatis `php artisan test --filter=StudentPresence` lulus 100%.
