# Task 09: Perbaikan Layout Aplikasi Mobile (Flutter)

> **Ref Brief:** Poin #11  
> **Tingkat Urgensi:** Prioritas 4 (UI/UX Mobile Client)

---

## 1. Masalah & Tujuan
Aplikasi Flutter (`mobile/`) diperuntukkan bagi siswa dan wali murid. Terdapat beberapa isu tampilan:
1. Potensi **RenderFlex overflow** (terpotong saat keyboard aktif pada `login_screen.dart` atau form input).
2. Padding, tata letak kartu, dan grafik (donut chart / sparkline chart) yang perlu diselaraskan agar responsif di berbagai resolusi layar HP.
3. Penataan hierarki visual dan warna tema di `AppTheme`.

---

## 2. Berkas yang Terlibat
- `mobile/lib/theme/app_theme.dart`
- `mobile/lib/screens/login_screen.dart`
- `mobile/lib/screens/home_screen.dart`
- `mobile/lib/screens/parent_home_screen.dart`
- `mobile/lib/screens/attendance_screen.dart`
- `mobile/lib/screens/grade_screen.dart`
- `mobile/lib/screens/schedule_screen.dart`
- `mobile/lib/widgets/` (donut_chart, sparkline_chart, activity_timeline)

---

## 3. Langkah-Langkah Perbaikan

### Langkah 1: Audit Layar dengan `flutter analyze`
Jalankan lint check:
```bash
cd mobile && flutter analyze
```
Identifikasi peringatan sintaks, deprecated widget, atau unbounded height/width.

### Langkah 2: Tangani Isu Overflow pada Form & Login
- Bungkus konten form pada `login_screen.dart` dan screen input lain dengan `SingleChildScrollView(physics: BouncingScrollPhysics(), child: ...)` untuk mencegah *bottom overflow by X pixels* saat keyboard virtual muncul.
- Pastikan semua layar menggunakan `SafeArea` untuk mengakomodasi notch atau punch-hole kamera perangkat Android/iOS modern.

### Langkah 3: Perbaiki Kontras & Grid Dashboard
- Pada `home_screen.dart` dan `parent_home_screen.dart`:
  - Gunakan `GridView.builder` dengan `shrinkWrap: true` dan `physics: NeverScrollableScrollPhysics()` di dalam scroll view, atau ganti dengan layout `Wrap`/`Flex` yang fleksibel.
  - Periksa warna kartu status presensi dan nilai agar kontras teks memenuhi standar aksesibilitas.

### Langkah 4: Pengujian di Berbagai Viewport
Uji tampilan pada berbagai ukuran resolusi:
```bash
cd mobile && flutter test
```

---

## 4. Exit Criteria (Definisi Selesai)
- [ ] Tidak ada exception *RenderFlex overflowed* saat aplikasi dibuka dan keyboard muncul di layar login/input.
- [ ] Layar dashboard siswa dan wali murid tertata rapi di dalam `SafeArea`.
- [ ] Perintah `flutter analyze` menghasilkan *No issues found!*.
- [ ] Test widget `flutter test` lulus tanpa error.
