/**
 * Logic for Pengaturan Web Page
 */

document.addEventListener('DOMContentLoaded', () => {
    loadSettings();
});

async function loadSettings() {
    // Show loading state if needed

    // Add timestamp to prevent caching
    const result = await fetchData('pengaturan.php?_t=' + new Date().getTime());

    if (result.status === 'success' && result.data) {
        const d = result.data;

        document.getElementById('settingId').value = d.id;
        document.getElementById('nama_sekolah').value = d.nama_sekolah;
        document.getElementById('slogan').value = d.slogan;
        document.getElementById('deskripsi').value = d.deskripsi;
        document.getElementById('email').value = d.email;
        document.getElementById('telepon').value = d.telepon;
        document.getElementById('alamat').value = d.alamat;
        document.getElementById('maps_embed').value = d.maps_embed;

        document.getElementById('facebook').value = d.facebook;
        document.getElementById('instagram').value = d.instagram;
        document.getElementById('youtube').value = d.youtube;

        // Checkboxes - Handle strictly
        // Ensure we check against 1, "1", true, or "true"
        const isTrue = (val) => val == 1 || val === '1' || val === true || val === 'true';
        document.getElementById('mode_maintenance').checked = isTrue(d.mode_maintenance);
        document.getElementById('buka_ppdb').checked = isTrue(d.buka_ppdb);

        // Brochure Link
        if (d.file_brosur) {
            document.getElementById('current_brosur').classList.remove('hidden');
            document.getElementById('link_brosur').href = `../../assets/uploads/${d.file_brosur}`;
            document.getElementById('link_brosur').innerText = d.file_brosur;
        } else {
            document.getElementById('current_brosur').classList.add('hidden');
        }
    }
}

async function saveSettings(event) {
    event.preventDefault();

    const formData = new FormData();
    formData.append('id', document.getElementById('settingId').value);
    formData.append('nama_sekolah', document.getElementById('nama_sekolah').value);
    formData.append('slogan', document.getElementById('slogan').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('telepon', document.getElementById('telepon').value);
    formData.append('alamat', document.getElementById('alamat').value);
    formData.append('maps_embed', document.getElementById('maps_embed').value);
    formData.append('facebook', document.getElementById('facebook').value);
    formData.append('instagram', document.getElementById('instagram').value);
    formData.append('youtube', document.getElementById('youtube').value);
    formData.append('mode_maintenance', document.getElementById('mode_maintenance').checked);
    formData.append('buka_ppdb', document.getElementById('buka_ppdb').checked);

    const fileInput = document.getElementById('file_brosur');
    if (fileInput.files.length > 0) {
        formData.append('file_brosur', fileInput.files[0]);
    }

    // Use sendData which handles FormData automatically
    const result = await sendData('pengaturan.php', 'POST', formData);

    if (result.status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Pengaturan website telah disimpan.',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            loadSettings(); // Reload to update "Current Brochure" link
        });
    } else {
        Swal.fire('Error', result.message, 'error');
    }
}