<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
if (! isset($settings)) {
    include __DIR__.'/../helpers/get_settings.php';
}
?>
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="assets/img/logo.png" alt="Logo Tut Wuri" class="h-10 md:h-12 w-auto">
                <div class="leading-tight">
                    <h1 class="text-lg md:text-xl font-bold text-school-blue uppercase"><?php echo $settings['nama_sekolah'] ?? 'SMK SKILLANCE'; ?></h1>
                    <p class="text-[10px] md:text-xs text-gray-600 font-semibold tracking-wide"><?php echo $settings['slogan'] ?? 'TERAKREDITASI A (UNGGUL)'; ?>
                    </p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-6 text-sm font-bold text-gray-700 uppercase">
                <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'text-school-blue' : 'hover:text-school-blue'; ?> transition">Beranda</a>
                <a href="profil.php" class="<?php echo $current_page == 'profil.php' ? 'text-school-blue' : 'hover:text-school-blue'; ?> transition">Profil</a>
                <a href="jurusan.php" class="<?php echo $current_page == 'jurusan.php' ? 'text-school-blue' : 'hover:text-school-blue'; ?> transition">Jurusan</a>
                <a href="galeri.php" class="<?php echo $current_page == 'galeri.php' ? 'text-school-blue' : 'hover:text-school-blue'; ?> transition">Galeri</a>
                <a href="berita.php" class="<?php echo $current_page == 'berita.php' ? 'text-school-blue' : 'hover:text-school-blue'; ?> transition">Berita</a>

                <?php if (isset($settings['buka_ppdb']) && $settings['buka_ppdb'] == 1) { ?>
                    <a href="ppdb.php" class="bg-school-blue text-white px-4 py-2 rounded hover:bg-blue-800 transition">PPDB Online</a>
                <?php } ?>
            </nav>

            <button id="mobile-menu-btn" class="md:hidden text-school-blue text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobile-menu"
            class="hidden md:hidden mt-4 pb-4 border-t border-gray-100 flex flex-col space-y-3 pt-4 bg-white animate-fade-in-down">
            <a href="index.php" class="block px-2 <?php echo $current_page == 'index.php' ? 'text-school-blue' : 'text-gray-700'; ?> font-bold uppercase hover:bg-gray-50">Beranda</a>
            <a href="profil.php" class="block px-2 <?php echo $current_page == 'profil.php' ? 'text-school-blue' : 'text-gray-700'; ?> font-bold uppercase hover:text-school-blue hover:bg-gray-50">Profil</a>
            <a href="jurusan.php" class="block px-2 <?php echo $current_page == 'jurusan.php' ? 'text-school-blue' : 'text-gray-700'; ?> font-bold uppercase hover:text-school-blue hover:bg-gray-50">Jurusan</a>
            <a href="galeri.php" class="block px-2 <?php echo $current_page == 'galeri.php' ? 'text-school-blue' : 'text-gray-700'; ?> font-bold uppercase hover:text-school-blue hover:bg-gray-50">Galeri</a>
            <a href="berita.php" class="block px-2 <?php echo $current_page == 'berita.php' ? 'text-school-blue' : 'text-gray-700'; ?> font-bold uppercase hover:text-school-blue hover:bg-gray-50">Berita</a>

            <?php if (isset($settings['buka_ppdb']) && $settings['buka_ppdb'] == 1) { ?>
                <a href="ppdb.php" class="block px-2 py-2 text-center bg-school-blue text-white rounded font-bold uppercase">PPDB Online</a>
            <?php } ?>
        </div>
    </div>
</header>