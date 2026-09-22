@extends('layouts.public')

@section('title', 'Hubungi Kami')
@section('header', 'Kontak Kami')
@section('subheader', 'Kami siap melayani pertanyaan dan kebutuhan informasi Anda')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <div class="space-y-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Kantor Pusat</h3>
                    <p class="text-gray-600 mb-6">
                        Silakan kunjungi kantor kami pada jam kerja untuk keperluan administrasi atau informasi lebih lanjut
                        mengenai pendaftaran.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <div class="flex items-center justify-center h-10 w-10 rounded-md bg-blue-100 text-blue-900">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Alamat</h4>
                                <p class="mt-1 text-gray-500">Jl. Jendral Sudirman No. 123<br>Kota Jambi, Indonesia 36123
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center h-10 w-10 rounded-md bg-blue-100 text-blue-900">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Telepon</h4>
                                <p class="mt-1 text-gray-500">(0741) 123-4567<br>Senin - Jumat, 08:00 - 16:00</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="shrink-0">
                                <div
                                    class="flex items-center justify-center h-10 w-10 rounded-md bg-blue-100 text-blue-900">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Email</h4>
                                <p class="mt-1 text-gray-500">info@sekolah.sch.id<br>admissions@sekolah.sch.id</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-200 rounded-xl overflow-hidden h-64 w-full relative group">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=2074&auto=format&fit=crop"
                        class="w-full h-full object-cover opacity-60" alt="Map Placeholder">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <a href="#"
                            class="bg-white px-6 py-3 rounded-full shadow-lg text-blue-900 font-bold hover:bg-blue-50 transition transform hover:scale-105">
                            <i class="fas fa-map-marked-alt mr-2"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h3>

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('public.kontak.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 bg-gray-50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                        <input type="text" name="subject" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                        <textarea name="message" rows="4" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 bg-gray-50"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-900 text-white font-bold py-3 rounded-xl hover:bg-blue-800 transition shadow-md">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
