@extends('layouts.app')

@section('title', 'Detail Pendaftar PPDB')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('tu.ppdb.index') }}" class="text-sm text-blue-600 hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Detail Pendaftar: {{ $ppdb->nama_lengkap }}</h2>
        <p class="text-sm text-gray-500">No. Registrasi: {{ $ppdb->no_registrasi }}</p>
    </div>
    
    <div class="flex gap-2">
        @if ($ppdb->status_pendaftaran !== 'Accepted')
            <form action="{{ route('tu.ppdb.updateStatus', $ppdb->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_pendaftaran" value="Accepted">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition shadow-sm">
                    <i class="fas fa-check mr-1"></i> Terima
                </button>
            </form>
        @endif
        @if ($ppdb->status_pendaftaran !== 'Rejected')
            <form action="{{ route('tu.ppdb.updateStatus', $ppdb->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_pendaftaran" value="Rejected">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition shadow-sm">
                    <i class="fas fa-times mr-1"></i> Tolak
                </button>
            </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Data Siswa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-user text-blue-600 mr-2"></i> Data Pribadi Calon Siswa</h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">NISN / NIK</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->nisn ?? '-' }} / {{ $ppdb->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tempat, Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->tempat_lahir }}, {{ $ppdb->tanggal_lahir ? $ppdb->tanggal_lahir->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jenis Kelamin</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->jenis_kelamin_label }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Agama</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->agama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">No. WhatsApp</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->no_hp ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Alamat Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $ppdb->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Orang Tua --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-users text-blue-600 mr-2"></i> Data Orang Tua</h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">Data Ayah</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Nama Ayah</p>
                                <p class="font-semibold text-sm text-gray-800">{{ $ppdb->nama_ayah ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pekerjaan</p>
                                <p class="font-semibold text-sm text-gray-800">{{ $ppdb->pekerjaan_ayah ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Penghasilan</p>
                                <p class="font-semibold text-sm text-gray-800">{{ $ppdb->penghasilan_ayah ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">Data Ibu</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Nama Ibu</p>
                                <p class="font-semibold text-sm text-gray-800">{{ $ppdb->nama_ibu ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pekerjaan</p>
                                <p class="font-semibold text-sm text-gray-800">{{ $ppdb->pekerjaan_ibu ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Penghasilan</p>
                                <p class="font-semibold text-sm text-gray-800">{{ $ppdb->penghasilan_ibu ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="space-y-6">
        
        {{-- Status Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-info-circle text-blue-600 mr-2"></i> Status Pendaftaran</h3>
            </div>
            <div class="p-5">
                @php
                    $badgeClass = match($ppdb->status_pendaftaran) {
                        'Accepted' => 'bg-green-100 text-green-700',
                        'Rejected' => 'bg-red-100 text-red-700',
                        default    => 'bg-yellow-100 text-yellow-700',
                    };
                    $badgeLabel = match($ppdb->status_pendaftaran) {
                        'Accepted' => 'Diterima',
                        'Rejected' => 'Ditolak',
                        default    => 'Pending / Menunggu Verifikasi',
                    };
                @endphp
                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg border border-gray-100 mb-4">
                    <span class="text-sm font-bold px-4 py-2 rounded-full {{ $badgeClass }} mb-2">
                        {{ $badgeLabel }}
                    </span>
                    <p class="text-xs text-gray-500">Mendaftar pada: {{ $ppdb->tanggal_daftar ? $ppdb->tanggal_daftar->format('d M Y H:i') : '-' }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-500 mb-1">Asal Sekolah</p>
                    <p class="font-semibold text-gray-800">{{ $ppdb->asal_sekolah ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Dokumen --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-folder-open text-blue-600 mr-2"></i> Berkas Unggahan</h3>
            </div>
            <div class="p-0 divide-y divide-gray-100">
                
                @php
                    $documents = [
                        ['label' => 'Pas Photo 3x4', 'field' => 'doc_pas_photo'],
                        ['label' => 'Ijazah / SKL', 'field' => 'doc_ijazah'],
                        ['label' => 'Transkrip Nilai', 'field' => 'doc_transkrip'],
                        ['label' => 'Sertifikat TKA', 'field' => 'doc_tka'],
                        ['label' => 'Akta Kelahiran', 'field' => 'doc_akta'],
                        ['label' => 'Kartu Keluarga', 'field' => 'doc_kk'],
                        ['label' => 'KTP Ayah', 'field' => 'doc_ktp_ayah'],
                        ['label' => 'KTP Ibu', 'field' => 'doc_ktp_ibu'],
                    ];
                @endphp

                @foreach($documents as $doc)
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ $doc['label'] }}</span>
                        </div>
                        @if($ppdb->{$doc['field']})
                            <a href="{{ Storage::url($ppdb->{$doc['field']}) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded text-xs font-bold hover:bg-blue-100 transition">
                                <i class="fas fa-external-link-alt mr-1"></i> Buka
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic">Tidak ada</span>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</div>
@endsection
