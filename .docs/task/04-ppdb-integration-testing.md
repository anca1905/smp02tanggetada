# Task 04: Pengujian & Integrasi Alur PPDB

> **Ref Brief:** Poin #1  
> **Tingkat Urgensi:** Prioritas 2 (Verifikasi End-to-End Pendaftaran Siswa Baru)

---

## 1. Tujuan
Memastikan seluruh alur PPDB (Penerimaan Peserta Didik Baru) terintegrasi tanpa cacat antara:
1. Formulir publik frontend (`/ppdb/daftar`).
2. Validasi form request dan penyimpanan database (`ppdb` table).
3. Penyimpanan berkas lampiran (ijazah, KK, akta, pas foto, dll.).
4. Dashboard Admin TU (`/tu/ppdb`): listing, filter status, update status verifikasi (Pending, Diterima, Ditolak), dan cetak/export bukti.

---

## 2. Titik Uji Kunci
1. **Status Buka/Tutup PPDB:** Jika pengaturan `ppdb_open` di tabel `settings` bernilai false, pengiriman formulir wajib ditolak dengan pesan yang sesuai.
2. **Validasi Formulir:**
   - NISN & NIK wajib valid (format angka).
   - Dokumen wajib memiliki ekstensi yang diizinkan (PDF, JPG, PNG) dan batasan ukuran (misal maks 2MB).
3. **Penyimpanan Data & Auto Numbering:**
   - Nomor registrasi ter-generate dengan format unik `REG-YYYY-XXXX`.
   - Data biodata calon siswa, data orang tua, dan path dokumen tersimpan di tabel `ppdb`.
4. **Sinkronisasi Admin TU:**
   - Data langsung muncul di tabel pendaftar `/tu/ppdb`.
   - Admin TU dapat mengubah status pendaftaran dan status berkas.

---

## 3. Berkas yang Terlibat
- `app/Http/Controllers/PublicController.php` (`ppdbForm`, `storePpdb`)
- `app/Http/Requests/Public/StorePpdbRegistrationRequest.php`
- `app/Actions/Public/ProcessPpdbRegistrationAction.php`
- `app/Http/Controllers/AdminTu/PpdbController.php`
- `app/Models/Ppdb.php`
- `tests/Feature/Public/PpdbRegistrationTest.php`
- `tests/Feature/AdminTu/PpdbManagementTest.php`

---

## 4. Langkah-Langkah Pengerjaan

### Langkah 1: Audit Form Request & Model
- Periksa `StorePpdbRegistrationRequest.php`: pastikan semua rule validasi mencerminkan kolom di tabel `ppdb`.
- Pastikan atribut dokumen bertipe `file|mimes:pdf,jpg,jpeg,png|max:2048`.

### Langkah 2: Tulis Comprehensive Feature Test
Buat file test `tests/Feature/Public/PpdbRegistrationTest.php`:
- Test 1: Halaman form pendaftaran dapat diakses saat PPDB aktif.
- Test 2: Pendaftaran ditolak saat PPDB ditutup.
- Test 3: Validasi gagal jika field wajib kosong (nama, nisn, nik, dll.).
- Test 4: Upload file terverifikasi berhasil tersimpan di storage menggunakan fake disk (`Storage::fake('public')`).
- Test 5: Nomor registrasi otomatis bertambah dan unik.

Buat file test `tests/Feature/AdminTu/PpdbManagementTest.php`:
- Test 1: Admin TU dapat melihat daftar pendaftar dengan pagination & filter status.
- Test 2: Admin TU dapat mengubah status pendaftar menjadi "Diterima" / "Ditolak".
- Test 3: Admin TU dapat menghapus data pendaftar beserta berkasnya dari storage.

### Langkah 3: Eksekusi & Perbaikan Bug
Jalankan test dan perbaiki bila ditemukan mismatch field atau unhandled null value.

---

## 5. Exit Criteria (Definisi Selesai)
- [x] Formulir `/ppdb/daftar` berhasil disubmit dengan data dummy dan file upload valid.
- [x] Record masuk ke tabel `ppdb` dengan nomor registrasi unik.
- [x] Data pendaftar tampil lengkap di `/tu/ppdb` dan status verifikasi dapat diubah oleh Admin TU.
- [x] Test otomatis `php artisan test --filter=Ppdb` lulus 100% tanpa error.
