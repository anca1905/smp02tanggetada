# Task 02: Perbaikan Akses File PDF Peserta PPDB di Admin TU

> **Ref Brief:** Poin #8  
> **Tingkat Urgensi:** Prioritas 1 (Bug Akses Dokumen Peserta)

---

## 1. Masalah & Penyebab
Di halaman rincian pendaftar PPDB Admin TU (`resources/views/tu/ppdb/show.blade.php`), link dokumen peserta menggunakan:
```blade
<a href="{{ Storage::url($ppdb->{$doc['field']}) }}" target="_blank">
```
Kendala yang terjadi:
1. Default filesystem di konfigurasi aplikasi adalah `'local'`, sedangkan file di-upload ke disk `'public'` (`storage/app/public/ppdb_documents/...`). Memanggil `Storage::url()` tanpa spesifikasi disk menyebabkan path resolusi keliru atau file 404.
2. Membuka file langsung melalui path public storage rawan bocor ke publik tanpa otentikasi, padahal dokumen memuat data privat (KTP, KK, Akta Kelahiran).

---

## 2. Solusi (Lean & Aman)
Sediakan rute terproteksi khusus Operator/TU untuk melihat/mengunduh dokumen PPDB secara inline (browser PDF viewer):
- **Route:** `GET /tu/ppdb/{ppdb}/document/{field}` dengan middleware `auth:operator`.
- **Controller:** `App\Http\Controllers\AdminTu\PpdbController@showDocument`
- Logika: Validasi nama field (hanya boleh `doc_ijazah`, `doc_transkrip`, `doc_tka`, `doc_akta`, `doc_kk`, `doc_ktp_ayah`, `doc_ktp_ibu`, `doc_pas_photo`). Periksa keberadaan file di disk `public` (fallback ke `local` jika ada data lama). Kembalikan response stream dengan header `Content-Type: application/pdf` (atau mime type gambar jika pas photo).

---

## 3. Berkas yang Terlibat
- `app/Http/Controllers/AdminTu/PpdbController.php`
- `routes/web.php`
- `resources/views/tu/ppdb/show.blade.php`
- `tests/Feature/AdminTu/PpdbDocumentAccessTest.php`

---

## 4. Langkah-Langkah Implementasi

### Langkah 1: Tulis Test Akses Dokumen (TDD)
- Test guest / siswa mengakses dokumen PPDB: Mendapat response redirect ke login atau 403 Forbidden.
- Test Admin TU yang login mengakses dokumen valid: Mendapat response HTTP 200 dengan header `Content-Disposition: inline` dan `Content-Type: application/pdf`.
- Test request field yang tidak diizinkan atau file fiktif: Mengembalikan 404 Not Found.

### Langkah 2: Tambahkan Method di `PpdbController`
Di `app/Http/Controllers/AdminTu/PpdbController.php`:
```php
public function showDocument(Ppdb $ppdb, string $field): \Symfony\Component\HttpFoundation\Response
{
    $allowedFields = [
        'doc_ijazah', 'doc_transkrip', 'doc_tka', 'doc_akta', 
        'doc_kk', 'doc_ktp_ayah', 'doc_ktp_ibu', 'doc_pas_photo'
    ];

    abort_unless(in_array($field, $allowedFields), 404);

    $filePath = $ppdb->{$field};
    abort_if(empty($filePath), 404);

    $disk = Storage::disk('public')->exists($filePath) ? 'public' : (Storage::disk('local')->exists($filePath) ? 'local' : null);
    abort_unless($disk, 404, 'Berkas tidak ditemukan pada server.');

    return Storage::disk($disk)->response($filePath);
}
```

### Langkah 3: Daftarkan Route di `routes/web.php`
Di dalam grup middleware `auth:operator`:
```php
Route::get('/ppdb/{ppdb}/document/{field}', [PpdbController::class, 'showDocument'])->name('tu.ppdb.document');
```

### Langkah 4: Perbarui View `show.blade.php`
Ganti:
```blade
<a href="{{ Storage::url($ppdb->{$doc['field']}) }}" target="_blank" ...>
```
Menjadi:
```blade
<a href="{{ route('tu.ppdb.document', [$ppdb->id, $doc['field']]) }}" target="_blank" ...>
```

---

## 5. Exit Criteria (Definisi Selesai)
- [x] Admin TU berhasil membuka dan melihat file PDF (Ijazah, KK, KTP, dsb.) langsung di browser tab baru tanpa error 404.
- [x] Akses URL dokumen diblokir jika diakses oleh user non-operator/guest.
- [x] Test `php artisan test --filter=PpdbDocumentAccessTest` lulus 100%.
