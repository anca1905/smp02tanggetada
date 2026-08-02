/**
 * PPDB Dynamic Frontend Logic
 * Handles API calls to PHP backend.
 */

const API_BASE_URL = 'api';

document.addEventListener('DOMContentLoaded', async () => {
    // 1. Load Header & Footer first
    // 1. Initialize Mobile Menu
    initMobileMenu();

    // 2. Check System Settings (Maintenance & School Info) - uses header/footer elements
    checkSystemSettings();

    // 3. Initialize specific page logic
    if (document.getElementById('form-ppdb')) {
        initPPDBForm();
        loadMajorsForSelect();
    }

    if (document.getElementById('majors-grid')) {
        loadMajorsGrid();
    }

    if (document.getElementById('majors-list')) {
        loadMajorsList();
    }

    if (document.getElementById('news-grid')) {
        loadNewsGrid();
    }

    if (document.getElementById('news-list')) {
        loadNewsList();
    }
});

/**
 * Load Header and Footer dynamically
 */


/**
 * Initialize Mobile Menu functionality
 */
function initMobileMenu() {
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }
}

/**
 * Check Settings (Maintenance Mode & Dynamic Info)
 */
async function checkSystemSettings() {
    try {
        const response = await fetch(`${API_BASE_URL}/public/settings.php`);
        const result = await response.json();

        if (result.status === 'success') {
            const settings = result.data;

            // 1. Cek Maintenance Mode
            if (settings.mode_maintenance == 1 && !window.location.pathname.includes('maintenance.html')) {
                window.location.href = 'maintenance.html';
                return;
            }

            if (settings.mode_maintenance == 0 && window.location.pathname.includes('maintenance.html')) {
                window.location.href = 'index.html';
                return;
            }

            // 2. Update Info Sekolah di UI
            updateUIWithSettings(settings);

            // 3. Cek Status Buka PPDB (hanya di halaman PPDB)
            const ppdbForm = document.getElementById('form-ppdb');
            if (ppdbForm && settings.buka_ppdb == 0) {
                ppdbForm.innerHTML = `
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Pendaftaran Ditutup</p>
                        <p>Mohon maaf, periode Pendaftaran Peserta Didik Baru (PPDB) saat ini telah ditutup.</p>
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Failed to load settings:', error);
    }
}

function updateUIWithSettings(s) {
    // Topbar Contact Info
    // Note: We need ids in header.html for precise targeting, or use selectors
    // Assuming standard layout:
    const phoneEl = document.querySelector('.fa-phone-alt')?.parentElement;
    if (phoneEl) phoneEl.innerHTML = `<i class="fas fa-phone-alt mr-1"></i> ${s.telepon}`;

    const emailEl = document.querySelector('.fa-envelope')?.parentElement;
    if (emailEl) emailEl.innerHTML = `<i class="fas fa-envelope mr-1"></i> ${s.email}`;

    // Social Media
    const fbEl = document.querySelector('.fa-facebook')?.parentElement;
    if (fbEl && s.facebook) fbEl.href = s.facebook;

    const igEl = document.querySelector('.fa-instagram')?.parentElement;
    if (igEl && s.instagram) igEl.href = s.instagram;

    const ytEl = document.querySelector('.fa-youtube')?.parentElement;
    if (ytEl && s.youtube) ytEl.href = s.youtube;

    // Hero Section (Homepage)
    const heroTitle = document.getElementById('hero-title');
    if (heroTitle) heroTitle.innerHTML = `PPDB ${new Date().getFullYear() + 1} <br> Telah Dibuka`; // Dynamic Year? Or use settings? Settings doesn't have year.

    const heroSlogan = document.getElementById('hero-slogan');
    if (heroSlogan && s.slogan) heroSlogan.innerText = s.slogan;

    const heroDesc = document.getElementById('hero-desc');
    if (heroDesc && s.deskripsi) heroDesc.innerText = s.deskripsi;

    // Footer
    const footerText = document.querySelector('footer p');
    if (footerText) footerText.innerHTML = `&copy; ${new Date().getFullYear()} ${s.nama_sekolah}. All rights reserved.`;

    // Brochure Download
    const btnBrosur = document.getElementById('btn-download-brosur');
    if (btnBrosur) {
        if (s.file_brosur) {
            btnBrosur.href = `assets/uploads/${s.file_brosur}`;
            btnBrosur.target = '_blank';
        } else {
            // Optional: Hide button if no brochure
            btnBrosur.style.display = 'none';
        }
    }
}


/**
 * Halaman PPDB: Submit Form
 */
function initPPDBForm() {
    const form = document.getElementById('form-ppdb');
    const submitBtn = document.getElementById('btn-submit');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`${API_BASE_URL}/ppdb.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message);
                form.reset();
                window.location.href = 'index.html';
            } else {
                alert('GAGAL: ' + result.message);
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem. Silakan coba lagi.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
}

/**
 * Halaman PPDB: Load Pilihan Jurusan
 */
async function loadMajorsForSelect() {
    const select = document.getElementById('jurusan-select');
    if (!select) return;

    try {
        const response = await fetch(`${API_BASE_URL}/jurusan.php`);
        const result = await response.json();

        if (result.status === 'success') {
            select.innerHTML = '<option value="" disabled selected>-- Pilih Jurusan --</option>';
            result.data.forEach(major => {
                const option = document.createElement('option');
                option.value = major.code;
                option.textContent = `${major.name} (${major.code})`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Gagal memuat jurusan:', error);
        select.innerHTML = `<option value="" disabled selected>Check Connection</option>`;
    }
}

/**
 * Homepage: Load Jurusan Grid (Simple Cards)
 */
async function loadMajorsGrid() {
    const container = document.getElementById('majors-grid');
    container.innerHTML = '<p class="text-center col-span-full">Memuat data jurusan...</p>';

    try {
        const response = await fetch(`${API_BASE_URL}/jurusan.php`);
        const result = await response.json();

        if (result.status === 'success') {
            container.innerHTML = '';
            // Render as simple cards
            result.data.forEach((major, index) => {
                // Colors cycle
                const colors = ['green', 'blue', 'orange', 'purple'];
                const color = colors[index % colors.length];

                // Simple Grid Card
                const html = `
                <div class="bg-white p-4 rounded shadow hover:shadow-lg transition border-t-4 border-${color}-500">
                    <div class="h-12 w-12 bg-${color}-100 text-${color}-600 rounded flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap"></i> <!-- Generic Icon for now -->
                    </div>
                    <h4 class="font-bold text-gray-800 mb-1">${major.code}</h4>
                    <p class="text-xs text-gray-500">${major.name}</p>
                </div>
                `;
                container.innerHTML += html;
            });
        }
    } catch (error) {
        container.innerHTML = '<p class="text-center text-red-500 col-span-full">Gagal memuat data jurusan.</p>';
    }
}

/**
 * Jurusan Page: Load Jurusan List (Detailed Rows)
 */
async function loadMajorsList() {
    const container = document.getElementById('majors-list');
    container.innerHTML = '<p class="text-center">Memuat data jurusan...</p>';

    try {
        const response = await fetch(`${API_BASE_URL}/jurusan.php`);
        const result = await response.json();

        if (result.status === 'success') {
            container.innerHTML = '';
            result.data.forEach((major, index) => {
                const colors = ['green', 'blue', 'orange', 'purple'];
                const color = colors[index % colors.length];
                const isEven = index % 2 === 0;
                const flexDirection = isEven ? 'md:flex-row' : 'md:flex-row-reverse';

                const html = `
                <div class="flex flex-col ${flexDirection} gap-8 items-center bg-white p-6 rounded-xl shadow-sm border-t-4 border-${color}-500">
                    <div class="md:w-1/2">
                        <img src="${major.image_url}" class="rounded-lg shadow-md w-full h-80 object-cover" alt="${major.name}">
                    </div>
                    <div class="md:w-1/2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-${color}-100 text-${color}-600 rounded-full flex items-center justify-center text-2xl">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-school-blue">${major.name} (${major.code})</h2>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            ${major.description}
                        </p>
                        <a href="ppdb.html" class="inline-block bg-${color}-600 text-white px-6 py-2 rounded font-bold hover:bg-${color}-700 transition">
                            Daftar Jurusan Ini
                        </a>
                    </div>
                </div>
                `;
                container.innerHTML += html;
            });
        }
    } catch (error) {
        container.innerHTML = '<p class="text-center text-red-500">Gagal memuat data jurusan.</p>';
    }
}

/**
 * Homepage: Load News Grid (3 items)
 */
async function loadNewsGrid() {
    const container = document.getElementById('news-grid');
    container.innerHTML = '<p class="text-center col-span-full">Memuat berita...</p>';

    try {
        const response = await fetch(`${API_BASE_URL}/berita.php`);
        const result = await response.json();

        if (result.status === 'success') {
            container.innerHTML = '';

            // Limit to 3 items for homepage
            const newsItems = result.data.slice(0, 3);

            if (newsItems.length === 0) {
                container.innerHTML = '<p class="text-center col-span-full">Belum ada berita.</p>';
                return;
            }

            newsItems.forEach(news => {
                const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
                const date = new Date(news.tanggal).toLocaleDateString('id-ID', dateOptions);

                const html = `
                <div class="bg-white border border-gray-200 rounded overflow-hidden shadow-sm hover:shadow-md transition">
                    <img src="${news.gambar}" class="w-full h-48 object-cover" alt="${news.judul}">
                    <div class="p-4">
                        <span class="text-xs text-gray-500 mb-2 block"><i class="far fa-calendar"></i> ${date}</span>
                        <h4 class="font-bold text-gray-800 text-base mb-2 hover:text-school-blue cursor-pointer">
                            <a href="detail-berita.php?id=${news.id}">${news.judul}</a>
                        </h4>
                        <a href="detail-berita.php?id=${news.id}" class="text-xs font-bold text-school-yellow uppercase">Baca Selengkapnya</a>
                    </div>
                </div>
                `;
                container.innerHTML += html;
            });
        }
    } catch (error) {
        container.innerHTML = '<p class="text-center text-red-500 col-span-full">Gagal memuat berita.</p>';
    }
}

/**
 * Berita Page: Load All News List
 */
async function loadNewsList() {
    const container = document.getElementById('news-list');
    container.innerHTML = '<p class="text-center">Memuat berita...</p>';

    try {
        const response = await fetch(`${API_BASE_URL}/berita.php`);
        const result = await response.json();

        if (result.status === 'success') {
            container.innerHTML = '';

            if (result.data.length === 0) {
                container.innerHTML = '<p class="text-center">Belum ada berita.</p>';
                return;
            }

            result.data.forEach(news => {
                const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
                const date = new Date(news.tanggal).toLocaleDateString('id-ID', dateOptions);

                const html = `
                <article class="flex flex-col md:flex-row bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="md:w-1/3">
                        <img src="${news.gambar}" alt="${news.judul}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 md:w-2/3">
                        <div class="text-xs text-gray-500 mb-2">
                            <i class="far fa-calendar-alt"></i> ${date}
                            <span class="ml-2 bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-[10px]">${news.kategori}</span>
                        </div>
                        <h3 class="text-xl font-bold text-school-blue mb-2 hover:text-school-yellow transition">
                            <a href="detail-berita.php?id=${news.id}">${news.judul}</a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            ${news.ringkasan}
                        </p>
                        <a href="detail-berita.php?id=${news.id}" class="text-school-blue font-bold text-sm hover:underline">Baca Selengkapnya &rarr;</a>
                    </div>
                </article>
                `;
                container.innerHTML += html;
            });
        }
    } catch (error) {
        container.innerHTML = '<p class="text-center text-red-500">Gagal memuat berita.</p>';
    }
}
