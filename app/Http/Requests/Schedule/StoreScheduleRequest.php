<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreScheduleRequest extends FormRequest
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
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'day' => [
                'required',
                'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            ],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string', 'after:start_time'],
        ];
    }

    /**
     * Kumpulan pesan error custom untuk validasi.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages()
    {
        return [
            'classroom_id.required' => 'Kelas harus dipilih',
            'classroom_id.exists' => 'Kelas yang dipilih tidak valid',

            'subject_id.required' => 'Mata pelajaran harus dipilih',
            'subject_id.exists' => 'Mata pelajaran tidak valid',

            'teacher_id.required' => 'Guru harus dipilih',
            'teacher_id.exists' => 'Guru tidak valid',

            'day.required' => 'Hari harus dipilih',
            'day.in' => 'Hari harus berupa Senin sampai Sabtu',

            'start_time.required' => 'Jam mulai harus diisi',
            'end_time.required' => 'Jam selesai harus diisi',
            'end_time.after' => 'Jam selesai harus setelah jam mulai pelajaran',
        ];
    }
}
