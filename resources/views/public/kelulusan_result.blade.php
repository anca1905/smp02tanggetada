@extends('layouts.public')

@section('title', 'Hasil Kelulusan - ' . $graduation->student_name)
@section('header', 'Hasil Pengumuman Kelulusan Siswa')
@section('subheader', 'Informasi Resmi Kelulusan & Unduh Surat Keterangan Lulus (SKL)')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- STATUS BANNER --}}
        @if($graduation->status === 'lulus')
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-green-700 p-8 text-white text-center">
                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-4xl mx-auto mb-3 shadow">
                    <i class="fas fa-award text-yellow-300"></i>
                </div>
                <span class="px-3.5 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-widest text-emerald-100">
                    Surat Keputusan Kelulusan
                </span>
                <h1 class="text-3xl sm:text-4xl font-black mt-2 tracking-wide">
                    SELAMAT, ANDA LULUS!
                </h1>
                <p class="text-emerald-100 text-sm mt-1 max-w-md mx-auto">
                    Dari Satuan Pendidikan SMP Negeri 02 Tanggetada Tahun Ajaran {{ date('Y')-1 }}/{{ date('Y') }}.
                </p>
            </div>
        @elseif($graduation->status === 'ditunda')
            <div class="bg-gradient-to-r from-amber-600 to-orange-700 p-8 text-white text-center">
                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-4xl mx-auto mb-3">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">STATUS KELULUSAN DITUNDA</h1>
                <p class="text-amber-100 text-sm mt-1 max-w-md mx-auto">
                    Mohon segera menghubungi pihak sekolah / Wali Kelas terkait penyelesaian administrasi atau evaluasi akhir.
                </p>
            </div>
        @else
            <div class="bg-gradient-to-r from-red-600 to-rose-700 p-8 text-white text-center">
                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-4xl mx-auto mb-3">
                    <i class="fas fa-times"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">TIDAK LULUS</h1>
                <p class="text-red-100 text-sm mt-1 max-w-md mx-auto">
                    Silakan berkonsultasi dengan pihak sekolah mengenai opsi program pembinaan lanjutan.
                </p>
            </div>
        @endif

        {{-- BIODATA DETAIL --}}
        <div class="p-6 sm:p-8 space-y-6">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Data Identitas Siswa</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-100 text-sm">
                    <div>
                        <span class="text-gray-500 text-xs block">Nama Lengkap:</span>
                        <strong class="text-gray-900 text-base">{{ $graduation->student_name }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">Kelas / Rombel:</span>
                        <strong class="text-gray-900">{{ $graduation->classroom_name ?: ($graduation->student->classroom->name ?? 'IX') }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">Nomor Induk Siswa (NIS):</span>
                        <span class="font-mono font-bold text-gray-800">{{ $graduation->nis }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">NISN:</span>
                        <span class="font-mono font-bold text-gray-800">{{ $graduation->nisn ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">No. Surat SKL Resmi:</span>
                        <span class="font-mono text-xs font-semibold text-blue-700">{{ $graduation->letter_number ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs block">Rata-rata Nilai Ujian:</span>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                            {{ number_format((float)$graduation->average_score, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            @if($graduation->status === 'lulus')
                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('public.kelulusan.skl.download', $graduation) }}"
                       class="flex-1 py-4 px-6 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white rounded-2xl font-bold text-base shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                        <i class="fas fa-file-pdf text-xl"></i> Unduh Surat Keterangan Lulus (SKL) PDF
                    </a>
                    <a href="{{ route('public.kelulusan') }}"
                       class="px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-semibold text-sm transition flex items-center justify-center">
                        Cek Siswa Lain
                    </a>
                </div>
                <p class="text-[11px] text-gray-400 text-center">
                    * Dokumen PDF telah ditandatangani secara digital dan dilengkapi kode unik verifikasi keaslian.
                </p>
            @else
                <div class="pt-2">
                    <a href="{{ route('public.kelulusan') }}"
                       class="w-full py-3 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-semibold text-sm transition flex items-center justify-center">
                        Kembali ke Halaman Cek
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
