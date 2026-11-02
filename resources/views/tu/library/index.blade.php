@extends('layouts.app')

@section('title', 'Perpustakaan & Buku BOS')

@section('content')
<div class="space-y-6">

    {{-- Top Header / Hero --}}
    <div class="bg-gradient-to-r from-amber-700 via-amber-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-amber-200 text-xs font-medium mb-2">
                <i class="fas fa-book-reader"></i> Perpustakaan & Sirkulasi Buku BOS
            </div>
            <h1 class="text-2xl font-bold">Sirkulasi & Katalog Buku Perpustakaan</h1>
            <p class="text-amber-100 text-sm mt-1">Kelola sirkulasi buku teks BOS per rombel/kelas, buku umum, serta peminjaman mandiri siswa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openModal('modal-add-loan')" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Pinjam Buku
            </button>
            <button onclick="openModal('modal-bulk-loan')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm shadow-md hover:shadow-lg transition flex items-center gap-2">
                <i class="fas fa-users-cog"></i> Distribusi Rombel (Buku BOS)
            </button>
            <button onclick="openModal('modal-add-book')" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-medium text-sm transition flex items-center gap-2">
                <i class="fas fa-book"></i> Tambah Judul Buku
            </button>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
            <div class="font-semibold mb-1 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-600"></i> Mohon periksa kembali formulir:
            </div>
            <ul class="list-disc list-inside text-sm space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-bookmark"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Judul Buku</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_titles'] }}</p>
                <p class="text-[11px] text-amber-600 font-medium">Katalog Terdaftar</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Eksemplar</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_stock']) }}</p>
                <p class="text-[11px] text-blue-600 font-medium">Fisik Buku di Sekolah</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-book-reader"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Sedang Dipinjam</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['active_loans'] }}</p>
                <p class="text-[11px] text-emerald-600 font-medium">Buku di Tangan Siswa</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Lewat Batas (Overdue)</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['overdue_loans'] }}</p>
                <p class="text-[11px] text-red-600 font-medium">Perlu Pengingat WA</p>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex border-b border-gray-200 gap-4">
        <a href="{{ route('tu.library.index', ['tab' => 'loans']) }}"
           class="pb-3 px-2 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'loans' ? 'border-amber-600 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fas fa-clipboard-list"></i> Sirkulasi Peminjaman & Pengembalian
            <span class="px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-800 font-bold">{{ $stats['active_loans'] }}</span>
        </a>
        <a href="{{ route('tu.library.index', ['tab' => 'books']) }}"
           class="pb-3 px-2 font-semibold text-sm border-b-2 transition flex items-center gap-2 {{ $activeTab === 'books' ? 'border-amber-600 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <i class="fas fa-books"></i> Katalog Buku & Buku BOS
            <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700 font-bold">{{ $stats['total_titles'] }}</span>
        </a>
    </div>

    {{-- TAB 1: SIRKULASI PEMINJAMAN --}}
    @if($activeTab === 'loans')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            {{-- Filter and Search --}}
            <form method="GET" action="{{ route('tu.library.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="hidden" name="tab" value="loans">
                
                <div class="sm:col-span-2 relative">
                    <input type="text" name="loan_search" value="{{ request('loan_search') }}"
                           placeholder="Cari no pinjam, nama siswa, NIS, atau judul buku..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                </div>

                <div>
                    <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">Semua Status</option>
                        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Terlambat (Overdue)</option>
                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                        <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Hilang</option>
                        <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <select name="classroom_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">Semua Kelas</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-medium transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['loan_search', 'status', 'classroom_id']))
                        <a href="{{ route('tu.library.index', ['tab' => 'loans']) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table Loans --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-y border-gray-200">
                        <tr>
                            <th class="py-3.5 px-4">Kode & Tgl Pinjam</th>
                            <th class="py-3.5 px-4">Siswa & Kelas</th>
                            <th class="py-3.5 px-4">Buku Dipinjam</th>
                            <th class="py-3.5 px-4">Batas Waktu</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Pengingat WA</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-900 font-mono text-xs">{{ $loan->loan_code }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $loan->loan_date->format('d M Y') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-900">{{ $loan->student?->student_name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 font-mono">NIS: {{ $loan->student?->nis }} &bull; {{ $loan->student?->classroom?->name ?? 'Tanpa Rombel' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-900 line-clamp-1" title="{{ $loan->book?->title }}">{{ $loan->book?->title ?? '-' }}</div>
                                    <div class="text-[11px] text-amber-700 font-mono">{{ $loan->book?->book_code }} ({{ $loan->book?->category_label }})</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium {{ $loan->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                                        {{ $loan->due_date->format('d M Y') }}
                                    </div>
                                    @if($loan->status === 'borrowed')
                                        @if($loan->isOverdue())
                                            <span class="text-[10px] text-red-600 font-semibold bg-red-100 px-1.5 py-0.5 rounded">Terlambat {{ Carbon\Carbon::today()->diffInDays($loan->due_date) }} Hari</span>
                                        @else
                                            <span class="text-[10px] text-emerald-600 font-medium">Sisa {{ Carbon\Carbon::today()->diffInDays($loan->due_date, false) }} hari</span>
                                        @endif
                                    @elseif($loan->return_date)
                                        <div class="text-[11px] text-gray-400">Kembali: {{ $loan->return_date->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($loan->status === 'borrowed')
                                        @if($loan->isOverdue())
                                            <span class="px-2.5 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold inline-flex items-center gap-1">
                                                <i class="fas fa-exclamation-triangle"></i> Terlambat
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-full bg-amber-100 text-amber-800 font-semibold inline-flex items-center gap-1">
                                                <i class="fas fa-clock"></i> Dipinjam
                                            </span>
                                        @endif
                                    @elseif($loan->status === 'returned')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-100 text-emerald-800 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-check"></i> Kembali
                                        </span>
                                    @elseif($loan->status === 'lost')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-slate-100 text-slate-800 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-times-circle"></i> Hilang
                                        </span>
                                    @elseif($loan->status === 'damaged')
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-heart-broken"></i> Rusak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @php
                                        $phone = $loan->student?->parent_phone ?: $loan->student?->phone_number;
                                        $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $phone ?? ''));
                                        $msg = "Yth. Orang Tua / Siswa {$loan->student?->student_name},\nKami dari Perpustakaan SMPN 2 Tanggetada menginformasikan peminjaman buku: '{$loan->book?->title}' (Kode: {$loan->book?->book_code}).\nBatas waktu pengembalian adalah tanggal {$loan->due_date->format('d M Y')}.\nMohon dapat segera dikembalikan ke ruang perpustakaan sekolah. Terima kasih.";
                                    @endphp
                                    @if($cleanPhone)
                                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($msg) }}" target="_blank"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200 transition"
                                           title="Kirim Pengingat WhatsApp ke Siswa/Ortu">
                                            <i class="fab fa-whatsapp text-emerald-600"></i> Kirim WA
                                        </a>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">No WA -</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($loan->status === 'borrowed')
                                            <button onclick="openReturnModal({{ $loan->id }}, '{{ addslashes($loan->book?->title ?? '') }}', '{{ addslashes($loan->student?->student_name ?? '') }}')"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition shadow-sm"
                                                    title="Proses Pengembalian">
                                                <i class="fas fa-undo"></i> Kembalikan
                                            </button>
                                        @endif
                                        <form method="POST" action="{{ route('tu.library.loans.destroy', $loan) }}" onsubmit="return confirm('Hapus riwayat peminjaman ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition" title="Hapus Riwayat">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-400">
                                    <i class="fas fa-book-reader text-3xl mb-2 text-gray-300"></i>
                                    <p>Belum ada catatan sirkulasi peminjaman buku.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $loans->links() }}
            </div>
        </div>
    @endif

    {{-- TAB 2: KATALOG BUKU --}}
    @if($activeTab === 'books')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            {{-- Filter and Search --}}
            <form method="GET" action="{{ route('tu.library.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="hidden" name="tab" value="books">
                
                <div class="sm:col-span-2 relative">
                    <input type="text" name="book_search" value="{{ request('book_search') }}"
                           placeholder="Cari judul buku, kode, penulis, atau ISBN..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                    <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                </div>

                <div>
                    <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">Semua Kategori</option>
                        <option value="buku_paket_bos" {{ request('category') === 'buku_paket_bos' ? 'selected' : '' }}>Buku Paket BOS</option>
                        <option value="referensi" {{ request('category') === 'referensi' ? 'selected' : '' }}>Buku Referensi</option>
                        <option value="fiksi" {{ request('category') === 'fiksi' ? 'selected' : '' }}>Fiksi / Cerita</option>
                        <option value="non_fiksi" {{ request('category') === 'non_fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                        <option value="majalah" {{ request('category') === 'majalah' ? 'selected' : '' }}>Majalah / Jurnal</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <select name="class_level" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="">Semua Kelas</option>
                        <option value="7" {{ request('class_level') === '7' ? 'selected' : '' }}>Kelas VII</option>
                        <option value="8" {{ request('class_level') === '8' ? 'selected' : '' }}>Kelas VIII</option>
                        <option value="9" {{ request('class_level') === '9' ? 'selected' : '' }}>Kelas IX</option>
                        <option value="semua" {{ request('class_level') === 'semua' ? 'selected' : '' }}>Semua Tingkat</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-medium transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['book_search', 'category', 'class_level']))
                        <a href="{{ route('tu.library.index', ['tab' => 'books']) }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table Books --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-y border-gray-200">
                        <tr>
                            <th class="py-3.5 px-4">Kode & Cover</th>
                            <th class="py-3.5 px-4">Judul & Pengarang</th>
                            <th class="py-3.5 px-4">Kategori & Kelas</th>
                            <th class="py-3.5 px-4 text-center">Stok Tersedia</th>
                            <th class="py-3.5 px-4">Lokasi Rak</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($books as $book)
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($book->cover_image)
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="w-10 h-14 object-cover rounded shadow-sm">
                                        @else
                                            <div class="w-10 h-14 rounded bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="px-2 py-0.5 rounded bg-gray-100 font-mono text-xs font-bold text-gray-800">{{ $book->book_code }}</span>
                                            @if($book->isbn)
                                                <div class="text-[10px] text-gray-400 mt-1">ISBN: {{ $book->isbn }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 max-w-xs">
                                    <div class="font-bold text-gray-900 leading-snug">{{ $book->title }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">Penulis: {{ $book->author ?: '-' }} &bull; {{ $book->publisher ?: '-' }} ({{ $book->publish_year ?: '-' }})</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 text-xs rounded-full font-medium inline-block {{ $book->category === 'buku_paket_bos' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $book->category_label }}
                                    </span>
                                    <div class="text-[11px] text-gray-500 mt-1">Jenjang: Kelas {{ $book->class_level }}</div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="font-bold text-base {{ $book->available_stock > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $book->available_stock }} <span class="text-xs font-normal text-gray-400">/ {{ $book->total_stock }}</span>
                                    </div>
                                    <div class="text-[10px] text-gray-400">{{ $book->active_loans_count }} dipinjam</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-gray-700">{{ $book->location_shelf ?: 'Rak Umum' }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditBookModal({{ json_encode($book) }})" class="p-1.5 text-blue-600 hover:text-blue-800 transition" title="Edit Buku">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('tu.library.books.destroy', $book) }}" onsubmit="return confirm('Hapus judul buku ini dari katalog?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition" title="Hapus Buku">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    <i class="fas fa-book-open text-3xl mb-2 text-gray-300"></i>
                                    <p>Belum ada buku terdaftar dalam katalog.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $books->links() }}
            </div>
        </div>
    @endif

