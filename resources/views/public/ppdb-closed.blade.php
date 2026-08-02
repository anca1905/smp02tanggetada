@extends('layouts.public')

@section('title', 'PPDB Ditutup')
@section('header', 'Pendaftaran Ditutup')
@section('subheader', 'Pendaftaran Peserta Didik Baru saat ini sedang ditutup')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-24 text-center">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lock text-red-500 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-3">Pendaftaran Sedang Ditutup</h2>
            <p class="text-gray-500 mb-8 leading-relaxed">
                Penerimaan Peserta Didik Baru (PPDB) saat ini belum dibuka atau sudah ditutup.
                Silakan hubungi sekolah atau pantau terus halaman ini untuk informasi lebih lanjut.
            </p>
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 text-white rounded-xl font-semibold hover:bg-blue-800 transition">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
