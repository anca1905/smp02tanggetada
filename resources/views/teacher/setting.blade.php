@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    <div class="max-w-4xl mx-auto">

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center">
                <div class="text-green-500 mr-3"><i class="fas fa-check-circle text-xl"></i></div>
                <div>
                    <p class="font-bold text-green-800">Berhasil!</p>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex items-start">
                    <div class="text-red-500 mr-3 mt-0.5"><i class="fas fa-exclamation-circle text-xl"></i></div>
                    <div>
                        <p class="font-bold text-red-800">Gagal Menyimpan</p>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="relative inline-block mb-4">
                        @php
                            $avatar = $teacher->photo_url
                                ? asset($teacher->photo_url)
                                : 'https://ui-avatars.com/api/?background=random&size=128&name=' .
                                    urlencode($teacher->name);
                        @endphp
                        <img id="preview-photo"
                            class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg bg-gray-100"
                            src="{{ $avatar }}" alt="Foto Profil">

                        <span
                            class="absolute bottom-2 right-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full border-2 border-white">
                            Guru
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800">{{ $teacher->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $teacher->username }}</p>

                    <div class="border-t pt-4 text-left">
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider mb-2">Info Pegawai</p>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-id-card w-5 text-center mr-2 text-blue-500"></i>
                            <span>NIP/ID: {{ $teacher->ID }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-book-open w-5 text-center mr-2 text-blue-500"></i>
                            <span>Mapel: {{ $teacher->subject ?? '-' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-chalkboard-teacher w-5 text-center mr-2 text-blue-500"></i>
                            <span>Wali Kelas: {{ $teacher->homeroom_class ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">Edit Profil Guru</h2>
                    </div>

                    <form action="{{ route('teacher.settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="p-6 space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $teacher->name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username Login</label>
                                <input type="text" name="username" value="{{ old('username', $teacher->username) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ganti Foto Profil</label>
                            <input type="file" name="photo_url" id="foto-input" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>

                        <hr class="border-gray-100">

                        <div>
                            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-lock mr-2 text-gray-400"></i> Keamanan
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama <span
                                            class="text-red-500">*</span></label>
                                    <input type="password" name="current_password"
                                        placeholder="Masukkan password saat ini untuk konfirmasi"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                        <input type="password" name="new_password" placeholder="Minimal 6 karakter"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ulangi Password
                                            Baru</label>
                                        <input type="password" name="new_password_confirmation"
                                            placeholder="Konfirmasi password baru"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit"
                                class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all transform active:scale-95">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const photoInput = document.getElementById('foto-input');
        const previewPhoto = document.getElementById('preview-photo');

        photoInput.onchange = evt => {
            const [file] = photoInput.files;
            if (file) {
                previewPhoto.src = URL.createObjectURL(file);
            }
        }
    </script>
@endsection
