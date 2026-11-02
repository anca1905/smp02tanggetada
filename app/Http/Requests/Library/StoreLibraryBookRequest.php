<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_code' => ['required', 'string', 'max:50', 'unique:library_books,book_code'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publish_year' => ['nullable', 'string', 'max:10'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:50'],
            'class_level' => ['required', 'string', 'max:10'],
            'total_stock' => ['required', 'integer', 'min:1'],
            'location_shelf' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_code.required' => 'Kode buku wajib diisi.',
            'book_code.unique' => 'Kode buku sudah digunakan.',
            'title.required' => 'Judul buku wajib diisi.',
            'category.required' => 'Kategori buku wajib dipilih.',
            'class_level.required' => 'Tingkat kelas sasaran wajib dipilih.',
            'total_stock.required' => 'Jumlah stok wajib diisi.',
            'total_stock.min' => 'Jumlah stok minimal 1 eksemplar.',
        ];
    }
}
