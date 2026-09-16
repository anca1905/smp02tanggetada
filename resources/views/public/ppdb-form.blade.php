@extends('layouts.public')

@section('title', 'Formulir Pendaftaran PPDB Online')
@section('header', 'Formulir Pendaftaran Siswa Baru')
@section('subheader', 'Mohon isi data dengan benar sesuai Ijazah dan Kartu Keluarga (KK)')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-700 transition">Beranda</a>
            <i class="fas fa-chevron-right text-xs text-gray-300"></i>
            <a href="{{ route('public.ppdb') }}" class="hover:text-blue-700 transition">PPDB Online</a>
            <i class="fas fa-chevron-right text-xs text-gray-300"></i>
            <span class="text-gray-800 font-semibold">Formulir Pendaftaran</span>
        </nav>

        {{-- Flash Success --}}
        @if (session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-2xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-green-800 mb-1">Pendaftaran Berhasil!</p>
                    <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    <a href="{{ route('public.ppdb') }}" class="inline-block mt-3 text-sm font-bold text-blue-700 hover:underline">
                        ← Kembali ke Halaman PPDB
                    </a>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">
                <p class="text-red-700 text-sm font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">
                <p class="font-bold text-red-800 text-sm mb-2">Terdapat kesalahan pada form:</p>
                <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- === FORM UTAMA === --}}
            <div class="lg:w-2/3">
                <form id="form-ppdb" action="{{ route('public.ppdb.store') }}" method="POST" enctype="multipart/form-data"
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @csrf

                    {{-- Header Form --}}
                    <div class="bg-blue-900 px-8 py-5 text-white">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-yellow-400"></i>
                            Formulir Pendaftaran Siswa Baru
                        </h2>
                        <p class="text-blue-200 text-sm mt-1">Isi semua field yang bertanda <span class="text-yellow-400 font-bold">*</span> wajib diisi</p>
                    </div>

                    <div class="p-8 space-y-8">

                        {{-- Seksi 1: Data Pribadi --}}
                        <div>
                            <h3 class="text-base font-bold text-blue-900 border-b-2 border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-900 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
                                Data Pribadi Calon Siswa
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 uppercase text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('nama_lengkap') border-red-500 @enderror"
                                        placeholder="Sesuai Ijazah">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NISN <span class="text-red-500">*</span></label>
                                    <input type="text" name="nisn" value="{{ old('nisn') }}" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('nisn') border-red-500 @enderror"
                                        placeholder="10 Digit NISN" maxlength="20">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIK (No. KTP/KIA) <span class="text-red-500">*</span></label>
                                    <input type="text" name="nik" value="{{ old('nik') }}" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('nik') border-red-500 @enderror"
                                        placeholder="16 Digit NIK" maxlength="16">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Kartu Keluarga (KK)</label>
                                    <input type="text" name="no_kk" value="{{ old('no_kk') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('no_kk') border-red-500 @enderror"
                                        placeholder="16 Digit No KK" maxlength="20">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <select name="jenis_kelamin" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white @error('jenis_kelamin') border-red-500 @enderror">
                                        <option value="">-- Pilih --</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 uppercase text-sm focus:outline-none focus:ring-2 focus:ring-blue-900"
                                        placeholder="Kota kelahiran">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Agama & Kepercayaan</label>
                                    <select name="agama"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="Islam" {{ old('agama') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen/Protestan" {{ old('agama') === 'Kristen/Protestan' ? 'selected' : '' }}>Kristen/Protestan</option>
                                        <option value="Katholik" {{ old('agama') === 'Katholik' ? 'selected' : '' }}>Katholik</option>
                                        <option value="Hindu" {{ old('agama') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Budha" {{ old('agama') === 'Budha' ? 'selected' : '' }}>Budha</option>
                                        <option value="Khonghucu" {{ old('agama') === 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                                        <option value="Kepercayaan" {{ old('agama') === 'Kepercayaan' ? 'selected' : '' }}>Kepercayaan Kepada Tuhan YME</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tempat Tinggal</label>
                                    <select name="tempat_tinggal"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
                                        <option value="">-- Pilih --</option>
                                        <option value="Bersama orang tua" {{ old('tempat_tinggal') === 'Bersama orang tua' ? 'selected' : '' }}>Bersama orang tua</option>
                                        <option value="Wali" {{ old('tempat_tinggal') === 'Wali' ? 'selected' : '' }}>Wali</option>
                                        <option value="Kos" {{ old('tempat_tinggal') === 'Kos' ? 'selected' : '' }}>Kos</option>
                                        <option value="Asrama" {{ old('tempat_tinggal') === 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                        <option value="Panti Asuhan" {{ old('tempat_tinggal') === 'Panti Asuhan' ? 'selected' : '' }}>Panti Asuhan</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Jalan (Sesuai KK)</label>
                                    <textarea name="alamat" rows="2"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 uppercase text-sm focus:outline-none focus:ring-2 focus:ring-blue-900"
                                        placeholder="Nama Jalan, Gg, Blok, No. Rumah">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Seksi 2: Sekolah Asal --}}
                        <div>
                            <h3 class="text-base font-bold text-blue-900 border-b-2 border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-900 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                                Data Sekolah Asal
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama SD/MI Asal <span class="text-red-500">*</span></label>
                                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 uppercase text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('asal_sekolah') border-red-500 @enderror"
                                        placeholder="SD NEGERI ...">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Lulus</label>
                                    <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', date('Y')) }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900"
                                        min="2000" max="{{ date('Y') + 1 }}">
                                </div>
                            </div>
                        </div>

                        {{-- Seksi 3: Data Orang Tua --}}
                        <div>
                            <h3 class="text-base font-bold text-blue-900 border-b-2 border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-900 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                                Data Orang Tua / Wali
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Ayah --}}
                                <div class="md:col-span-2 border-b border-gray-100 pb-2">
                                    <h4 class="font-bold text-gray-800 text-sm">Data Ayah Kandung</h4>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 uppercase text-sm focus:outline-none focus:ring-2 focus:ring-blue-900"
                                        placeholder="Nama lengkap ayah">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan Ayah</label>
                                    <select name="pekerjaan_ayah" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
                                        <option value="">-- Pilih Pekerjaan --</option>
                                        <option value="Tidak bekerja">Tidak bekerja</option>
                                        <option value="Nelayan">Nelayan</option>
                                        <option value="Petani">Petani</option>
                                        <option value="Peternak">Peternak</option>
                                        <option value="PNS/TNI/POLRI">PNS/TNI/POLRI</option>
                                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                                        <option value="Pedagang Kecil">Pedagang Kecil</option>
                                        <option value="Pedagang Besar">Pedagang Besar</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Wirausaha">Wirausaha</option>
                                        <option value="Buruh">Buruh</option>
                                        <option value="Pensiunan">Pensiunan</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Penghasilan Ayah</label>
                                    <select name="penghasilan_ayah" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
                                        <option value="">-- Pilih Penghasilan --</option>
                                        <option value="Kurang dari Rp 500.000">Kurang dari Rp 500.000</option>
                                        <option value="Rp 500.000 - Rp 999.999">Rp 500.000 - Rp 999.999</option>
                                        <option value="Rp 1.000.000 - Rp 1.999.999">Rp 1.000.000 - Rp 1.999.999</option>
                                        <option value="Rp 2.000.000 - Rp 4.999.999">Rp 2.000.000 - Rp 4.999.999</option>
                                        <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                        <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                    </select>
                                </div>

                                {{-- Ibu --}}
                                <div class="md:col-span-2 border-b border-gray-100 pb-2">
                                    <h4 class="font-bold text-gray-800 text-sm">Data Ibu Kandung</h4>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 uppercase text-sm focus:outline-none focus:ring-2 focus:ring-blue-900"
                                        placeholder="Nama lengkap ibu">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan Ibu</label>
                                    <select name="pekerjaan_ibu" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
                                        <option value="">-- Pilih Pekerjaan --</option>
                                        <option value="Tidak bekerja">Tidak bekerja</option>
                                        <option value="Nelayan">Nelayan</option>
                                        <option value="Petani">Petani</option>
                                        <option value="Peternak">Peternak</option>
                                        <option value="PNS/TNI/POLRI">PNS/TNI/POLRI</option>
                                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                                        <option value="Pedagang Kecil">Pedagang Kecil</option>
                                        <option value="Pedagang Besar">Pedagang Besar</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Wirausaha">Wirausaha</option>
                                        <option value="Buruh">Buruh</option>
                                        <option value="Pensiunan">Pensiunan</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Penghasilan Ibu</label>
                                    <select name="penghasilan_ibu" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
                                        <option value="">-- Pilih Penghasilan --</option>
                                        <option value="Kurang dari Rp 500.000">Kurang dari Rp 500.000</option>
                                        <option value="Rp 500.000 - Rp 999.999">Rp 500.000 - Rp 999.999</option>
                                        <option value="Rp 1.000.000 - Rp 1.999.999">Rp 1.000.000 - Rp 1.999.999</option>
                                        <option value="Rp 2.000.000 - Rp 4.999.999">Rp 2.000.000 - Rp 4.999.999</option>
                                        <option value="Rp 5.000.000 - Rp 20.000.000">Rp 5.000.000 - Rp 20.000.000</option>
                                        <option value="Lebih dari Rp 20.000.000">Lebih dari Rp 20.000.000</option>
                                    </select>
                                </div>

                                {{-- Kontak --}}
                                <div class="md:col-span-2 border-b border-gray-100 pb-2">
                                    <h4 class="font-bold text-gray-800 text-sm">Kontak (Wajib)</h4>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WA / Telepon Aktif <span class="text-red-500">*</span></label>
                                    <input type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('no_hp') border-red-500 @enderror"
                                        placeholder="Contoh: 0812xxxx">
                                    <p class="text-xs text-gray-500 mt-1.5"><i class="fas fa-info-circle mr-1 text-blue-400"></i> Nomor ini akan digunakan untuk informasi kelulusan.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Seksi 4: Upload Berkas Persyaratan --}}
                        <div>
                            <h3 class="text-base font-bold text-blue-900 border-b-2 border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-900 text-white rounded-full flex items-center justify-center text-xs font-bold">4</div>
                                Upload Berkas Persyaratan (Wajib)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg flex gap-3 text-sm text-yellow-800 mb-2">
                                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                                        <p>Pastikan format file sesuai (JPG/PNG untuk foto, PDF untuk lainnya) dan ukuran masing-masing file maksimal <strong>2MB</strong>.</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pas Photo 3x4 <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_pas_photo" required accept=".jpg,.jpeg,.png"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_pas_photo') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scan Ijazah / SKL <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_ijazah" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_ijazah') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scan Transkrip Nilai <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_transkrip" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_transkrip') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sertifikat TKA <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_tka" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_tka') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scan Akta Kelahiran <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_akta" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_akta') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scan Kartu Keluarga (KK) <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_kk" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_kk') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scan KTP Ayah <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_ktp_ayah" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_ktp_ayah') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Scan KTP Ibu <span class="text-red-500">*</span></label>
                                    <input type="file" name="doc_ktp_ibu" required accept=".pdf"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 @error('doc_ktp_ibu') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF</p>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit" id="btn-submit"
                                class="w-full bg-blue-900 text-white font-bold py-4 rounded-xl hover:bg-blue-800 active:scale-95 transition-all shadow-lg flex items-center justify-center gap-2 text-base">
                                <i class="fas fa-paper-plane"></i>
                                SIMPAN DATA PENDAFTARAN
                            </button>
                            <p class="text-xs text-center text-gray-400 mt-3">
                                Dengan mengirim form ini, Anda menyatakan data yang diisi adalah benar dan dapat dipertanggungjawabkan.
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            {{-- === SIDEBAR INFO === --}}
            <div class="lg:w-1/3 space-y-5">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-headset text-blue-600"></i> Butuh Bantuan?
                    </h4>
                    <p class="text-sm text-gray-500 mb-4">Jika mengalami kesulitan mengisi formulir, silakan hubungi panitia PPDB:</p>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fab fa-whatsapp text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700">Panitia PPDB</p>
                                <p class="text-green-600 text-xs">Hubungi via WhatsApp</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-clipboard-list text-blue-600"></i> Syarat Daftar Ulang
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-2.5">
                        @foreach (['Bukti Pendaftaran Online (Cetak)', 'Fotokopi Ijazah / SKL (Legalisir)', 'Fotokopi Kartu Keluarga (KK)', 'Fotokopi Akta Kelahiran', 'Fotokopi KIP/KPS (Jika ada)', 'Pas Foto 3×4 (2 Lembar)', 'Map Snelhecter'] as $syarat)
                            <li class="flex items-start gap-2.5">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                {{ $syarat }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-blue-900 text-white rounded-2xl shadow-sm p-6">
                    <h4 class="font-bold mb-4 flex items-center gap-2 border-b border-blue-800 pb-3">
                        <i class="fas fa-calendar-alt text-yellow-400"></i> Jadwal PPDB
                    </h4>
                    <ul class="text-sm space-y-3">
                        @foreach ([['Pendaftaran', '1 – 30 Juni'], ['Verifikasi Berkas', '1 – 5 Juli'], ['Pengumuman', '7 Juli'], ['Daftar Ulang', '8 – 10 Juli']] as [$label, $tanggal])
                            <li class="flex justify-between items-center py-2 border-b border-blue-800 last:border-0">
                                <span class="text-blue-200">{{ $label }}</span>
                                <span class="font-bold text-yellow-400">{{ $tanggal }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('public.ppdb') }}"
                    class="flex items-center gap-2 text-sm text-blue-700 hover:text-blue-900 transition font-semibold">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman PPDB
                </a>
            </div>
        </div>
    </div>
@endsection
