@extends('layouts.public')

@section('title', 'E-Dokumen - E-Learning')
@section('header', 'E-Dokumen')
@section('subheader', 'Dokumen-dokumen resmi dan penting dari sekolah')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">E-Learning</span>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">E-Dokumen</span>
    </nav>

    <div class="text-center mb-12">
        <div class="w-20 h-20 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-folder-open text-3xl"></i>
        </div>
        <h2 class="text-4xl font-bold text-gray-900">E-Dokumen</h2>
        <p class="text-gray-500 mt-3">Kumpulan dokumen resmi sekolah yang dapat diunduh</p>
    </div>

    {{-- Kategori Dokumen --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Dokumen Administrasi</h3>
                    <p class="text-xs text-gray-400">Form, surat, dan berkas administrasi</p>
                </div>
            </div>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2 text-gray-500 italic py-2 border-b border-dashed">
                    <i class="fas fa-info-circle text-blue-400"></i>
                    Dokumen akan tersedia segera
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-100 text-green-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Kurikulum &amp; Program</h3>
                    <p class="text-xs text-gray-400">Silabus, RPP, dan program sekolah</p>
                </div>
            </div>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2 text-gray-500 italic py-2 border-b border-dashed">
                    <i class="fas fa-info-circle text-green-400"></i>
                    Dokumen akan tersedia segera
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Prestasi &amp; Sertifikat</h3>
                    <p class="text-xs text-gray-400">Piagam, sertifikat akreditasi</p>
                </div>
            </div>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2 text-gray-500 italic py-2 border-b border-dashed">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                    Dokumen akan tersedia segera
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gavel text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Peraturan &amp; Kebijakan</h3>
                    <p class="text-xs text-gray-400">Tata tertib, SK, dan kebijakan sekolah</p>
                </div>
            </div>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2 text-gray-500 italic py-2 border-b border-dashed">
                    <i class="fas fa-info-circle text-purple-400"></i>
                    Dokumen akan tersedia segera
                </li>
            </ul>
        </div>
    </div>

    <div class="mt-10 bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
        <i class="fas fa-envelope text-blue-600 text-2xl mb-3"></i>
        <p class="text-blue-800 font-semibold">Butuh dokumen tertentu?</p>
        <p class="text-blue-600 text-sm mt-1">Hubungi kami melalui halaman kontak atau datang langsung ke sekolah.</p>
        <a href="{{ route('public.kontak') }}" class="inline-flex items-center gap-2 mt-4 bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
            <i class="fas fa-envelope"></i> Hubungi Kami
        </a>
    </div>
</div>
@endsection
