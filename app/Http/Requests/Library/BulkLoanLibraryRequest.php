<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class BulkLoanLibraryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'exists:library_books,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'loan_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'officer_name' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Buku paket BOS wajib dipilih.',
            'classroom_id.required' => 'Kelas / rombel sasaran wajib dipilih.',
            'loan_date.required' => 'Tanggal pendistribusian buku wajib diisi.',
            'due_date.required' => 'Batas waktu kembali (akhir semester/tahun) wajib diisi.',
            'due_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pendistribusian.',
        ];
    }
}
