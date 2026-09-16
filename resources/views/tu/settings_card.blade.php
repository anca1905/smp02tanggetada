@extends('layouts.app')

@section('title', 'Desain Kartu Pelajar')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg font-medium border border-green-200">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kustomisasi ID Card</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah tema warna dan teks ketentuan pada bagian belakang kartu pelajar.</p>
            </div>
        </div>

        <form action="{{ route('tu.settings.update.card') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Teks & Identitas Kartu</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah (Kop Depan)</label>
                    <input type="text" name="id_card_school_name" 
                        value="{{ $settings['id_card_school_name'] ?? ($settings['app_name'] ?? 'SMP MODERN NU PLEMAHAN') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Misal: SMP MODERN NU PLEMAHAN">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Sekolah (Kop Depan)</label>
                    <input type="text" name="id_card_school_address" 
                        value="{{ $settings['id_card_school_address'] ?? 'Jl. Pendidikan No. 1, Kecamatan Pendidikan, Kabupaten Pendidikan' }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Misal: Jl. Raya Plemahan No 99 Desa Plemahan...">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kepala Sekolah (Tanda Tangan)</label>
                    <input type="text" name="id_card_principal_name" 
                        value="{{ $settings['id_card_principal_name'] ?? 'Nama Kepala Sekolah, M.Pd' }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Beserta Gelar (contoh: Drs. Budi Santoso, M.Pd)">
                </div>
            </div>

            <h3 class="font-bold text-gray-700 border-b pb-2 mb-4 mt-8">Pengaturan Warna Tema</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Header/Kop Utama</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="id_card_bg_color_1" 
                            value="{{ $settings['id_card_bg_color_1'] ?? '#166534' }}"
                            class="w-12 h-12 rounded cursor-pointer border border-gray-300 p-1">
                        <span class="text-xs text-gray-500">Default: Hijau Tua (#166534)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Gelombang Aksen 1</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="id_card_bg_color_2" 
                            value="{{ $settings['id_card_bg_color_2'] ?? '#eab308' }}"
                            class="w-12 h-12 rounded cursor-pointer border border-gray-300 p-1">
                        <span class="text-xs text-gray-500">Default: Kuning (#eab308)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Gelombang Aksen 2</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="id_card_bg_color_3" 
                            value="{{ $settings['id_card_bg_color_3'] ?? '#15803d' }}"
                            class="w-12 h-12 rounded cursor-pointer border border-gray-300 p-1">
                        <span class="text-xs text-gray-500">Default: Hijau Terang (#15803d)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna Teks Data</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="id_card_font_color" 
                            value="{{ $settings['id_card_font_color'] ?? '#111827' }}"
                            class="w-12 h-12 rounded cursor-pointer border border-gray-300 p-1">
                        <span class="text-xs text-gray-500">Default: Gelap/Hitam (#111827)</span>
                    </div>
                </div>
            </div>

            <h3 class="font-bold text-gray-700 border-b pb-2 mb-4 mt-8">Bagian Belakang Kartu</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ketentuan / Tata Tertib</label>
                <p class="text-xs text-gray-500 mb-3">Teks ini akan muncul di bagian belakang ID Card. Gunakan baris baru untuk membuat poin list.</p>
                <textarea name="id_card_rules" rows="6" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                    placeholder="Contoh:&#10;1. Kartu ini berlaku selama menjadi siswa.&#10;2. Wajib dibawa setiap hari.">{{ $settings['id_card_rules'] ?? "1. Kartu ini berlaku selama pemilik berstatus sebagai siswa di sekolah ini.\n2. Kartu ini tidak boleh berpindah milik.\n3. Apabila Anda kehilangan kartu ini, harap segera melapor ke pihak sekolah.\n4. Kartu ini wajib digunakan untuk absensi kehadiran." }}</textarea>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-colors">
                    Simpan Desain Kartu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
