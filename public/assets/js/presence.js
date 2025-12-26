document.addEventListener('DOMContentLoaded', () => {

    const USE_MOCK_API = false;

    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        const clockEl = document.getElementById('clock');
        const dateEl = document.getElementById('date');

        if (clockEl) clockEl.textContent = timeString;
        if (dateEl) dateEl.textContent = dateString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const statusText = document.getElementById('status-text');
    const statusIndicator = document.getElementById('status-indicator');
    const cameraPlaceholder = document.getElementById('camera-placeholder');

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480, facingMode: "user" } });
            video.srcObject = stream;
            await video.play();

            if (cameraPlaceholder) cameraPlaceholder.classList.add('hidden');
            if (statusIndicator) {
                statusIndicator.classList.remove('bg-red-500');
                statusIndicator.classList.add('bg-green-500', 'pulse-animation');
            }
            if (statusText) statusText.textContent = 'Kamera aktif dan siap digunakan';

        } catch (err) {
            console.error("Error akses kamera:", err);
            if (cameraPlaceholder) {
                cameraPlaceholder.classList.remove('hidden');
                cameraPlaceholder.innerHTML = `<div class="text-center p-4"><p class="text-red-500 font-bold mb-2">Akses Kamera Ditolak</p><p class="text-xs text-gray-500">Pastikan izin kamera diaktifkan di browser.</p></div>`;
            }
            if (statusText) {
                statusText.textContent = "Kamera tidak terdeteksi / Izin ditolak";
                statusText.classList.add('text-red-500');
            }
            if (statusIndicator) {
                statusIndicator.classList.remove('bg-green-500', 'pulse-animation');
                statusIndicator.classList.add('bg-red-500');
            }
        }
    }
    startCamera();

    const identityInput = document.getElementById('identity');
    const passwordInput = document.getElementById('password');
    const captureBtn = document.getElementById('capture-btn');
    const dropdown = document.getElementById('autocomplete-dropdown');

    let timeoutId;
    identityInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length < 3) {
            dropdown.classList.add('hidden');
            validateForm();
            return;
        }

        clearTimeout(timeoutId);
        timeoutId = setTimeout(async () => {
            if (USE_MOCK_API) {
                const mockTeachers = [
                    { name: 'Budi Santoso', ID: '123456' },
                    { name: 'Siti Aminah', ID: '654321' }
                ];
                renderDropdown(mockTeachers.filter(t => t.name.toLowerCase().includes(query.toLowerCase())));
            } else {
                try {
                    const response = await fetch(`/presensi/search?query=${query}`);
                    const teachers = await response.json();
                    renderDropdown(teachers);
                } catch (error) {
                    console.error('Error searching teachers:', error);
                }
            }
        }, 300);
    });

    function renderDropdown(teachers) {
        dropdown.innerHTML = '';
        if (teachers.length > 0) {
            dropdown.classList.remove('hidden');
            teachers.forEach(teacher => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-blue-50 cursor-pointer text-sm border-b border-gray-100 last:border-0 transition-colors';
                div.innerHTML = `<span class="font-semibold text-gray-700">${teacher.name}</span> <span class="text-xs text-gray-400 ml-1">(${teacher.ID})</span>`;

                div.onclick = () => {
                    identityInput.value = teacher.ID;
                    dropdown.classList.add('hidden');
                    validateForm();
                };
                dropdown.appendChild(div);
            });
        } else {
            dropdown.classList.add('hidden');
        }
    }

    document.addEventListener('click', (e) => {
        if (!identityInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function validateForm() {
        if (identityInput.value.length >= 3 && passwordInput.value.length > 0) {
            captureBtn.disabled = false;
            captureBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            captureBtn.classList.add('hover:bg-blue-700');
        } else {
            captureBtn.disabled = true;
            captureBtn.classList.add('opacity-50', 'cursor-not-allowed');
            captureBtn.classList.remove('hover:bg-blue-700');
        }
    }
    identityInput.addEventListener('input', validateForm);
    passwordInput.addEventListener('input', validateForm);

    validateForm();

    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalDetails = document.getElementById('modal-details');
    const modalIcon = document.getElementById('modal-icon');
    const modalContent = document.getElementById('modal-content');
    const modalCloseBtn = document.getElementById('modal-close');

    function showModal(title, message, type, detail = null) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        modalIcon.className = 'mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4';
        modalTitle.className = 'text-2xl font-bold';
        modalDetails.className = 'mt-5 p-4 rounded-xl text-sm hidden';
        modalDetails.innerHTML = '';

        if (type === 'success') {
            modalIcon.classList.add('bg-green-100');
            modalIcon.innerHTML = `
            <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>`;
            modalTitle.classList.add('text-green-600');
            modalDetails.classList.add('bg-green-50', 'text-black-800');
        } else if (type === 'error') {
            modalIcon.classList.add('bg-red-100');
            modalIcon.innerHTML = `
            <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>`;
            modalTitle.classList.add('text-red-600');
            modalDetails.classList.add('bg-red-50', 'text-red-800');
        }

        if (detail && typeof detail === 'object') {
            modalDetails.innerHTML = `
            <div class="space-y-2">
                <div class="flex">
                    <span class="w-28 font-medium">Nama</span><span>:</span>
                    <span class="ml-2">${detail.nama}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-medium">ID</span><span>:</span>
                    <span class="ml-2">${detail.id}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-medium">Tanggal</span><span>:</span>
                    <span class="ml-2">${detail.tanggal}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-medium">Waktu</span><span>:</span>
                    <span class="ml-2">${detail.waktu}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-medium">Keterangan</span><span>:</span>
                    <span class="ml-2 font-semibold">${detail.keterangan}</span>
                </div>
            </div>
        `;
            modalDetails.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    modalCloseBtn.addEventListener('click', () => {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    });

    captureBtn.addEventListener('click', async () => {
        const identity = identityInput.value;
        const password = passwordInput.value;

        if (!identity || !password) return;

        const originalBtnText = captureBtn.innerHTML;
        captureBtn.disabled = true;
        captureBtn.innerHTML = `<svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;

        const maxWidth = 640;
        const aspectRatio = video.videoWidth / video.videoHeight;

        canvas.width = maxWidth;
        canvas.height = maxWidth / aspectRatio;

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/jpeg', 0.7);

        try {
            if (USE_MOCK_API) {

            } else {
                const response = await fetch('/presensi/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        identity: identity,
                        password: password,
                        image: imageData
                    })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showModal(result.message, '', 'success', result.data);

                    identityInput.value = '';
                    passwordInput.value = '';
                    validateForm();
                } else if (result.status === 'warning') {
                    showModal('Perhatian', result.message, 'info');
                } else {
                    showModal('Gagal', result.message, 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showModal('Error Sistem', 'Terjadi kesalahan saat menghubungkan ke server.', 'error');
        } finally {
            captureBtn.disabled = false;
            captureBtn.innerHTML = originalBtnText;
            validateForm();
        }
    });
});