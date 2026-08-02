<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Sedang Dalam Perbaikan - SMK Negeri 1 Skillance</title>
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
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen items-center justify-center text-center px-4">

    <div class="max-w-lg">
        <div class="text-school-yellow mb-6">
            <i class="fas fa-tools text-9xl"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-school-blue mb-4">Website Sedang Dalam Perbaikan</h1>
        <p class="text-gray-600 mb-8 leading-relaxed">
            Mohon maaf atas ketidaknyamanan ini. Kami sedang melakukan pemeliharaan sistem untuk meningkatkan layanan. Website akan segera kembali normal.
        </p>
        <div class="flex justify-center gap-4">
            <a href="#" class="bg-school-blue text-white px-6 py-3 rounded font-bold hover:bg-blue-800 transition">
                <i class="fas fa-sync-alt mr-2"></i> Coba Refresh
            </a>
            <a href="mailto:admin@smkskillance.sch.id" class="border-2 border-school-blue text-school-blue px-6 py-3 rounded font-bold hover:bg-school-blue hover:text-white transition">
                Hubungi Admin
            </a>
        </div>
        <p class="mt-12 text-xs text-gray-500">&copy; 2026 SMK Negeri 1 Skillance. All Rights Reserved.</p>
    </div>

</body>
</html>
