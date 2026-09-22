@extends('layouts.student')

@section('title', 'Ruang Belajar')

@section('content')
    <div class="space-y-6">
        {{-- Welcome Banner --}}
        <div
            class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg flex justify-between items-center relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-xl font-bold mb-1 flex items-center gap-2">
                    Halo, {{ $student->student_name }}!
                    <svg class="w-6 h-6 inline-block text-amber-300 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.5 2c-.8 0-1.5.7-1.5 1.5v6.5h-1V4.5C10 3.7 9.3 3 8.5 3S7 3.7 7 4.5v6.5h-1V6.5C6 5.7 5.3 5 4.5 5S3 5.7 3 6.5v8C3 18.6 6.4 22 10.5 22h3c4.1 0 7.5-3.4 7.5-7.5V11c0-.8-.7-1.5-1.5-1.5s-1.5.7-1.5 1.5v1h-1V8.5c0-.8-.7-1.5-1.5-1.5s-1.5.7-1.5 1.5V10h-1V3.5c0-.8-.7-1.5-1.5-1.5z"/></svg>
                </h2>
                <p class="text-indigo-100 mt-2">Selamat datang di kelas <span
                        class="font-bold bg-white/20 px-2 py-0.5 rounded">{{ $student->classroom->name ?? 'Belum Ada Kelas' }}</span>.
                </p>
                <p class="text-sm text-indigo-200 mt-1">Siap untuk belajar hal baru hari ini?</p>
            </div>
            <div class="hidden md:block text-6xl opacity-10 absolute right-0 bottom-[-10px]">
                <i class="fas fa-rocket"></i>
            </div>
        </div>

        {{-- Grid Mapel --}}
        <div class="grid grid-cols-1 gap-4">
            @forelse($myCourses as $subjectName => $schedules)
                @php $data = $schedules->first(); @endphp

                <a href="{{ route('student.lms.show', $data->id) }}" class="block group">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300 h-full flex flex-col active:scale-[0.98]">
                        {{-- Cover --}}
                        <div class="h-32 bg-gray-200 relative">
                            <img src="https://source.unsplash.com/random/800x600/?book,library,{{ $loop->index }}"
                                class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition"
                                alt="Cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-3 left-4 text-white">
                                <h3 class="font-bold text-lg leading-tight">{{ $data->subject->name }}</h3>
                                <p class="text-xs opacity-90">{{ $data->subject->code }}</p>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($data->teacher->name) }}&background=random"
                                        class="w-6 h-6 rounded-full mr-2" alt="Guru">
                                    <span class="line-clamp-1">{{ $data->teacher->name }}</span>
                                </div>

                                {{-- Jadwal Mini --}}
                                <div class="space-y-1">
                                    @foreach ($schedules as $sch)
                                        <span
                                            class="inline-block bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded border border-blue-100">
                                            {{ $sch->day }},
                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-xs text-gray-400 font-medium">Masuk Kelas</span>
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div
                        class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-400">
                        <i class="fas fa-books text-3xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold text-lg">Tidak ada mata pelajaran</h3>
                    <p class="text-gray-500 text-sm mt-1">Jadwal pelajaran belum tersedia untuk kelasmu.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
