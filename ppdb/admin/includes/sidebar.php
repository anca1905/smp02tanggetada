<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside id="sidebar"
    class="bg-school-blue text-white w-64 flex-shrink-0 fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 z-50 sidebar-transition flex flex-col h-full shadow-xl">
    
    <div class="h-16 flex items-center justify-center border-b border-blue-800 bg-blue-900 shadow-sm">
        <div class="flex items-center gap-2 font-bold text-lg tracking-wide">
            <i class="fas fa-school text-yellow-400"></i> SMK SKILLANCE
        </div>
    </div>

    <div class="p-4 border-b border-blue-800 flex items-center gap-3 bg-blue-900/50">
        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white font-bold border border-white/20">
            AD
        </div>
        <div>
            <p class="text-sm font-bold text-white">Administrator</p>
            <p class="text-xs text-blue-200">Super Admin</p>
        </div>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-2">Utama</p>

        <a href="dashboard.php" class="flex items-center px-3 py-2.5 rounded-md group shadow-inner <?= $currentPage == 'dashboard.php' ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white transition-colors' ?>">
            <i class="fas fa-home w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Dashboard</span>
        </a>

        <p class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-6">Akademik & Siswa</p>

        <!-- Data Siswa (Placeholder/Future) -->
        <!-- 
        <a href="#" class="flex items-center px-3 py-2.5 text-blue-100 hover:bg-blue-800 hover:text-white rounded-md group transition-colors">
            <i class="fas fa-user-graduate w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Data Siswa</span>
        </a> 
        -->

        <a href="data-guru.php" class="flex items-center px-3 py-2.5 rounded-md group transition-colors <?= $currentPage == 'data-guru.php' ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?>">
            <i class="fas fa-chalkboard-teacher w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Data Guru & GTK</span>
        </a>
        <a href="data-mapel.php" class="flex items-center px-3 py-2.5 rounded-md group transition-colors <?= $currentPage == 'data-mapel.php' ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?>">
            <i class="fas fa-book w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Mata Pelajaran</span>
        </a>

        <p class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-6">PPDB Online</p>

        <a href="data-ppdb.php" class="flex items-center px-3 py-2.5 rounded-md group transition-colors <?= $currentPage == 'data-ppdb.php' ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?>">
            <i class="fas fa-clipboard-list w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Data Pendaftar</span>
        </a>
        <!-- Verifikasi Berkas merged into Data Pendaftar for now or separate based on user req, keep simplified -->

        <p class="px-3 text-xs font-semibold text-blue-300 uppercase tracking-wider mb-2 mt-6">Website</p>

        <a href="data-berita.php" class="flex items-center px-3 py-2.5 rounded-md group transition-colors <?= $currentPage == 'data-berita.php' ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?>">
            <i class="fas fa-newspaper w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Berita & Artikel</span>
        </a>
        <a href="pengaturan-web.php" class="flex items-center px-3 py-2.5 rounded-md group transition-colors <?= $currentPage == 'pengaturan-web.php' ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-800 hover:text-white' ?>">
            <i class="fas fa-cog w-6 text-center text-sm"></i>
            <span class="ml-2 text-sm font-medium">Pengaturan Web</span>
        </a>
    </nav>

    <div class="p-4 border-t border-blue-800">
        <button onclick="logout()" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-200 bg-blue-900 rounded-md hover:bg-red-600 hover:text-white transition cursor-pointer">
            <i class="fas fa-sign-out-alt w-5 mr-2"></i> Keluar Sistem
        </button>
    </div>

</aside>
