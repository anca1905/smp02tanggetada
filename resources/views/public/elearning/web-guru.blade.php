@extends('layouts.public')

@section('title', 'Web Guru - E-Learning')
@section('header', 'Web Guru')
@section('subheader', 'Portal pembelajaran untuk guru dan siswa')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">

    <nav class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-10">
        <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">E-Learning</span>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-blue-900 font-semibold">Web Guru</span>
    </nav>

    <div class="bg-gradient-to-br from-blue-600 to-blue-900 text-white rounded-3xl p-14 shadow-xl">
        <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-chalkboard-teacher text-4xl"></i>
        </div>
        <h2 class="text-3xl font-bold mb-4">Web Guru</h2>
        <p class="text-blue-200 text-lg mb-8 max-w-md mx-auto">
            Portal e-learning khusus untuk guru dalam mengelola materi, tugas, dan penilaian siswa.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 bg-white text-blue-900 font-bold px-8 py-3 rounded-xl hover:bg-blue-50 transition shadow">
                <i class="fas fa-sign-in-alt"></i> Login Portal Guru
            </a>
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-book-open"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Materi Pelajaran</h4>
            <p class="text-sm text-gray-500">Upload dan kelola materi ajar untuk setiap kelas.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-green-100 text-green-700 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-tasks"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Penugasan</h4>
            <p class="text-sm text-gray-500">Buat dan nilai tugas siswa secara digital.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="w-10 h-10 bg-purple-100 text-purple-700 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h4 class="font-bold text-gray-800 mb-2">Rekap Presensi</h4>
            <p class="text-sm text-gray-500">Pantau kehadiran dan perkembangan siswa.</p>
        </div>
    </div>
</div>
@endsection
