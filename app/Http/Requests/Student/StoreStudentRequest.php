<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:100'],
            'nis' => ['required', 'string', 'max:20', 'unique:students,nis'],
            'nisn' => ['nullable', 'string', 'size:10', 'unique:students,nisn'],
            'gender' => ['required', 'in:M,F'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
            ],
            'student_status' => ['required', 'in:Active,Graduated,Inactive'],
            'photo_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'parent_name' => ['nullable', 'string', 'max:100'],
            'parent_phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'student_name.required' => 'Nama siswa wajib diisi.',
            'student_name.string' => 'Nama siswa harus berupa string/karakter.',
            'student_name.max' => 'Nama siswa tidak boleh lebih dari 100 karakter.',

            'nis.required' => 'NIS wajib diisi.',
            'nis.string' => 'NIS harus berupa string/karakter.',
            'nis.max' => 'NIS tidak boleh lebih dari 20 karakter.',
            'nis.unique' => 'NIS sudah terdaftar.',

            'nisn.string' => 'NISN harus berupa angka.',
            'nisn.size' => 'NISN harus tepat 10 karakter.',
            'nisn.unique' => 'NISN sudah terdaftar.',

            'phone_number.string' => 'Nomor telepon harus berupa string/karakter.',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'phone_number.regex' => 'Nomor telepon harus dalam format indonesia (08xxxxxxxx atau +628xxxxxxxx).',

            'gender.required' => 'Jenis kelamin wajib diisi.',
            'gender.in' => 'Jenis kelamin harus berupa M (Laki-laki) atau F (Perempuan).',

            'classroom_id.required' => 'Kelas wajib diisi.',
            'classroom_id.exists' => 'Kelas tidak valid.',

            'student_status.required' => 'Status siswa wajib diisi.',
            'student_status.in' => 'Status siswa harus berupa Active (Aktif), Graduated (Lulus), atau Inactive (Tidak Aktif).',

            'parent_phone.string' => 'Nomor telepon orang tua harus berupa string/karakter.',
            'parent_phone.max' => 'Nomor telepon orang tua tidak boleh lebih dari 20 karakter.',
            'parent_phone.regex' => 'Nomor telepon orang tua harus dalam format indonesia (08xx-xxxxxx atau +628xx-xxxxxx).',

            'photo_url.image' => 'File harus berupa gambar.',
            'photo_url.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'photo_url.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
