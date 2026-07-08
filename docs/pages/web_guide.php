<div class="doc-section">
    <h1>Panduan Web Dashboard</h1>
    <p>Bagian ini menjelaskan cara menggunakan dan mengelola sistem melalui Web Dashboard yang ditujukan untuk Admin, Guru, dan Siswa.</p>

    <h2>1. Instalasi Server</h2>
    <p>Untuk menjalankan web dashboard di server atau lokal (seperti Laragon/XAMPP), ikuti langkah-langkah berikut:</p>
    <ol>
        <li>Clone atau ekstrak file source code ke direktori web server Anda (contoh: <code>htdocs</code> atau <code>www</code>).</li>
        <li>Copy file <code>.env.example</code> menjadi <code>.env</code>.</li>
        <li>Buat database baru di MySQL.</li>
        <li>Sesuaikan konfigurasi database di file <code>.env</code>.</li>
        <li>Jalankan perintah berikut di terminal:</li>
    </ol>
    <div class="code-block">
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
    </div>

    <h2>2. Pengelolaan Akun (Admin)</h2>
    <p>Sebagai Admin, Anda dapat mengelola data master seperti Data Siswa, Data Guru, Kelas, dan Mata Pelajaran melalui menu navigasi di sebelah kiri pada dashboard.</p>
    
    <h2>3. Input Nilai & Absensi (Guru)</h2>
    <p>Guru memiliki hak akses untuk memberikan tugas, menilai tugas, dan mengisi daftar hadir siswa pada kelas yang diampunya.</p>
</div>
