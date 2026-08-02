/**
 * Admin API Helper Functions
 * Handles Authentication, Generic CRUD, and SweetAlerts
 */

const API_BASE = '../api/admin';
const AUTH_API = '../api/auth';

// 1. Check Authentication on Page Load
async function checkAuth() {
    try {
        const response = await fetch(`${AUTH_API}/session.php`);
        const result = await response.json();
        if (result.status !== 'authenticated') {
            window.location.href = 'index.html';
        } else {
            // Optional: Update UI with user info if element exists
            const userEl = document.getElementById('user-name');
            if (userEl) userEl.innerText = result.user.name;
        }
    } catch (e) {
        window.location.href = 'index.html';
    }
}

// 2. Generic Fetch Data (GET)
async function fetchData(endpoint) {
    try {
        const response = await fetch(`${API_BASE}/${endpoint}`);
        return await response.json();
    } catch (error) {
        console.error(`Error fetching ${endpoint}:`, error);
        return { status: 'error', message: 'Gagal memuat data.' };
    }
}

// 3. Generic Send Data (POST/PUT/DELETE)
async function sendData(endpoint, method, data = null) {
    const options = {
        method: method,
        headers: {}
    };

    if (data instanceof FormData) {
        // FormData doesn't need Content-Type header (browser sets it)
        options.body = data;
    } else if (data) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE}/${endpoint}`, options);
        return await response.json();
    } catch (error) {
        console.error(`Error sending to ${endpoint}:`, error);
        return { status: 'error', message: 'Terjadi kesalahan sistem.' };
    }
}

// 4. Show Toast Notification
function showToast(icon, title) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    Toast.fire({ icon: icon, title: title });
}

// 5. Generic Delete Confirmation
async function confirmDelete(endpoint, id, callback) {
    const result = await Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        // Support POST with _method=DELETE or actual DELETE method
        const apiRes = await sendData(endpoint, 'POST', JSON.stringify({ id: id, _method: 'DELETE' }));

        if (apiRes.status === 'success') {
            showToast('success', 'Data berhasil dihapus');
            if (callback) callback();
        } else {
            Swal.fire('Gagal!', apiRes.message || 'Gagal menghapus data.', 'error');
        }
    }
}

// Auto-run Auth Check
document.addEventListener('DOMContentLoaded', checkAuth);
