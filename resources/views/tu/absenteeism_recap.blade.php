@extends('layouts.app')

@section('title', 'Rekap Absensi Siswa')

@section('content')
    <div class="h-full flex flex-col min-w-0">

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 p-4">
            <form method="GET" action="{{ route('tu.rekap') }}">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-end md:items-center">

                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sesi</label>
                            <select name="sesi" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-40">
                                <option value="" {{ !$sesi ? 'selected' : '' }}>Semua Sesi</option>
                                <option value="apel"   {{ $sesi == 'apel'   ? 'selected' : '' }}>Apel Pagi</option>
                                <option value="kelas"  {{ $sesi == 'kelas'  ? 'selected' : '' }}>Di Kelas</option>
                                <option value="pulang" {{ $sesi == 'pulang' ? 'selected' : '' }}>Pulang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                            <select name="bulan" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-40">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
                            <select name="kelas" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-32">
                                <option value="">Semua</option>
                                @foreach ($classrooms as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 w-full md:w-auto">
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <a href="{{ route('tu.rekap.pdf', array_merge(request()->query(), ['stream' => 1])) }}"
                            target="_blank"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors shadow-sm whitespace-nowrap">
                            <i class="fas fa-print mr-2"></i> Cetak
                        </a>
                        <a href="{{ route('tu.rekap.pdf', request()->query()) }}"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors shadow-sm whitespace-nowrap">
                            <i class="fas fa-file-pdf mr-2"></i> Export PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex-1 flex flex-col min-w-0">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                    Rekap Absensi Siswa
                    @if($sesi)
                        @php $sesiMap = ['apel'=>'Apel Pagi','kelas'=>'Di Kelas','pulang'=>'Pulang']; @endphp
                        — Sesi {{ $sesiMap[$sesi] ?? $sesi }}
                    @endif
                    — Bulan {{ \Carbon\Carbon::create()->month($bulan)->format('F') }}
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-gray-600 font-medium border-b border-gray-200 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Sesi</th>
                            <th class="px-6 py-3">Nama Lengkap</th>
                            <th class="px-6 py-3">Kelas</th>
                            <th class="px-6 py-3">Status Kehadiran</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->attendance->date)->isoFormat('dddd, D MMM Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $sesiMap = [
                                            'apel'   => ['icon' => 'fa-sun', 'label' => 'Apel Pagi', 'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
                                            'kelas'  => ['icon' => 'fa-chalkboard-user', 'label' => 'Di Kelas', 'class' => 'bg-blue-50 text-blue-800 border-blue-200'],
                                            'pulang' => ['icon' => 'fa-house-user', 'label' => 'Pulang', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                                        ];
                                        $currentSesi = $sesiMap[$item->attendance->session_type ?? ''] ?? null;
                                    @endphp
                                    @if ($currentSesi)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $currentSesi['class'] }}">
                                            <i class="fas {{ $currentSesi['icon'] }} text-[10px]"></i>
                                            {{ $currentSesi['label'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            —
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ optional($item->student)->student_name ?? 'Data Siswa Dihapus' }}
                                </td>

                                <td class="px-6 py-4">{{ optional(optional($item->student)->classroom)->name ?? '-' }}</td>

                                <td class="px-6 py-4">
                                    @php
                                        [$color, $text] = match (strtolower($item->status)) {
                                            'present'    => ['bg-green-100 text-green-800', 'Hadir'],
                                            'sick'       => ['bg-yellow-100 text-yellow-800', 'Sakit'],
                                            'permission' => ['bg-blue-100 text-blue-800', 'Izin'],
                                            'absent'     => ['bg-red-100 text-red-800', 'Alpa'],
                                            'late'       => ['bg-orange-100 text-orange-800', 'Terlambat'],
                                            'bolos'      => ['bg-purple-100 text-purple-800', 'Bolos'],
                                            default      => ['bg-gray-100 text-gray-800', 'Tidak Absen'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                        {{ $text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-center">
                                        @php
                                            $parentPhone = optional($item->student)->parent_phone ?: optional($item->student)->phone_number;
                                            $cleanParentPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $parentPhone ?? ''));
                                            $tglStr = \Carbon\Carbon::parse($item->attendance->date)->isoFormat('dddd, D MMMM Y');
                                            $statusLabel = strtolower($item->status) === 'bolos' ? 'BOLOS' : 'ALPA (Tanpa Keterangan)';
                                            $waMsg = "Yth. Orang Tua dari siswa " . (optional($item->student)->student_name ?? 'Siswa') . " (" . (optional(optional($item->student)->classroom)->name ?? '-') . "),\nKami menginformasikan bahwa pada hari {$tglStr}, ananda tercatat {$statusLabel} pada absensi sekolah.\nMohon konfirmasi atau hubungi pihak sekolah terkait kehadiran ananda. Terima kasih.";
                                        @endphp
                                        @if($cleanParentPhone && in_array(strtolower($item->status), ['absent', 'bolos']))
                                            <a href="https://wa.me/{{ $cleanParentPhone }}?text={{ urlencode($waMsg) }}" target="_blank"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition shadow-xs"
                                               title="Kirim Peringatan WA ke Orang Tua">
                                                <i class="fab fa-whatsapp text-emerald-600"></i>
                                                <span>WA Ortu</span>
                                            </a>
                                        @endif
                                        <button type="button"
                                            onclick="openEditStatusModal({{ $item->id }}, '{{ addslashes(optional($item->student)->student_name ?? 'Siswa') }}', '{{ addslashes(optional(optional($item->student)->classroom)->name ?? '-') }}', '{{ \Carbon\Carbon::parse($item->attendance->date)->isoFormat('dddd, D MMMM Y') }}', '{{ $item->status }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition shadow-xs cursor-pointer">
                                            <i class="fas fa-edit text-xs"></i>
                                            <span>Ubah</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-folder-open text-4xl text-gray-300 mb-2"></i>
                                        <p>Belum ada data absensi untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $data->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status Presensi -->
    <div id="editStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all border border-gray-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-900 to-indigo-900 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <i class="fas fa-user-edit text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base">Koreksi Status Presensi</h3>
                        <p class="text-xs text-blue-200">Perbarui status kehadiran siswa</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditStatusModal()" class="text-white/70 hover:text-white text-lg cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="editStatusForm" method="POST" action="">
                @csrf
                @method('PATCH')

                <div class="p-6 space-y-4">
                    <!-- Student Info Box -->
                    <div class="bg-blue-50/60 rounded-xl p-3.5 border border-blue-100 text-xs text-gray-700 space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nama Siswa:</span>
                            <span id="modalStudentName" class="font-bold text-gray-900 text-right"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Kelas:</span>
                            <span id="modalStudentClass" class="font-semibold text-gray-800 text-right"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Tanggal:</span>
                            <span id="modalAttendanceDate" class="font-medium text-gray-700 text-right"></span>
                        </div>
                    </div>

                    <!-- Status Picker Radio Cards -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Pilih Status Baru:</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- Hadir -->
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-green-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50/70 has-[:checked]:ring-2 has-[:checked]:ring-green-500/20">
                                <input type="radio" name="status" value="present" class="sr-only">
                                <span class="w-6 h-6 rounded-full bg-green-500 text-white font-bold text-xs flex items-center justify-center">H</span>
                                <span class="text-xs font-semibold text-gray-800">Hadir</span>
                            </label>

                            <!-- Terlambat -->
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-amber-50 transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/70 has-[:checked]:ring-2 has-[:checked]:ring-amber-500/20">
                                <input type="radio" name="status" value="late" class="sr-only">
                                <span class="w-6 h-6 rounded-full bg-amber-500 text-white font-bold text-xs flex items-center justify-center">T</span>
                                <span class="text-xs font-semibold text-gray-800">Terlambat</span>
                            </label>

                            <!-- Sakit -->
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-yellow-50 transition has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50/70 has-[:checked]:ring-2 has-[:checked]:ring-yellow-500/20">
                                <input type="radio" name="status" value="sick" class="sr-only">
                                <span class="w-6 h-6 rounded-full bg-yellow-400 text-white font-bold text-xs flex items-center justify-center">S</span>
                                <span class="text-xs font-semibold text-gray-800">Sakit</span>
                            </label>

                            <!-- Izin -->
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-blue-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/70 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                                <input type="radio" name="status" value="permission" class="sr-only">
                                <span class="w-6 h-6 rounded-full bg-blue-500 text-white font-bold text-xs flex items-center justify-center">I</span>
                                <span class="text-xs font-semibold text-gray-800">Izin</span>
                            </label>

                            <!-- Alpa -->
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-red-50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50/70 has-[:checked]:ring-2 has-[:checked]:ring-red-500/20">
                                <input type="radio" name="status" value="absent" class="sr-only">
                                <span class="w-6 h-6 rounded-full bg-red-500 text-white font-bold text-xs flex items-center justify-center">A</span>
                                <span class="text-xs font-semibold text-gray-800">Alpa</span>
                            </label>

                            <!-- Bolos -->
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-purple-50 transition has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/70 has-[:checked]:ring-2 has-[:checked]:ring-purple-600/20">
                                <input type="radio" name="status" value="bolos" class="sr-only">
                                <span class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center">B</span>
                                <span class="text-xs font-semibold text-gray-800">Bolos</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-3.5 border-t border-gray-100 flex justify-end gap-2.5">
                    <button type="button" onclick="closeEditStatusModal()"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditStatusModal(detailId, studentName, studentClass, attendanceDate, currentStatus) {
            document.getElementById('modalStudentName').textContent = studentName;
            document.getElementById('modalStudentClass').textContent = 'Kelas ' + studentClass;
            document.getElementById('modalAttendanceDate').textContent = attendanceDate;

            const form = document.getElementById('editStatusForm');
            form.action = "{{ url('/tu/absenteeism-recap') }}/" + detailId;

            // Select current status
            const radios = form.querySelectorAll('input[name="status"]');
            radios.forEach(r => {
                r.checked = (r.value.toLowerCase() === currentStatus.toLowerCase());
            });

            document.getElementById('editStatusModal').classList.remove('hidden');
        }

        function closeEditStatusModal() {
            document.getElementById('editStatusModal').classList.add('hidden');
        }

        // Close on backdrop click
        document.getElementById('editStatusModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditStatusModal();
            }
        });
    </script>
@endsection
