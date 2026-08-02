<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - SMK Skillance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'school-blue': '#003366', // Biru Dinas
                        'school-dark': '#0f172a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Transisi halus buat sidebar */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .modal-scroll::-webkit-scrollbar { width: 8px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .modal-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar will be included here -->
        <?php include 'sidebar.php'; ?>

        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"
            onclick="toggleSidebar()"></div>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header
                class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-10 border-b border-gray-200">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()"
                        class="text-gray-500 focus:outline-none md:hidden mr-4 hover:text-school-blue">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 hidden sm:block"><?= $pageHeader ?? 'Dashboard' ?></h2>
                </div>

                <div class="flex items-center gap-4">
                    <span id="user-name" class="text-sm font-semibold text-gray-600 hidden sm:block">...</span>
                    <button onclick="logout()" class="text-red-500 hover:text-red-700 font-bold text-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                    <a href="../index.html" target="_blank"
                        class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-school-blue text-sm font-bold rounded-full hover:bg-blue-100 transition border border-blue-200">
                        <i class="fas fa-globe"></i> Lihat Website
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 sm:p-6">
