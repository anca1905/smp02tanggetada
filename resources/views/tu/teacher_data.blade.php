@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
    <div class="h-full flex flex-col min-w-0">

        {{-- @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
 --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 p-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Tabel Data Guru</h2>

                <div class="flex flex-col sm:flex-row gap-3 items-center">
                    <form action="{{ route('tu.teacher.index') }}" method="GET" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIP atau Nama..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                        <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </form>

                    <button onclick="openModal('add')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Guru
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex-1 flex flex-col min-w-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Foto</th>
                            <th class="px-6 py-3">Nama Lengkap</th>
                            <th class="px-6 py-3">NIP/UID</th>
                            <th class="px-6 py-3">Jabatan / Mapel</th>
                            <th class="px-6 py-3">Wali Kelas</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($paginatedTeachers as $teacher)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4">
                                    @php
                                        $avatar = $teacher->photo_url
                                            ? (str_starts_with($teacher->photo_url, 'img/') ? asset($teacher->photo_url) : asset('storage/' . $teacher->photo_url))
                                            : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($teacher->name);
                                    @endphp
                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                        src="{{ $avatar }}" alt="Foto">
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $teacher->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $teacher->employee_id }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($teacher->position)
                                        <div class="font-medium text-gray-900">{{ $teacher->position }}</div>
                                        @if($teacher->subject)
                                            <div class="text-gray-500 text-xs">Guru Mapel {{ $teacher->subject }}</div>
                                        @endif
                                    @else
                                        {{ $teacher->subject ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($teacher->homeroom_class && $teacher->homeroom_class != '-')
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">{{ $teacher->homeroom_class }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $teacher->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $teacher->status == 'Active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="openModal('edit', {{ json_encode($teacher) }})"
                                            class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 rounded hover:bg-blue-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button onclick="confirmDelete({{ $teacher->id }})"
                                            class="text-red-600 hover:text-red-800 p-1 bg-red-50 rounded hover:bg-red-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <p>Tidak ada data guru ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $paginatedTeachers->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div id="teacherModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-2xl transform transition-all scale-100 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Tambah Guru</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="teacherForm" method="POST" data-action="{{ route('tu.teacher.store') }}"
                action="{{ route('tu.teacher.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                <input type="hidden" id="teacherId" name="id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="teacherName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP / ID <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="employee_id" id="teacherNIP"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="teacherPhone"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <select name="gender" id="teacherGender"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" selected>Pilih</option>
                            <option value="Male">Laki-laki</option>
                            <option value="Female">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="teacherStatus"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan / Tugas Tambahan (Opsional)</label>
                        <input type="text" name="position" id="teacherPosition" placeholder="Contoh: Wakil Kepala Sekolah Kesiswaan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Jabatan ini akan ditampilkan di halaman depan (GTK).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran <span
                                class="text-red-500">*</span></label>
                        <select name="subject" id="teacherSubject"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Pilih Mapel</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas</label>
                        <select name="homeroom_class" id="teacherWaliKelas"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Bukan Wali Kelas</option>
                            @foreach($classrooms as $cls)
                                <option value="Wali Kelas {{ $cls->name }}">Wali Kelas {{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (Opsional)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full overflow-hidden border border-gray-200 bg-gray-100 shrink-0 flex items-center justify-center">
                            <img id="photoPreview" src="https://ui-avatars.com/api/?name=Guru&background=E5E7EB&color=6B7280" alt="Preview Foto" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo_url" id="fotoGuru" accept="image/jpeg,image/png,image/jpg"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 mt-4">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">Akun Login</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="username" id="teacherUsername"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="teacherPassword" placeholder="Minimal 6 karakter"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1" id="passwordHint">Wajib diisi untuk guru baru.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-md">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST"
        class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        @csrf
        @method('DELETE')
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hapus Data Guru?</h3>
            <p class="text-gray-500 text-sm mb-6">Data yang dihapus tidak da pat dikembalikan. Lanjutkan?</p>
            <div class="flex space-x-3 justify-center">
                <button type="button"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
                    onclick="document.getElementById('deleteForm').classList.add('hidden')">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-md">Ya,
                    Hapus</button>
            </div>
        </div>
    </form>
@endsection
@push('js')
    <script src="{{ asset('assets/js/tu/teacher_data.js') }}"></script>
@endpush
