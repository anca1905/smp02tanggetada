@extends('layouts.public')

@section('title', 'Profil Sekolah')
@section('header', 'Profil Sekolah')
@section('subheader', 'Mengenal Lebih Dekat Sejarah, Visi Misi, dan Fasilitas Kami')

@section('content')

    <div class="bg-white border-b border-gray-200 sticky top-20 z-40 hidden md:block">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex space-x-8">
                <a href="#tentang"
                    class="py-4 text-sm font-medium text-gray-600 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-900 transition">Tentang
                    Kami</a>
                <a href="#visimisi"
                    class="py-4 text-sm font-medium text-gray-600 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-900 transition">Visi
                    & Misi</a>
                <a href="#struktur"
                    class="py-4 text-sm font-medium text-gray-600 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-900 transition">Struktur
                    Organisasi</a>
                <a href="#fasilitas"
                    class="py-4 text-sm font-medium text-gray-600 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-900 transition">Fasilitas</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-20">

        <section id="tentang" class="scroll-mt-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block p-2 px-4 rounded-full bg-blue-100 text-blue-800 text-sm font-bold mb-4">Sejarah
                        Singkat</div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Membangun Generasi Unggul Sejak 1990</h2>
                    <div class="prose text-gray-600 leading-relaxed space-y-4">
                        <p>
                            Berawal dari semangat untuk mencerdaskan kehidupan bangsa, sekolah ini didirikan dengan
                            fasilitas sederhana namun tekad yang kuat. Seiring berjalannya waktu, kami terus bertransformasi
                            mengikuti perkembangan zaman.
                        </p>
                        <p>
                            Kini, dengan penerapan sistem manajemen berbasis digital (SIMS), kami berkomitmen untuk
                            memberikan pelayanan pendidikan yang transparan, akuntabel, dan modern bagi seluruh siswa dan
                            orang tua.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-blue-100 rounded-xl transform rotate-3"></div>
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop"
                        class="relative rounded-xl shadow-lg w-full" alt="Gedung Sekolah">
                </div>
            </div>
        </section>

        <section id="visimisi" class="scroll-mt-32 bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-16">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Visi & Misi</h2>
                    <p class="text-gray-600 mt-2">Arah dan tujuan pendidikan kami.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border-t-4 border-blue-600">
                        <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-eye text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Visi</h3>
                        <p class="text-lg text-gray-700 italic">
                            "{!! nl2br(e($site_settings['school_visi'] ?? 'Menjadi sekolah unggul, berkarakter, dan berwawasan global.')) !!}"
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border-t-4 border-yellow-500">
                        <div
                            class="w-12 h-12 bg-yellow-100 text-yellow-700 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-list-ul text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Misi</h3>
                        <div class="prose text-gray-700">
                            {!! nl2br(
                                e($site_settings['school_misi'] ?? "- Menyelenggarakan pendidikan berkualitas.\n- Mengembangkan potensi siswa."),
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="struktur" class="scroll-mt-32 text-center">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-900">Struktur Organisasi</h2>
                <p class="text-gray-600 mt-2">Bagan kepengurusan sekolah tahun ajaran ini.</p>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 inline-block">
                @if (isset($site_settings['struktur_img']))
                    <img src="{{ asset('storage/' . $site_settings['struktur_img']) }}" alt="Struktur Organisasi"
                        class="max-w-full h-auto rounded-lg">
                @else
                    <div
                        class="w-full max-w-4xl h-96 bg-gray-100 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-sitemap text-4xl mb-3"></i>
                            <p>Bagan Struktur Organisasi belum diunggah.</p>
                            <p class="text-xs">Silakan upload melalui Admin Panel.</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section id="fasilitas" class="scroll-mt-32">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Fasilitas Sekolah</h2>
                    <p class="text-gray-600 mt-2">Sarana penunjang kegiatan belajar mengajar.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="group relative overflow-hidden rounded-xl shadow-md h-64">
                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                        alt="Kelas">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <h3 class="text-white font-bold text-lg">Ruang Kelas Full AC</h3>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-xl shadow-md h-64">
                    <img src="https://images.unsplash.com/photo-1564981797816-1043664bf78d?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                        alt="Lab Komputer">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <h3 class="text-white font-bold text-lg">Laboratorium Komputer</h3>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-xl shadow-md h-64">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                        alt="Perpus">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <h3 class="text-white font-bold text-lg">Perpustakaan Digital</h3>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-xl shadow-md h-64">
                    <img src="https://images.unsplash.com/photo-1576267423445-b2e0074d68a4?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                        alt="Lapangan">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <h3 class="text-white font-bold text-lg">Lapangan Olahraga</h3>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-xl shadow-md h-64 md:col-span-2">
                    <img src="https://images.unsplash.com/photo-1598981493990-672535057a7d?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                        alt="Aula">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-6">
                        <h3 class="text-white font-bold text-lg">Aula Serbaguna</h3>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
