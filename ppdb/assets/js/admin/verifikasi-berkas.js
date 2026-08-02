// --- 1. DATA DUMMY ---
let verifyData = [
    {
        reg: "REG-2026-099",
        nama: "Dimas Anggara",
        jurusan: "RPL",
        tgl: "14 Jan 2026",
        waktu: "08:30 WIB",
        status: "Pending",
        docs: { kk: true, ijazah: true, akta: true }
    },
    {
        reg: "REG-2026-102",
        nama: "Sinta Bella",
        jurusan: "TKJ",
        tgl: "14 Jan 2026",
        waktu: "09:15 WIB",
        status: "Pending",
        docs: { kk: true, ijazah: false, akta: true } // Ijazah missing
    },
    {
        reg: "REG-2026-088",
        nama: "Budi Santoso",
        jurusan: "TBSM",
        tgl: "13 Jan 2026",
        waktu: "10:00 WIB",
        status: "Verified",
        docs: { kk: true, ijazah: true, akta: true }
    }
];

let currentFilter = 'Pending'; // Default filter

// --- 2. RENDER TABLE ---
function renderTable() {
    const tableBody = document.getElementById('verifTableBody');
    const searchInput = document.getElementById('searchInput').value.toLowerCase();

    const filteredData = verifyData.filter(item => {
        const matchSearch = item.nama.toLowerCase().includes(searchInput) || item.reg.toLowerCase().includes(searchInput);
        const matchFilter = currentFilter === 'all' || item.status === currentFilter;
        return matchSearch && matchFilter;
    });

    // Update Counts dengan Safety Check (Anti Null Error)
    const pendingCount = verifyData.filter(i => i.status === 'Pending').length;

    const badgeEl = document.getElementById('badgePending');
    if (badgeEl) badgeEl.innerText = pendingCount;

    const infoEl = document.getElementById('infoPendingCount');
    if (infoEl) infoEl.innerText = pendingCount + " Siswa";

    let html = '';
    if (filteredData.length === 0) {
        html = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data ditemukan</td></tr>`;
    } else {
        filteredData.forEach(item => {
            // Badge Jurusan
            let jurBadge = "bg-gray-100 text-gray-700";
            if (item.jurusan === 'RPL') jurBadge = "bg-blue-100 text-blue-700";
            if (item.jurusan === 'TKJ') jurBadge = "bg-green-100 text-green-700";
            if (item.jurusan === 'TBSM') jurBadge = "bg-orange-100 text-orange-700";

            // Docs Icons
            let docIcons = `
                        <span class="${item.docs.kk ? 'text-green-600' : 'text-red-500'} text-xs" title="KK"><i class="fas ${item.docs.kk ? 'fa-check-circle' : 'fa-times-circle'}"></i> KK</span>
                        <span class="${item.docs.ijazah ? 'text-green-600' : 'text-red-500'} text-xs" title="Ijazah"><i class="fas ${item.docs.ijazah ? 'fa-check-circle' : 'fa-times-circle'}"></i> Ijazah</span>
                        <span class="${item.docs.akta ? 'text-green-600' : 'text-red-500'} text-xs" title="Akta"><i class="fas ${item.docs.akta ? 'fa-check-circle' : 'fa-times-circle'}"></i> Akta</span>
                    `;

            // Status Badge & Action
            let statusBadge = '';
            let actionBtn = '';

            if (item.status === 'Pending') {
                statusBadge = `<span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded font-bold border border-yellow-200">Pending</span>`;
                actionBtn = `<button onclick="openModal('${item.reg}')" class="bg-school-blue text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-blue-800 shadow-sm flex items-center gap-1 mx-auto"><i class="fas fa-eye"></i> Cek Berkas</button>`;
            } else if (item.status === 'Verified') {
                statusBadge = `<span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold border border-green-200">Terverifikasi</span>`;
                actionBtn = `<button onclick="openModal('${item.reg}')" class="bg-gray-200 text-gray-600 px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-300 shadow-sm flex items-center gap-1 mx-auto"><i class="fas fa-search"></i> Detail</button>`;
            } else {
                statusBadge = `<span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-bold border border-red-200">Ditolak</span>`;
                actionBtn = `<button onclick="openModal('${item.reg}')" class="bg-gray-200 text-gray-600 px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-300 shadow-sm flex items-center gap-1 mx-auto"><i class="fas fa-search"></i> Detail</button>`;
            }

            html += `
                        <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                            <td class="px-6 py-4 text-xs text-gray-500">${item.tgl}<br>${item.waktu}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-school-blue">${item.nama}</p>
                                <p class="text-xs text-gray-500 font-mono">${item.reg}</p>
                            </td>
                            <td class="px-6 py-4"><span class="${jurBadge} px-2 py-1 rounded text-xs font-bold">${item.jurusan}</span></td>
                            <td class="px-6 py-4"><div class="flex gap-2">${docIcons}</div></td>
                            <td class="px-6 py-4 text-center">${statusBadge}</td>
                            <td class="px-6 py-4 text-center">${actionBtn}</td>
                        </tr>
                    `;
        });
    }
    tableBody.innerHTML = html;
}

