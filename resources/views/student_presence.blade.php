@extends('layouts.app')

@section('title', 'Presensi Siswa')

@push('head')
{{-- qrcodejs untuk generate QR di browser --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('content')
    <div class="space-y-6">

        {{-- Filter Kelas & Tanggal --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <form method="GET" action="{{ route('teacher.student-attendance') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                    <select name="kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        required onchange="this.form.submit()">
                        <option value="" disabled {{ !$selectedClass ? 'selected' : '' }}>-- Pilih Kelas --</option>
                        @foreach ($classList as $id => $name)
                            <option value="{{ $id }}" {{ request('kelas') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Sesi</label>
                    <select name="sesi" id="sesiFilter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        onchange="this.form.submit()">
                        <option value="apel"   {{ $selectedSession == 'apel'   ? 'selected' : '' }}>Apel Pagi</option>
                        <option value="kelas"  {{ $selectedSession == 'kelas'  ? 'selected' : '' }}>Di Kelas</option>
                        <option value="pulang" {{ $selectedSession == 'pulang' ? 'selected' : '' }}>Pulang</option>
                    </select>
                </div>
                
                @if($selectedSession === 'kelas')
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="subject_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        onchange="this.form.submit()" required>
                        <option value="" disabled {{ !$selectedSubject ? 'selected' : '' }}>-- Pilih Mapel --</option>
                        @foreach ($subjects ?? [] as $subject)
                            <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $selectedDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        onchange="this.form.submit()">
                </div>
                <div class="md:col-span-{{ $selectedSession === 'kelas' ? '5' : '2' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-2">
                    @if ($selectedClass)
                        @php
                            $sessionLabels = ['apel'=>'Apel Pagi','kelas'=>'Di Kelas','pulang'=>'Pulang'];
                        @endphp
                        <span class="text-xs sm:text-sm {{ $attendanceData ? 'text-green-600' : 'text-gray-500' }}">
                            <i class="fas {{ $attendanceData ? 'fa-check-circle' : 'fa-info-circle' }} mr-1"></i>
                            Sesi <strong>{{ $sessionLabels[$selectedSession] ?? $selectedSession }}</strong>:
                            {{ $attendanceData ? 'Data sudah tersimpan (Mode Edit)' : 'Belum ada data (Mode Input Baru)' }}
                        </span>

                        {{-- Tombol Buka QR --}}
                        <button type="button" id="btnOpenQr"
                            onclick="openQrModal()"
                            class="w-full sm:w-auto flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg transition shadow">
                            <i class="fas fa-qrcode"></i>
                            <span>Buka QR Presensi</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        @if ($selectedClass && count($students) > 0)
            <form action="{{ route('teacher.student-attendance-store') }}" method="POST" onsubmit="confirmAction(event, this, 'Simpan Presensi?', 'Data kehadiran siswa akan disimpan.', 'Ya, Simpan')">
                @csrf
                <input type="hidden" name="class" value="{{ $selectedClass }}">
                <input type="hidden" name="session_type" value="{{ $selectedSession }}">
                @if($selectedSession === 'kelas')
                <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
                @endif
                <input type="hidden" name="date" value="{{ $selectedDate }}">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs sm:text-sm text-left text-gray-500">
                            <thead class="text-[11px] sm:text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 w-10 text-center">No</th>
                                    <th class="px-3 sm:px-6 py-3">Nama Siswa</th>
                                    <th class="px-3 sm:px-6 py-3 text-center">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($students as $index => $student)
                                    @php
                                        $status = $student->saved_status ?? 'present';
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">{{ $index + 1 }}</td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                                            <p class="font-medium text-gray-900">{{ $student->student_name }}</p>
                                            <p class="text-[11px] sm:text-xs text-gray-400">{{ $student->nis }}</p>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                                            <div class="flex justify-center space-x-2 sm:space-x-4">
                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="present" class="peer sr-only"
                                                        {{ $status == 'present' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-green-500 peer-checked:border-green-600 peer-checked:text-white text-gray-400 transition-all hover:bg-green-50">
                                                        <span class="font-bold text-xs sm:text-sm">H</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-green-600 font-medium">Hadir</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="late" class="peer sr-only"
                                                        {{ $status == 'late' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-amber-500 peer-checked:border-amber-600 peer-checked:text-white text-gray-400 transition-all hover:bg-amber-50">
                                                        <span class="font-bold text-xs sm:text-sm">T</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-amber-500 font-medium">Terlambat</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="sick" class="peer sr-only"
                                                        {{ $status == 'sick' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-yellow-400 peer-checked:border-yellow-500 peer-checked:text-white text-gray-400 transition-all hover:bg-yellow-50">
                                                        <span class="font-bold text-xs sm:text-sm">S</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-yellow-500 font-medium">Sakit</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="permission" class="peer sr-only"
                                                        {{ $status == 'permission' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-blue-500 peer-checked:border-blue-600 peer-checked:text-white text-gray-400 transition-all hover:bg-blue-50">
                                                        <span class="font-bold text-xs sm:text-sm">I</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-blue-600 font-medium">Izin</span>
                                                </label>

                                                <label class="cursor-pointer flex flex-col items-center group">
                                                    <input type="radio" name="attendance[{{ $student->nis }}][status]"
                                                        value="absent" class="peer sr-only"
                                                        {{ $status == 'absent' ? 'checked' : '' }}>
                                                    <div
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:bg-red-500 peer-checked:border-red-600 peer-checked:text-white text-gray-400 transition-all hover:bg-red-50">
                                                        <span class="font-bold text-xs sm:text-sm">A</span>
                                                    </div>
                                                    <span
                                                        class="text-[10px] mt-1 text-gray-400 peer-checked:text-red-600 font-medium">Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 px-4 sm:px-6 py-3.5 sm:py-4 border-t border-gray-200 flex justify-end sticky bottom-0 z-10">
                        <button type="submit"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 sm:px-8 rounded-lg shadow-lg flex items-center justify-center transform transition hover:-translate-y-0.5 text-sm sm:text-base">
                            <i class="fas fa-save mr-2"></i> Simpan Presensi
                        </button>
                    </div>
                </div>
            </form>
        @elseif($selectedClass)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm">
                <div class="flex">
                    <div class="shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Tidak ada siswa ditemukan di <strong>Kelas {{ $selectedClass }}</strong>.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div
                class="flex flex-col items-center justify-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                <div class="p-4 bg-blue-50 rounded-full mb-3">
                    <i class="fas fa-user-graduate text-3xl text-blue-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Mulai Presensi</h3>
                <p class="text-gray-500">Silakan pilih <strong>Kelas</strong> dan <strong>Tanggal</strong> di atas untuk
                    memuat daftar siswa.</p>
            </div>
        @endif

    </div>

    {{-- ============================================================
         MODAL QR PRESENSI
    ============================================================ --}}
    <div id="qrModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4"
         style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-white font-bold text-lg">QR Presensi</h2>
                    <p class="text-indigo-100 text-sm" id="qrSubtitle">Kelas — | Tanggal —</p>
                </div>
                <button onclick="closeQrModal()" class="text-white hover:text-indigo-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 flex flex-col items-center">
                {{-- Loading state --}}
                <div id="qrLoading" class="flex flex-col items-center gap-3 py-8">
                    <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-gray-500 text-sm">Membuat QR Code...</p>
                </div>

                {{-- QR Canvas --}}
                <div id="qrCodeContainer" class="hidden flex flex-col items-center gap-4">
                    <div id="qrcode" class="p-3 border-4 border-indigo-100 rounded-xl"></div>

                    {{-- Timer --}}
                    <div class="flex items-center gap-2 bg-indigo-50 rounded-full px-4 py-2">
                        <i class="fas fa-clock text-indigo-500"></i>
                        <span class="text-sm font-semibold text-indigo-700">Berlaku: </span>
                        <span id="qrTimer" class="text-sm font-bold text-indigo-900">05:00</span>
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        Tampilkan QR ini ke siswa.<br>QR otomatis kadaluarsa setelah 5 menit.
                    </p>

                    {{-- Refresh button --}}
                    <button onclick="generateQr()" id="btnRefreshQr"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold transition text-sm">
                        <i class="fas fa-sync-alt"></i>
                        Refresh QR (Perpanjang 5 menit)
                    </button>
                </div>

                {{-- Expired state --}}
                <div id="qrExpired" class="hidden flex-col items-center gap-3 py-4">
                    <div class="p-4 bg-red-50 rounded-full">
                        <i class="fas fa-times-circle text-red-400 text-4xl"></i>
                    </div>
                    <p class="text-red-600 font-semibold">QR Code Kadaluarsa!</p>
                    <button onclick="generateQr()"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-semibold transition text-sm">
                        <i class="fas fa-sync-alt"></i>
                        Buat QR Baru
                    </button>
                </div>
            </div>

            {{-- Footer: siapa saja yang sudah scan --}}
            <div class="border-t border-gray-100 px-6 pb-4 pt-3">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                    <i class="fas fa-users mr-1"></i> Sudah Check-in
                    <span id="checkinCount" class="ml-1 bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">0</span>
                    dari <span id="totalStudents">{{ count($students) }}</span>
                </p>
                <div id="checkinList" class="flex flex-wrap gap-2 max-h-24 overflow-y-auto">
                    <span class="text-xs text-gray-400 italic">Belum ada yang scan.</span>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        const GENERATE_QR_URL = "{{ route('teacher.student-attendance-generate-qr') }}";
        const CSRF_TOKEN      = "{{ csrf_token() }}";
        const SELECTED_CLASS  = "{{ $selectedClass }}";
        const SELECTED_SESSION = "{{ $selectedSession }}";
        const SELECTED_SUBJECT = "{{ $selectedSubject }}";
        const SELECTED_DATE   = "{{ $selectedDate }}";

        let qrInstance     = null;
        let timerInterval  = null;
        let pollInterval   = null;
        let expiresAt      = null;

        // Nama kelas untuk ditampilkan (nilai nama bukan ID)
        const CLASS_NAME = @json($classList->get($selectedClass) ?? $selectedClass);
        const SUBJECT_NAME = @json(collect($subjects ?? [])->firstWhere('id', $selectedSubject)?->name ?? '');

        function openQrModal() {
            document.getElementById('qrModal').classList.remove('hidden');
            generateQr();
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
            clearInterval(timerInterval);
            clearInterval(pollInterval);
        }

        async function generateQr() {
            // Reset state
            clearInterval(timerInterval);
            clearInterval(pollInterval);
            showState('loading');

            try {
                const payload = { class: SELECTED_CLASS, session_type: SELECTED_SESSION, date: SELECTED_DATE };
                if (SELECTED_SESSION === 'kelas') {
                    payload.subject_id = SELECTED_SUBJECT;
                }

                const res = await fetch(GENERATE_QR_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept'      : 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();
                if (!data.success) throw new Error(data.message || 'Gagal generate QR');

                expiresAt = new Date(data.expires_at);

                // Render QR code
                const container = document.getElementById('qrcode');
                container.innerHTML = '';
                qrInstance = new QRCode(container, {
                    text  : data.qr_token,
                    width : 220,
                    height: 220,
                    colorDark  : '#312e81',
                    colorLight : '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H,
                });

                let subtitle = `Kelas ${CLASS_NAME} | ${SELECTED_DATE}`;
                if (SELECTED_SESSION === 'kelas' && SUBJECT_NAME) {
                    subtitle += ` | Mapel: ${SUBJECT_NAME}`;
                }
                
                document.getElementById('qrSubtitle').textContent = subtitle;

                showState('qr');
                startTimer();
                startPoll(data.qr_token);

            } catch (err) {
                alert('Gagal membuat QR: ' + err.message);
                closeQrModal();
            }
        }

        function startTimer() {
            updateTimer();
            timerInterval = setInterval(() => {
                const now  = new Date();
                const diff = expiresAt - now;
                if (diff <= 0) {
                    clearInterval(timerInterval);
                    clearInterval(pollInterval);
                    showState('expired');
                    return;
                }
                updateTimer();
            }, 1000);
        }

        function updateTimer() {
            const diff    = Math.max(0, expiresAt - new Date());
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('qrTimer').textContent =
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        function showState(state) {
            document.getElementById('qrLoading').classList.add('hidden');
            document.getElementById('qrCodeContainer').classList.add('hidden');
            document.getElementById('qrExpired').classList.add('hidden');

            if (state === 'loading')  document.getElementById('qrLoading').classList.remove('hidden');
            if (state === 'qr')       document.getElementById('qrCodeContainer').classList.remove('hidden');
            if (state === 'expired')  document.getElementById('qrExpired').classList.remove('hidden');
        }

        // Poll setiap 5 detik untuk update daftar siswa yang sudah check-in
        function startPoll(token) {
            pollCheckin(token);
            pollInterval = setInterval(() => pollCheckin(token), 5000);
        }

        async function pollCheckin(token) {
            try {
                // Ambil daftar siswa yang sudah check-in (endpoint public, tidak perlu auth)
                let pollUrl = `/api/student/attendance/checkin-list?class=${encodeURIComponent(SELECTED_CLASS)}&session_type=${encodeURIComponent(SELECTED_SESSION)}&date=${SELECTED_DATE}`;
                if (SELECTED_SESSION === 'kelas') {
                    pollUrl += `&subject_id=${SELECTED_SUBJECT}`;
                }

                const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success) return;

                const list    = data.data ?? [];
                const countEl = document.getElementById('checkinCount');
                const listEl  = document.getElementById('checkinList');

                countEl.textContent = list.length;
                if (list.length === 0) {
                    listEl.innerHTML = '<span class="text-xs text-gray-400 italic">Belum ada yang scan.</span>';
                } else {
                    listEl.innerHTML = list.map(s =>
                        `<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                            <i class="fas fa-check mr-1"></i>${s.student_name}
                        </span>`
                    ).join('');
                }
            } catch (_) {}
        }
    </script>
    @endpush
@endsection
