@extends('layouts.public')

@section('title', 'Verifikasi Keaslian Dokumen SKL - SMP 02')
@section('header', 'Verifikasi Keaslian Surat Keterangan Lulus')
@section('subheader', 'Sistem Verifikasi Digital Keabsahan Dokumen Kelulusan SMP Negeri 02 Tanggetada')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden text-center p-8 space-y-6">

        <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-4xl mx-auto border-4 border-emerald-100">
            <i class="fas fa-check-double"></i>
        </div>

        <div>
            <span class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                Terverifikasi Resmi
            </span>
            <h2 class="text-2xl font-black text-gray-900 mt-2">DOKUMEN INI ASLI & VALID</h2>
            <p class="text-xs text-gray-500 mt-1">
                Tercatat pada basis data Sistem Informasi Manajemen Sekolah (SIMS) SMP Negeri 02 Tanggetada.
            </p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 text-left text-xs space-y-3">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nama Lengkap:</span>
                <strong class="text-gray-900 text-sm">{{ $graduation->student_name }}</strong>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">NIS / NISN:</span>
                <span class="font-mono font-bold text-gray-800">{{ $graduation->nis }} / {{ $graduation->nisn ?: '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nomor Surat SKL:</span>
                <span class="font-mono text-blue-700 font-semibold">{{ $graduation->letter_number ?: '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Status Kelulusan:</span>
                <span class="font-bold uppercase text-emerald-700">{{ $graduation->status }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nilai Rata-rata Ujian:</span>
                <span class="font-bold text-gray-900">{{ number_format((float)$graduation->average_score, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Kode Unik Verifikasi:</span>
                <span class="font-mono font-bold text-gray-800">{{ $graduation->verification_code }}</span>
            </div>
        </div>

        <div class="pt-2">
            <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition">
                Kembali ke Beranda Sekolah
            </a>
        </div>
    </div>
</div>
@endsection
