@extends('layouts.app')

@section('title', 'Pengaturan Web')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Website</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tu.settings.update.website') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-fit">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Identitas & Kontak</h3>
                    <div class="space-y-4">
                        <div class="mb-4 flex items-center gap-4">
                            <div class="shrink-0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Sekolah</label>
                                <div
                                    class="h-20 w-20 rounded-lg border border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                                    @if (isset($settings['school_logo']))
                                        <img src="{{ asset('storage/' . $settings['school_logo']) }}"
                                            class="w-full h-full object-contain p-1">
                                    @else
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="grow">
                                <input type="file" name="school_logo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="mt-1 text-xs text-gray-500">Format: PNG/JPG (Transparan direkomendasikan). Max:
                                    2MB.</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi / Sekolah</label>
                            <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'SIMS Terpadu' }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="school_phone"
                                value="{{ $settings['school_phone'] ?? '(0741) 123456' }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" name="school_email"
                                value="{{ $settings['school_email'] ?? 'admin@sekolah.sch.id' }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="school_address" rows="3" class="w-full border-gray-300 rounded-lg">{{ $settings['school_address'] ?? 'Jl. Jendral Sudirman...' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-fit">
                    <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Tampilan Beranda (Hero)</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Besar (Hero Title)</label>
                            <input type="text" name="hero_title"
                                value="{{ $settings['hero_title'] ?? 'Digitalisasi Pendidikan Menuju Sekolah Unggul' }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                            <textarea name="hero_description" rows="3" class="w-full border-gray-300 rounded-lg">{{ $settings['hero_description'] ?? 'Platform terintegrasi...' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Background Hero</label>
                            @if (isset($settings['hero_bg']))
                                <img src="{{ asset('storage/' . $settings['hero_bg']) }}"
                                    class="w-full h-32 object-cover rounded-lg mb-2 border border-gray-200">
                            @endif
                            <input type="file" name="hero_bg" class="text-sm text-gray-500">
                        </div>
                    </div>
                </div>

            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-fit lg:col-span-2">
                <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Profil Sekolah</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Visi Sekolah</label>
                            <textarea name="school_visi" rows="4" class="w-full border-gray-300 rounded-lg">{{ $settings['school_visi'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Misi Sekolah</label>
                            <textarea name="school_misi" rows="4" class="w-full border-gray-300 rounded-lg"
                                placeholder="Pisahkan dengan enter...">{{ $settings['school_misi'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Struktur Organisasi</label>
                        <div class="flex items-center space-x-4">
                            @if (isset($settings['struktur_img']))
                                <img src="{{ asset('storage/' . $settings['struktur_img']) }}"
                                    class="h-32 object-contain border border-gray-200 rounded p-1">
                            @endif
                            <input type="file" name="struktur_img"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
