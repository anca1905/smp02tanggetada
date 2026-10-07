<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class ScanBarcodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nis' => trim((string) ($this->nis ?? $this->barcode ?? '')),
            'date' => $this->date ?? now()->toDateString(),
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => ['required', 'string'],
            'session_type' => ['required', 'string', 'in:apel,kelas,pulang'],
            'class' => ['required', function ($attribute, $value, $fail) {
                if ($value !== 'all' && ! \App\Models\Classroom::where('id', $value)->exists()) {
                    $fail('Kelas tidak ditemukan.');
                }
            }],
            'date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.required' => 'NIS siswa harus diisi.',
            'session_type.required' => 'Jenis sesi harus dipilih.',
            'session_type.in' => 'Jenis sesi tidak valid.',
            'class.required' => 'Kelas harus dipilih.',
            'class.exists' => 'Kelas tidak ditemukan.',
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
