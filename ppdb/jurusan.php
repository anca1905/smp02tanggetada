<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kompetensi Keahlian - SMK Negeri 1 Skillance</title>
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

    <div class="bg-school-blue text-white py-10 text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 uppercase">Kompetensi Keahlian</h1>
            <p class="text-gray-300 max-w-2xl mx-auto">
                Pilih jurusan sesuai minat dan bakatmu. Kami mencetak lulusan siap kerja dengan fasilitas praktik standar industri.
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 space-y-16" id="majors-list">
        <!-- Loaded by JS -->
        <p class="text-center">Memuat data jurusan...</p>
    </div>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
