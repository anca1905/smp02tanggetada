let currentClass = null;

function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        btn.classList.add('text-gray-500');
    });
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));

    if (tabName === 'classes') {
        document.getElementById('tab-classes').classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        document.getElementById('tab-classes').classList.remove('text-gray-500');
        document.getElementById('content-classes').classList.remove('hidden');
        document.getElementById('content-form').classList.add('hidden');
    } else if (tabName === 'history') {
        document.getElementById('tab-history').classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        document.getElementById('tab-history').classList.remove('text-gray-500');
        document.getElementById('content-history').classList.remove('hidden');
    }
}

function openPresensiForm(kelas) {
    currentClass = kelas;
    document.getElementById('class-title').innerText = "Kelas " + kelas;

    document.getElementById('content-classes').classList.add('hidden');
    document.getElementById('content-form').classList.remove('hidden');

    document.getElementById('step-1').classList.remove('hidden');
    document.getElementById('step-2').classList.add('hidden');
}

function goToStep2() {
    fetch(`/api/siswa/${currentClass}`)
        .then(res => res.json())
        .then(data => {
            renderStudentList(data);
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        })
        .catch(err => alert('Gagal memuat data siswa'));
}

function backToStep1() {
    document.getElementById('step-2').classList.add('hidden');
    document.getElementById('step-1').classList.remove('hidden');
}

function renderStudentList(students) {
    const tbody = document.getElementById('student-list-body');
    tbody.innerHTML = '';
    document.getElementById('total-students').innerText = students.length;

    students.forEach((s, index) => {
        const row = `
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-4 border-b border-gray-100">${index + 1}</td>
                    <td class="px-6 py-4 border-b border-gray-100 font-medium text-gray-900">${s.nama} <br><span class="text-xs text-gray-400">${s.nis}</span></td>
                    <td class="px-6 py-4 border-b border-gray-100">${s.gender}</td>
                    <td class="px-6 py-4 border-b border-gray-100 text-center">
                        <div class="inline-flex bg-gray-100 rounded-lg p-1">
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${s.id}" value="H" class="peer sr-only" checked>
                                <span class="px-3 py-1 rounded-md text-sm transition-all peer-checked:bg-green-500 peer-checked:text-white text-gray-500 hover:text-gray-700">H</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${s.id}" value="S" class="peer sr-only">
                                <span class="px-3 py-1 rounded-md text-sm transition-all peer-checked:bg-yellow-400 peer-checked:text-white text-gray-500 hover:text-gray-700">S</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${s.id}" value="I" class="peer sr-only">
                                <span class="px-3 py-1 rounded-md text-sm transition-all peer-checked:bg-blue-400 peer-checked:text-white text-gray-500 hover:text-gray-700">I</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_${s.id}" value="A" class="peer sr-only">
                                <span class="px-3 py-1 rounded-md text-sm transition-all peer-checked:bg-red-500 peer-checked:text-white text-gray-500 hover:text-gray-700">A</span>
                            </label>
                        </div>
                    </td>
                </tr>
            `;
        tbody.innerHTML += row;
    });
}

function saveAttendance() {
    if (confirm('Simpan data presensi ini?')) {
        alert('✅ Data Berhasil Disimpan!');
        switchTab('classes');
    }
}