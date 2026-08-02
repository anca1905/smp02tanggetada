fetch("layouts/sidebar.html")
    .then(res => res.text())
    .then(data => {
        document.getElementById("sidebar").innerHTML = data;

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // ⬇⬇ JADIKAN GLOBAL
        window.toggleSidebar = function () {
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        };
    })
    .catch(err => console.error(err));
