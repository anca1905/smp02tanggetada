@extends('layouts.public')

@section('title', 'Buku Tamu Digital - ' . ($site_settings['app_name'] ?? 'SIMS'))
@section('header', 'Buku Tamu Digital Sekolah')
@section('subheader', 'Selamat datang di SMP Negeri 02 Tanggetada. Silakan mengisi data kunjungan Anda pada formulir di bawah ini.')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if(session('success'))
        <div class="mb-6 p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-900 rounded-3xl flex items-start gap-4 shadow-sm animate-fade-in">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg shrink-0 shadow">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <h4 class="font-bold text-base">Kunjungan Berhasil Dicatat!</h4>
                <p class="text-xs sm:text-sm text-emerald-800 mt-0.5 leading-relaxed">
                    {{ session('success') }}
                </p>
                <div class="mt-2 text-xs font-semibold text-emerald-700">
                    Silakan menuju ke ruang lobi / resepsionis untuk dipersilakan bertemu pihak sekolah.
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-teal-800 via-emerald-800 to-cyan-900 p-6 sm:p-8 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl text-teal-200 shadow">
                    <i class="fas fa-address-book"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black">Formulir Kunjungan Tamu</h2>
                    <p class="text-teal-100 text-xs mt-0.5">Satu formulir cepat untuk pencatatan resmi pelayanan sekolah.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('public.buku-tamu.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap Tamu <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Nama lengkap Anda..."
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-300 text-sm focus:border-teal-600 focus:ring-4 focus:ring-teal-50 focus:outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Instansi / Lembaga / Alamat Asal
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-building"></i>
                        </div>
                        <input type="text" name="institution" value="{{ old('institution') }}"
                               placeholder="Contoh: Dinas Pendidikan / Desa..."
                               class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-300 text-sm focus:border-teal-600 focus:ring-4 focus:ring-teal-50 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nomor WhatsApp / HP Aktif <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               placeholder="Contoh: 081234567890"
                               class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-300 text-sm focus:border-teal-600 focus:ring-4 focus:ring-teal-50 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Kategori Tamu <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required class="w-full px-4 py-3 rounded-2xl border border-gray-300 text-sm focus:border-teal-600 focus:ring-4 focus:ring-teal-50 focus:outline-none transition">
                        <option value="wali_murid">Orang Tua / Wali Murid</option>
                        <option value="dinas_pemerintah">Instansi / Dinas Pemerintah</option>
                        <option value="swasta_vendor">Perusahaan / Swasta / Vendor</option>
                        <option value="alumni">Alumni</option>
                        <option value="umum">Masyarakat Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Ingin Bertemu Dengan <span class="text-red-500">*</span>
                    </label>
                    <select name="meet_with" required class="w-full px-4 py-3 rounded-2xl border border-gray-300 text-sm focus:border-teal-600 focus:ring-4 focus:ring-teal-50 focus:outline-none transition">
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="wakil_kepala_sekolah">Wakil Kepala Sekolah</option>
                        <option value="guru">Guru / Wali Kelas</option>
                        <option value="tata_usaha">Tata Usaha (TU)</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Maksud &amp; Tujuan Kunjungan <span class="text-red-500">*</span>
                </label>
                <textarea name="purpose" rows="3" required
                          placeholder="Jelaskan secara singkat keperluan atau hal yang ingin dikoordinasikan..."
                          class="w-full px-4 py-3 rounded-2xl border border-gray-300 text-sm focus:border-teal-600 focus:ring-4 focus:ring-teal-50 focus:outline-none transition"></textarea>
            </div>

            <input type="hidden" name="visit_date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="visit_time" value="{{ date('H:i') }}">

            <div class="pt-3">
                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white rounded-2xl font-bold text-base shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Data Kunjungan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