</div>

{{-- MODAL 1: PINJAM BUKU INDIVIDUAL --}}
<div id="modal-add-loan" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative animate-in fade-in">
        <button onclick="closeModal('modal-add-loan')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Catat Peminjaman Buku</h3>
                <p class="text-xs text-gray-500">Peminjaman perseorangan untuk siswa</p>
            </div>
        </div>

        <form method="POST" action="{{ route('tu.library.loans.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Buku <span class="text-red-500">*</span></label>
                <select name="book_id" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Pilih Buku yang Tersedia --</option>
                    @foreach($availableBooks as $bk)
                        <option value="{{ $bk->id }}">{{ $bk->book_code }} - {{ $bk->title }} (Sisa: {{ $bk->available_stock }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Siswa Peminjam <span class="text-red-500">*</span></label>
                <select name="student_id" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Cari / Pilih Siswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->nis }} - {{ $st->student_name }} ({{ $st->classroom?->name ?? 'Kelas -' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pinjam <span class="text-red-500">*</span></label>
                    <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Batas Kembali (Due Date) <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+14 days')) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Petugas Perpustakaan / TU</label>
                <input type="text" name="officer_name" value="{{ auth()->user()?->name ?? 'Petugas TU' }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="notes" rows="2" placeholder="Catatan kondisi buku atau keperluan pinjam..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-add-loan')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow">Simpan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: DISTRIBUSI MASSAL BUKU PAKET BOS PER KELAS --}}
<div id="modal-bulk-loan" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative animate-in fade-in">
        <button onclick="closeModal('modal-bulk-loan')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Distribusi Buku Paket BOS Massal</h3>
                <p class="text-xs text-gray-500">Bagikan 1 judul buku ke seluruh siswa di rombel kelas sekaligus</p>
            </div>
        </div>

        <form method="POST" action="{{ route('tu.library.loans.bulk') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Buku Paket BOS <span class="text-red-500">*</span></label>
                <select name="book_id" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Buku Paket BOS --</option>
                    @foreach($availableBooks->where('category', 'buku_paket_bos') as $bk)
                        <option value="{{ $bk->id }}">{{ $bk->book_code }} - {{ $bk->title }} (Stok Tersedia: {{ $bk->available_stock }})</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-500 mt-1">Pastikan stok mencukupi untuk jumlah seluruh siswa pada rombel yang dipilih.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas / Rombel Sasaran <span class="text-red-500">*</span></label>
                <select name="classroom_id" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Distribusi <span class="text-red-500">*</span></label>
                    <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Batas Pengembalian (Akhir Smt/Thn) <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+180 days')) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan</label>
                <input type="text" name="notes" placeholder="Misal: Buku BOS Semester Ganjil TA 2026/2027"
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-bulk-loan')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow">Distribusikan Massal</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 3: TAMBAH JUDUL BUKU --}}
<div id="modal-add-book" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative my-8 animate-in fade-in">
        <button onclick="closeModal('modal-add-book')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Tambah Judul Buku Baru</h3>
                <p class="text-xs text-gray-500">Daftarkan buku teks BOS atau koleksi umum perpustakaan</p>
            </div>
        </div>

        <form method="POST" action="{{ route('tu.library.books.store') }}" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="book_code" placeholder="Misal: BOS-7-MAT" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="buku_paket_bos">Buku Paket BOS</option>
                        <option value="referensi">Buku Referensi</option>
                        <option value="fiksi">Fiksi / Cerita</option>
                        <option value="non_fiksi">Non-Fiksi</option>
                        <option value="majalah">Majalah / Jurnal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" name="title" placeholder="Judul lengkap buku..." required
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pengarang / Penulis</label>
                    <input type="text" name="author" placeholder="Nama penulis..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Penerbit</label>
                    <input type="text" name="publisher" placeholder="Kemendikbud / Erlangga..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Terbit</label>
                    <input type="text" name="publish_year" placeholder="2023"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tingkat Kelas <span class="text-red-500">*</span></label>
                    <select name="class_level" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="7">Kelas 7</option>
                        <option value="8">Kelas 8</option>
                        <option value="9">Kelas 9</option>
                        <option value="semua">Semua Kelas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jumlah Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="total_stock" min="1" value="30" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">ISBN</label>
                    <input type="text" name="isbn" placeholder="978-..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Rak</label>
                    <input type="text" name="location_shelf" placeholder="Rak BOS 7A / Lemari 2"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Foto Sampul (Cover Image)</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi / Sinopsis Ringkas</label>
                <textarea name="description" rows="2" placeholder="Uraian singkat buku..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-add-book')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 4: EDIT BUKU --}}
<div id="modal-edit-book" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative my-8 animate-in fade-in">
        <button onclick="closeModal('modal-edit-book')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Perbarui Data Buku</h3>
                <p class="text-xs text-gray-500" id="edit-book-label"></p>
            </div>
        </div>

        <form id="form-edit-book" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Buku <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-book-code" name="book_code" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select id="edit-category" name="category" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="buku_paket_bos">Buku Paket BOS</option>
                        <option value="referensi">Buku Referensi</option>
                        <option value="fiksi">Fiksi / Cerita</option>
                        <option value="non_fiksi">Non-Fiksi</option>
                        <option value="majalah">Majalah / Jurnal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" id="edit-title" name="title" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pengarang / Penulis</label>
                    <input type="text" id="edit-author" name="author"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Penerbit</label>
                    <input type="text" id="edit-publisher" name="publisher"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Terbit</label>
                    <input type="text" id="edit-publish-year" name="publish_year"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tingkat Kelas <span class="text-red-500">*</span></label>
                    <select id="edit-class-level" name="class_level" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="7">Kelas 7</option>
                        <option value="8">Kelas 8</option>
                        <option value="9">Kelas 9</option>
                        <option value="semua">Semua Kelas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Total Stok <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-total-stock" name="total_stock" min="1" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">ISBN</label>
                    <input type="text" id="edit-isbn" name="isbn"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Lokasi Rak</label>
                    <input type="text" id="edit-shelf" name="location_shelf"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Foto Sampul (Opsional)</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi / Sinopsis Ringkas</label>
                <textarea id="edit-description" name="description" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-edit-book')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 5: KEMBALIKAN BUKU --}}
<div id="modal-return-loan" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative animate-in fade-in">
        <button onclick="closeModal('modal-return-loan')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <i class="fas fa-undo"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Proses Pengembalian Buku</h3>
                <p class="text-xs text-gray-500" id="return-info-label"></p>
            </div>
        </div>

        <form id="form-return-loan" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Status Pengembalian <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="returned">Buku Dikembalikan (Kondisi Baik)</option>
                    <option value="damaged">Buku Rusak</option>
                    <option value="lost">Buku Hilang</option>
                </select>
                <p class="text-[11px] text-gray-500 mt-1">Jika status 'Dikembalikan', stok buku di perpustakaan otomatis bertambah kembali +1.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Denda / Ganti Rugi (Rp)</label>
                <input type="number" name="fine_amount" min="0" value="0"
                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="notes" rows="2" placeholder="Kondisi halaman, penggantian buku hilang, dll..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modal-return-loan')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow">Konfirmasi Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
    }

    function openReturnModal(loanId, bookTitle, studentName) {
        document.getElementById('return-info-label').innerText = studentName + ' - ' + bookTitle;
        document.getElementById('form-return-loan').action = '/tu/library/loans/' + loanId + '/return';
        openModal('modal-return-loan');
    }

    function openEditBookModal(book) {
        document.getElementById('edit-book-label').innerText = book.book_code + ' - ' + book.title;
        document.getElementById('form-edit-book').action = '/tu/library/books/' + book.id;
        document.getElementById('edit-book-code').value = book.book_code || '';
        document.getElementById('edit-title').value = book.title || '';
        document.getElementById('edit-category').value = book.category || 'buku_paket_bos';
        document.getElementById('edit-class-level').value = book.class_level || '7';
        document.getElementById('edit-author').value = book.author || '';
        document.getElementById('edit-publisher').value = book.publisher || '';
        document.getElementById('edit-publish-year').value = book.publish_year || '';
        document.getElementById('edit-total-stock').value = book.total_stock || 1;
        document.getElementById('edit-isbn').value = book.isbn || '';
        document.getElementById('edit-shelf').value = book.location_shelf || '';
        document.getElementById('edit-description').value = book.description || '';
        openModal('modal-edit-book');
    }
</script>
@endsection
