# Task 06: Fitur Upload & Pembaruan Foto Siswa di Admin TU

> **Ref Brief:** Poin #7  
> **Tingkat Urgensi:** Prioritas 3 (Kelengkapan Data Siswa & Kartu Pelajar)

---

## 1. Masalah & Analisis
1. Di tabel `students`, belum ada kolom untuk menyimpan berkas foto profil siswa (saat ini kartu pelajar di `tu.student_card` masih menggunakan fallback avatar `ui-avatars.com`).
2. Admin TU di menu Data Siswa (`/tu/students`):
   - Modal tambah siswa belum menyediakan input file foto.
   - Modal edit siswa belum menyediakan opsi upload/ganti foto.
   - Action `CreateStudentAction` dan `UpdateStudentAction` belum memproses file upload.

---

## 2. Solusi Teknis
1. Buat migration baru untuk menambahkan kolom `photo_url` (`string`, `nullable`) pada tabel `students`.
2. Tambahkan atribut `photo_url` pada `$fillable` model `Student.php`.
3. Perbarui `StoreStudentRequest` dan `UpdateStudentRequest` untuk menerima `photo_url` bertipe `nullable|image|mimes:jpeg,png,jpg|max:2048`.
4. Update `CreateStudentAction` dan `UpdateStudentAction`:
   - Simpan file foto ke `storage/app/public/students/photos`.
   - Hapus foto lama jika ada pembaruan foto baru.
5. Perbarui modal Blade di `resources/views/tu/student_data.blade.php`:
   - Tambahkan `enctype="multipart/form-data"` pada tag form.
   - Tambahkan preview gambar dan input file `<input type="file" name="photo_url" accept="image/*">`.
6. Perbarui `resources/views/tu/student_card.blade.php`:
   - Tampilkan foto asli siswa jika `photo_url` tersedia (fallback ke `ui-avatars` jika null).

---

## 3. Berkas yang Terlibat
- Migrasi: `database/migrations/YYYY_MM_DD_add_photo_url_to_students_table.php`
- Model: `app/Models/Student.php`
- Form Request:
  - `app/Http/Requests/Student/StoreStudentRequest.php`
  - `app/Http/Requests/Student/UpdateStudentRequest.php`
- Actions:
  - `app/Actions/Student/CreateStudentAction.php`
  - `app/Actions/Student/UpdateStudentAction.php`
- Controller: `app/Http/Controllers/AdminTu/StudentController.php`
- Views:
  - `resources/views/tu/student_data.blade.php`
  - `resources/views/tu/student_card.blade.php`
- Pengujian: `tests/Feature/AdminTu/StudentPhotoUploadTest.php`

---

## 4. Langkah-Langkah Implementasi

### Langkah 1: Migrasi Database
```bash
php artisan make:migration add_photo_url_to_students_table --no-interaction
```
Tambahkan:
```php
$table->string('photo_url')->nullable()->after('student_status');
```

### Langkah 2: TDD Test Upload Foto Siswa
Buat test di `tests/Feature/AdminTu/StudentPhotoUploadTest.php`:
- Test 1: Admin TU membuat siswa baru dengan menyertakan file foto (`UploadedFile::fake()->image('foto.jpg')`) -> file tersimpan di disk public dan path tercatat di DB.
- Test 2: Admin TU memperbarui foto siswa -> foto lama terhapus dari storage, foto baru tersimpan.
- Test 3: Validasi menolak file non-gambar atau ukuran melebihi 2MB.

### Langkah 3: Update Request & Actions
- Tambahkan validasi foto di `StoreStudentRequest` dan `UpdateStudentRequest`.
- Di `CreateStudentAction` dan `UpdateStudentAction`, gunakan `Storage::disk('public')` untuk menyimpan file dan menghapus file lama saat diganti.

### Langkah 4: Update Tampilan Blade & Kartu Pelajar
- Form modal di `tu/student_data.blade.php` diberi input file & preview.
- Pada `tu/student_card.blade.php`:
```blade
<img src="{{ $student->photo_url ? asset('storage/' . $student->photo_url) : 'https://ui-avatars.com/api/?name='.urlencode($student->student_name) }}" alt="Foto Siswa" class="student-photo">
```

---

## 5. Exit Criteria (Definisi Selesai)
- [x] Kolom `photo_url` terdaftar di database `students`.
- [x] Admin TU sukses mengunggah foto siswa baru dan memperbarui foto siswa lama.
- [x] Foto siswa tampil di tabel Data Siswa TU dan kartu pelajar yang dicetak.
- [x] File foto lama otomatis terhapus saat diperbarui untuk menghemat kapasitas storage.
- [x] Test otomatis `php artisan test --filter=StudentPhotoUploadTest` lulus 100%.
