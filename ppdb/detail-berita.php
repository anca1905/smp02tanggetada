<?php
require_once 'config/database.php';
include 'helpers/get_settings.php'; // Needed for header/footer

$id = $_GET['id'] ?? null;
$berita = null;

if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
        $stmt->execute([$id]);
        $berita = $stmt->fetch();

        // Update view count (optional)
        // $pdo->prepare("UPDATE berita SET dilihat = dilihat + 1 WHERE id = ?")->execute([$id]);
    } catch (Exception $e) {
        // Handle error silently
    }
}

if (!$berita) {
    header("Location: berita.php");
    exit;
}

// Format date
$date = date('d F Y', strtotime($berita['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($berita['judul']) ?> - <?= $settings['nama_sekolah'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'school-blue': '#003366',
                        'school-yellow': '#FFCC00'
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- PHP Header -->
    <?php include 'layouts/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <p class="text-sm text-gray-500 mb-4"><a href="index.php" class="hover:text-school-blue">Beranda</a> / <a
                href="berita.php" class="hover:text-school-blue">Berita</a> / <span class="text-gray-700"><?= htmlspecialchars($berita['judul']) ?></span></p>

        <div class="flex flex-col lg:flex-row gap-10">

            <!-- Main Content -->
            <div class="lg:w-2/3 bg-white p-8 rounded shadow-sm">
                <span class="bg-school-yellow text-school-blue px-3 py-1 rounded text-xs font-bold uppercase mb-4 inline-block"><?= htmlspecialchars($berita['kategori']) ?></span>
                <h1 class="text-3xl font-bold text-gray-800 mb-4 leading-tight"><?= htmlspecialchars($berita['judul']) ?></h1>

                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 border-b pb-4">
                    <span><i class="far fa-calendar mr-1"></i> <?= $date ?></span>
                    <span><i class="far fa-user mr-1"></i> <?= htmlspecialchars($berita['penulis']) ?></span>
                    <span><i class="far fa-eye mr-1"></i> <?= $berita['dilihat'] ?? 0 ?> Dilihat</span>
                </div>

                <?php if (!empty($berita['gambar'])): ?>
                    <img src="<?= $berita['gambar'] ?>" alt="<?= htmlspecialchars($berita['judul']) ?>" class="w-full h-auto rounded mb-6" onerror="this.style.display='none'">
                <?php endif; ?>

                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    <!-- Rich Text Content -->
                    <?= $berita['isi'] ?>
                </div>

                <div class="mt-8 pt-6 border-t flex justify-between items-center">
                    <span class="font-bold text-sm text-gray-600">Bagikan:</span>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:opacity-80"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($berita['judul']) ?>&url=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="w-8 h-8 bg-blue-400 text-white rounded-full flex items-center justify-center hover:opacity-80"><i class="fab fa-twitter"></i></a>
                        <a href="whatsapp://send?text=<?= urlencode($berita['judul'] . " " . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center hover:opacity-80"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3 space-y-8">

                <div class="bg-white p-6 rounded shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Berita Terbaru</h4>
                    <div id="sidebar-news-list">
                        <!-- Loaded by JS via app.js reuse or simple PHP loop here -->
                        <?php
                        $stmts = $pdo->query("SELECT * FROM berita WHERE status = 'Published' AND id != $id ORDER BY tanggal DESC LIMIT 5");
                        while ($row = $stmts->fetch()):
                        ?>
                            <div class="flex gap-4 group cursor-pointer mb-4">
                                <img src="<?= $row['gambar'] ?>" class="w-20 h-16 object-cover rounded" alt="Thumbnail" onerror="this.src='assets/img/news-placeholder.jpg'">
                                <div>
                                    <h5 class="font-bold text-sm group-hover:text-school-blue leading-tight mb-1">
                                        <a href="detail-berita.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['judul']) ?></a>
                                    </h5>
                                    <span class="text-xs text-gray-500"><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="bg-white p-6 rounded shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Kategori</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="#" class="px-3 py-1 bg-gray-100 text-xs font-semibold rounded hover:bg-school-blue hover:text-white transition">Kegiatan</a>
                        <a href="#" class="px-3 py-1 bg-gray-100 text-xs font-semibold rounded hover:bg-school-blue hover:text-white transition">Prestasi</a>
                        <a href="#" class="px-3 py-1 bg-gray-100 text-xs font-semibold rounded hover:bg-school-blue hover:text-white transition">Pengumuman</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
</body>

</html>