<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$page_path = 'pages/' . $page . '.php';

if (!file_exists($page_path)) {
    $page_path = 'pages/home.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi Sistem Informasi Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Boxicons for icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class='bx bxs-book-reader'></i>
                    <span>SIMS Docs</span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-group">
                    <h3 class="nav-title">Mulai</h3>
                    <a href="?page=home" class="<?= $page == 'home' ? 'active' : '' ?>">
                        <i class='bx bx-home-alt'></i> Pengantar
                    </a>
                </div>
                
                <div class="nav-group">
                    <h3 class="nav-title">Web Dashboard</h3>
                    <a href="?page=web_guide" class="<?= $page == 'web_guide' ? 'active' : '' ?>">
                        <i class='bx bx-globe'></i> Panduan Web
                    </a>
                </div>

                <div class="nav-group">
                    <h3 class="nav-title">Mobile App (Android)</h3>
                    <a href="?page=mobile_guide" class="<?= $page == 'mobile_guide' ? 'active' : '' ?>">
                        <i class='bx bx-mobile-alt'></i> Panduan Mobile
                    </a>
                </div>
            </nav>
            <div class="sidebar-footer">
                <p>Versi 1.0.0</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <div class="search-bar">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Cari dokumentasi...">
                </div>
                <div class="actions">
                    <a href="../" class="btn btn-outline">Lihat Aplikasi</a>
                </div>
            </header>

            <div class="content-wrapper">
                <?php include $page_path; ?>
            </div>
        </main>
    </div>

</body>
</html>
