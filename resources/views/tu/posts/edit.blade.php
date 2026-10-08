@extends('layouts.app')

@section('title', 'Edit Berita - ' . $post->title)

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            overflow: hidden;
            background-color: #fff;
        }
        .note-toolbar {
            background-color: #f9fafb !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 8px 10px !important;
        }
        .note-btn {
            background: #fff !important;
            border: 1px solid #d1d5db !important;
            color: #374151 !important;
            border-radius: 0.375rem !important;
            padding: 4px 8px !important;
            font-size: 13px !important;
        }
        .note-btn:hover {
            background-color: #f3f4f6 !important;
        }
        .note-editable {
            min-height: 350px;
            font-family: inherit;
            font-size: 15px;
            line-height: 1.6;
            color: #1f2937;
            background-color: #fff;
        }
        .note-editable img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 8px 0;
        }
    </style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('tu.posts.index') }}" class="text-sm text-blue-600 hover:underline mb-2 inline-flex items-center">
                <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Berita
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Edit Berita & Pengumuman</h2>
            <p class="text-sm text-gray-500">Perbarui konten artikel dan kelola gambar yang disisipkan.</p>
        </div>
        <div>
            <a href="{{ route('public.berita.show', $post->slug) }}" target="_blank"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition inline-flex items-center gap-1.5 border border-gray-300 shadow-sm">
                <i class="fas fa-external-link-alt"></i> Pratinjau di Web
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
            <p class="font-bold mb-1">Periksa kembali data yang diinput:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form action="{{ route('tu.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 placeholder-gray-400"
                    placeholder="Contoh: Semarak Bulan Bahasa, Perpustakaan SMPN 2 Tanggetada Gelar Lomba Literasi">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800 bg-white">
                        <option value="Pengumuman" {{ old('category', $post->category) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Prestasi" {{ old('category', $post->category) == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        <option value="Kegiatan" {{ old('category', $post->category) == 'Kegiatan' ? 'selected' : '' }}>Kegiatan Siswa</option>
                        <option value="Akademik" {{ old('category', $post->category) == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Ganti Gambar Sampul Utama (Thumbnail Header)
                    </label>
                    <input type="file" name="image" id="thumbnailInput" accept="image/jpeg,image/png,image/jpg,image/webp"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah thumbnail. Maks 2MB.</p>
                </div>
            </div>

            {{-- Pratinjau Thumbnail Saat Ini / Baru --}}
            <div id="thumbnailPreviewContainer" class="{{ $post->image ? '' : 'hidden' }}">
                <p class="text-xs font-semibold text-gray-500 mb-1">
                    <span id="thumbnailLabel">Sampul Saat Ini:</span>
                </p>
                <img id="thumbnailPreview" src="{{ $post->image ? asset('storage/' . $post->image) : '' }}"
                    alt="Sampul Berita" class="h-40 w-auto rounded-lg object-cover border border-gray-200 shadow-sm">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-sm font-semibold text-gray-700">
                        Isi Berita / Artikel <span class="text-red-500">*</span>
                    </label>
                    <span class="text-xs text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100 flex items-center gap-1">
                        <i class="fas fa-image"></i> Mendukung banyak gambar & teks kaya
                    </span>
                </div>
                <textarea id="summernote" name="content" required>{{ old('content', $post->content) }}</textarea>
                <div class="mt-2 p-3 bg-amber-50 rounded-lg border border-amber-200 text-xs text-amber-800 flex items-start gap-2">
                    <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                    <div>
                        <strong>Tips Memasukkan Gambar di Artikel:</strong>
                        <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-amber-700">
                            <li>Klik tombol <i class="fas fa-image"></i> (Picture) pada toolbar editor untuk memilih file gambar dari komputer/HP.</li>
                            <li>Atau tarik & lepas (drag and drop) gambar langsung ke dalam artikel.</li>
                            <li>Anda bisa memasukkan banyak gambar di posisi mana saja di dalam teks artikel.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('tu.posts.index') }}"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md transition inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote Lite
            $('#summernote').summernote({
                placeholder: 'Tulis isi berita atau artikel di sini...',
                tabsize: 2,
                height: 400,
                minHeight: 280,
                dialogsInBody: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadSummernoteImage(files[i], $(this));
                        }
                    }
                }
            });

            // AJAX Upload Gambar ke Server
            function uploadSummernoteImage(file, editor) {
                let data = new FormData();
                data.append("image", file);
                data.append("_token", "{{ csrf_token() }}");

                // Indikator loading
                Swal.fire({
                    title: 'Mengunggah Gambar...',
                    text: 'Mohon tunggu beberapa saat',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('tu.posts.upload-image') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "POST",
                    success: function(response) {
                        Swal.close();
                        if (response.url) {
                            editor.summernote('insertImage', response.url, function($image) {
                                $image.addClass('img-fluid rounded shadow-sm my-2');
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        let msg = 'Gagal mengunggah gambar ke server.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Unggah Gagal',
                            text: msg,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                });
            }

            // Preview Thumbnail
            $('#thumbnailInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        $('#thumbnailPreview').attr('src', evt.target.result);
                        $('#thumbnailLabel').text('Pratinjau Sampul Baru:');
                        $('#thumbnailPreviewContainer').removeClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
