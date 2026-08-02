<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sekolah - SMK Negeri 1 Skillance</title>
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
        <h1 class="text-3xl font-bold mb-2 uppercase">Profil Sekolah</h1>
        <p class="text-sm text-gray-300">Mengenal lebih dekat SMK Negeri 1 Skillance</p>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row gap-12">
            <!-- Sidebar / Foto -->
            <div class="md:w-1/3">
                <img src="https://images.unsplash.com/photo-1596495577886-d920f1fb7238" alt="Gedung Sekolah" class="w-full rounded shadow-lg mb-6">
                
                <div class="bg-white p-6 rounded shadow-sm">
                    <h3 class="font-bold text-school-blue mb-4 border-b pb-2">Identitas Sekolah</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex justify-between"><span>NPSN:</span> <span class="font-bold">12345678</span></li>
                        <li class="flex justify-between"><span>Status:</span> <span class="font-bold">Negeri</span></li>
                        <li class="flex justify-between"><span>Akreditasi:</span> <span class="font-bold">A (Unggul)</span></li>
                        <li class="flex justify-between"><span>Berdiri:</span> <span class="font-bold">2005</span></li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="md:w-2/3 space-y-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-school-yellow pl-4">Sejarah Singkat</h3>
                    <p class="text-gray-600 leading-relaxed text-justify mb-4">
                        SMK Negeri 1 Skillance didirikan pada tahun 2005 sebagai respon terhadap kebutuhan tenaga kerja terampil di bidang teknologi informasi dan komunikasi di wilayah ini. Berawal dari 2 jurusan dan 100 siswa, kini kami telah berkembang menjadi sekolah pusat keunggulan dengan 5 kompetensi keahlian dan lebih dari 1500 siswa.
                    </p>
                    <p class="text-gray-600 leading-relaxed text-justify">
                        Selama hampir dua dekade, kami terus berkomitmen untuk mencetak lulusan yang tidak hanya kompeten secara teknis, tetapi juga memiliki karakter yang kuat, berakhlak mulia, dan siap bersaing di era global.
                    </p>
                </div>

                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-school-yellow pl-4">Visi & Misi</h3>
                    
                    <div class="mb-4">
                        <h4 class="font-bold text-school-blue mb-2">Visi</h4>
                        <p class="italic text-gray-600 border-l-2 border-gray-300 pl-4 py-2 bg-gray-50">
                            "Menjadi Sekolah Menengah Kejuruan yang Unggul, Berkarakter, dan Berwawasan Lingkungan serta Mampu Bersaing di Tingkat Global pada Tahun 2030."
                        </p>
                    </div>

                    <div>
                        <h4 class="font-bold text-school-blue mb-2">Misi</h4>
                        <ol class="list-decimal list-inside text-gray-600 space-y-2 ml-2">
                            <li>Menyelenggarakan pendidikan yang berkualitas dan relevan dengan kebutuhan industri.</li>
                            <li>Membentuk peserta didik yang beriman, bertaqwa, dan berakhlak mulia.</li>
                            <li>Mengembangkan potensi peserta didik melalui kegiatan intrakurikuler dan ekstrakurikuler.</li>
                            <li>Mewujudkan sekolah yang ramah lingkungan dan kondusif untuk belajar.</li>
                            <li>Meningkatkan kerjasama dengan Dunia Usaha dan Dunia Industri (DUDI).</li>
                        </ol>
                    </div>
                </div>

                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-school-yellow pl-4">Struktur Organisasi</h3>
                    <img src="https://via.placeholder.com/800x400?text=Bagan+Struktur+Organisasi" alt="Struktur Organisasi" class="w-full rounded border border-gray-200">
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
