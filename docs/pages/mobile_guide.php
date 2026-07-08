<div class="doc-section">
    <h1>Panduan Mobile App (Android)</h1>
    <p>Aplikasi mobile dibuat menggunakan framework Flutter. Panduan ini akan membantu Anda mengonfigurasi aplikasi agar terhubung dengan Web Dashboard Anda.</p>

    <h2>1. Persyaratan Sistem</h2>
    <ul>
        <li>Flutter SDK (versi terbaru)</li>
        <li>Android Studio / Visual Studio Code</li>
        <li>Koneksi internet untuk mendownload *dependencies*.</li>
    </ul>

    <h2>2. Konfigurasi API (Base URL)</h2>
    <p>Agar aplikasi Android bisa mengambil data dari server web Anda, Anda harus mengubah konfigurasi Base URL. Buka file <code>lib/utils/constants.dart</code> atau file konfigurasi API Anda, lalu ubah URL-nya:</p>
    <div class="code-block">
// Ubah alamat ini sesuai dengan domain web dashboard Anda
const String API_BASE_URL = "https://domain-anda.com/api";
    </div>

    <h2>3. Re-Branding Aplikasi</h2>
    <p>Anda bisa mengubah nama dan logo aplikasi agar sesuai dengan instansi pembeli:</p>
    <ul>
        <li><strong>Ubah Nama Aplikasi:</strong> Buka <code>android/app/src/main/AndroidManifest.xml</code> dan ubah nilai pada <code>android:label="Nama Aplikasi"</code>.</li>
        <li><strong>Ubah Logo:</strong> Gunakan package <code>flutter_launcher_icons</code> atau ubah secara manual di folder <code>android/app/src/main/res/</code>.</li>
    </ul>

    <h2>4. Build APK / AAB</h2>
    <p>Untuk menghasilkan file instalasi Android (.apk) atau file untuk Play Store (.aab), jalankan perintah berikut di terminal (pastikan berada di folder aplikasi mobile):</p>
    <div class="code-block">
# Untuk membuat file APK
flutter build apk --release

# Untuk membuat file App Bundle (Play Store)
flutter build appbundle --release
    </div>
</div>
