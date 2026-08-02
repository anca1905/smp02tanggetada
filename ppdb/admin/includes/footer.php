            </main>

            <footer class="bg-white border-t p-4 text-center text-xs text-gray-500">
                &copy; <?= date('Y') ?> Sistem Informasi SMK Negeri 1 Skillance. Versi 1.0.0
            </footer>

        </div>
    </div>

    <!-- GLOBAL SCRIPTS -->
    <script src="../assets/js/admin-api.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
        function logout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await fetch('../api/auth/logout.php');
                    window.location.href = 'index.html';
                }
            });
        }
    </script>
    
    <!-- Page Specific Scripts -->
    <?php if(isset($extraScripts)) echo $extraScripts; ?>

</body>
</html>
