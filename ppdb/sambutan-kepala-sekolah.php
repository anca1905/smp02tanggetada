<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sambutan Kepala Sekolah - SMK Negeri 1 Skillance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'school-blue': '#003366', 'school-yellow': '#FFCC00' },
                    fontFamily: { sans: ['Open Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- PHP Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="bg-school-blue text-white py-12 text-center">
        <h1 class="text-3xl font-bold mb-2 uppercase">Sambutan Kepala Sekolah</h1>
        <p class="text-sm text-gray-300">SMK Negeri 1 Skillance</p>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row gap-12 items-start">
            
            <!-- Foto Kepala Sekolah -->
            <div class="w-full md:w-1/3 sticky top-24">
                <div class="bg-white p-2 shadow-lg rounded-lg border-b-4 border-school-yellow">
                    <img src="https://ui-avatars.com/api/?name=Drs.+H.+Supriyadi&size=600&background=random" 
                         alt="Drs. H. Supriyadi, M.Pd" class="w-full rounded">
                    <div class="text-center py-4">
                        <h4 class="font-bold text-xl text-school-blue">Drs. H. Supriyadi, M.Pd</h4>
                        <p class="text-gray-500 text-sm font-semibold">Kepala Sekolah</p>
                    </div>
                </div>
            </div>

            <!-- Isi Sambutan -->
            <div class="w-full md:w-2/3 bg-white p-8 rounded shadow-sm">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-school-yellow pl-4">Assalamualaikum Warahmatullahi Wabarakatuh</h2>
                
                <div class="prose max-w-none text-gray-600 leading-relaxed text-justify space-y-4">
                    <p>
                        Puji syukur kita panjatkan ke hadirat Allah SWT, Tuhan Yang Maha Esa, atas segala limpahan rahmat dan karunia-Nya, sehingga kita masih diberi kesempatan untuk terus berkarya dan mengabdi di dunia pendidikan.
                    </p>
                    <p>
                        Selamat datang di website resmi SMK Negeri 1 Skillance. Website ini kami hadirkan sebagai media informasi dan komunikasi yang efektif antara sekolah, orang tua, siswa, alumni, dan masyarakat luas. Di era digitalisasi industri 4.0 saat ini, keterbukaan informasi dan kecepatan akses menjadi kunci utama dalam pelayanan publik.
                    </p>
                    <p>
                        SMK Negeri 1 Skillance sebagai salah satu Sekolah Pusat Keunggulan (SMK PK) terus berkomitmen untuk meningkatkan mutu pendidikan vokasi. Kami berupaya mencetak lulusan yang tidak hanya cerdas secara intelektual dan kompeten dalam keterampilan teknis (hard skills), tetapi juga memiliki karakter yang kuat (soft skills) sesuai dengan Profil Pelajar Pancasila.
                    </p>
                    <p>
                        Kami menyadari bahwa tantangan dunia kerja ke depan semakin kompleks. Oleh karena itu, kurikulum kami diselaraskan dengan kebutuhan Dunia Usaha dan Dunia Industri (DUDI). Pembelajaran berbasis projek (Project Based Learning) dan Teaching Factory (TEFA) menjadi fokus utama kami untuk memastikan siswa mendapatkan pengalaman nyata layaknya di industri.
                    </p>
                    <p>
                        Kepada seluruh peserta didik, manfaatkanlah masa-masa sekolah ini untuk menggali potensi diri, mengasah keterampilan, dan membangun jejaring. Jadilah generasi muda yang inovatif, kreatif, dan berakhlak mulia.
                    </p>
                    <p>
                        Akhir kata, kami mengucapkan terima kasih atas dukungan dan kepercayaan masyarakat kepada SMK Negeri 1 Skillance. Mari kita bersinergi memajukan pendidikan vokasi untuk Indonesia yang lebih maju.
                    </p>
                    <p>
                        <strong>SMK Bisa! SMK Hebat! Vokasi Kuat, Menguatkan Indonesia!</strong>
                    </p>
                    <p class="mt-8">
                        Wassalamu'alaikum Warahmatullahi Wabarakatuh.
                    </p>
                </div>

                <div class="mt-8 border-t pt-6">
                     <a href="index.php" class="inline-flex items-center text-school-blue font-bold hover:text-school-yellow transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
