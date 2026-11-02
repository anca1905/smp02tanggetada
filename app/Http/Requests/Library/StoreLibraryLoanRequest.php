<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'exists:library_books,id'],
            'student_id' => ['required', 'exists:students,id'],
            'loan_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'officer_name' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Buku yang dipinjam wajib dipilih.',
            'student_id.required' => 'Siswa peminjam wajib dipilih.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'due_date.required' => 'Batas waktu kembali (due date) wajib diisi.',
            'due_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal peminjaman.',
        ];
    }
}