// --- 3. ACTIONS ---
function filterTable(status) {
    currentFilter = status;
    renderTable();
}

// --- 4. MODAL LOGIC ---
const modal = document.getElementById('verifModal');
const previewArea = document.getElementById('previewArea');

function openModal(reg) {
    const item = verifyData.find(d => d.reg === reg);
    if (!item) return;

    document.getElementById('modalRegId').value = reg;
    document.getElementById('modalStudentName').innerText = "Verifikasi: " + item.nama;
    document.getElementById('modalStudentJurusan').innerText = "Calon Siswa Jurusan " + item.jurusan;

    // Reset Preview
    previewArea.innerHTML = `
                <div class="text-center text-gray-500">
                    <i class="fas fa-eye text-4xl mb-2 text-school-blue"></i>
                    <p class="text-sm">Klik dokumen di kiri<br>untuk melihat pratinjau.</p>
                </div>
            `;

    // Action Buttons Logic
    const actionsDiv = document.getElementById('modalActions');
    if (item.status === 'Pending') {
        actionsDiv.innerHTML = `
                    <button onclick="closeModal()" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100 font-bold text-sm">Batal</button>
                    <button onclick="processVerif('${reg}', 'Rejected')" class="px-4 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200 font-bold text-sm">Tolak</button>
                    <button onclick="processVerif('${reg}', 'Verified')" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold text-sm shadow-lg"><i class="fas fa-check-circle mr-1"></i> Valid</button>
                `;
    } else {
        actionsDiv.innerHTML = `
                    <span class="text-sm text-gray-500 italic mr-auto">Status: <strong>${item.status}</strong></span>
                    <button onclick="closeModal()" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100 font-bold text-sm">Tutup</button>
                `;
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    modal.classList.add('hidden');
}

function previewDoc(type) {
    // Simulasi preview
    previewArea.innerHTML = `
                <div class="text-center animate-pulse">
                    <i class="fas fa-file-image text-6xl text-gray-300 mb-4"></i>
                    <p class="font-bold text-gray-600">Preview Dokumen: ${type}</p>
                    <p class="text-xs text-gray-400">(Simulasi Tampilan Gambar/PDF)</p>
                </div>
            `;
}

function processVerif(reg, status) {
    const index = verifyData.findIndex(d => d.reg === reg);

    if (status === 'Verified') {
        Swal.fire({
            title: 'Verifikasi Valid?',
            text: "Siswa akan dinyatakan diterima.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Valid',
            confirmButtonColor: '#16a34a'
        }).then((result) => {
            if (result.isConfirmed) {
                verifyData[index].status = 'Verified';
                closeModal();
                renderTable();
                Swal.fire('Berhasil', 'Data siswa terverifikasi.', 'success');
            }
        });
    } else {
        Swal.fire({
            title: 'Tolak Berkas?',
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Contoh: Scan Ijazah buram...',
            showCancelButton: true,
            confirmButtonText: 'Kirim Penolakan',
            confirmButtonColor: '#dc2626'
        }).then((result) => {
            if (result.isConfirmed) {
                verifyData[index].status = 'Rejected';
                closeModal();
                renderTable();
                Swal.fire('Ditolak', 'Notifikasi dikirim ke siswa.', 'info');
            }
        });
    }
}

// --- INIT ---
document.addEventListener('DOMContentLoaded', renderTable);
modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });

// Sidebar Toggle Script
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