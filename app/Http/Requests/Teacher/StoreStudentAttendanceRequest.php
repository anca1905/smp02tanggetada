<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentAttendanceRequest extends FormRequest
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
            'class' => ['required', 'exists:classrooms,id'],
            'session_type' => ['required', 'string', 'in:apel,kelas,pulang'],
            'subject_id' => ['nullable', 'required_if:session_type,kelas', 'exists:subjects,id'],
            'date' => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', 'string', 'in:present,sick,permission,absent,late'],
        ];
    }

    public function messages(): array
    {
        return [
            'class.required' => 'Kelas harus dipilih.',
            'class.exists' => 'Kelas tidak ditemukan.',
            'session_type.required' => 'Jenis sesi harus dipilih.',
            'session_type.in' => 'Jenis sesi tidak valid (apel/kelas/pulang).',
            'subject_id.required_if' => 'Mata pelajaran harus dipilih untuk sesi kelas.',
            'subject_id.exists' => 'Mata pelajaran tidak ditemukan.',
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'attendance.required' => 'Data kehadiran harus diisi.',
            'attendance.array' => 'Format data kehadiran tidak valid.',
            'attendance.*.status.required' => 'Status kehadiran siswa harus diisi.',
            'attendance.*.status.string' => 'Format status kehadiran tidak valid.',
            'attendance.*.status.in' => 'Status kehadiran tidak valid.',
        ];
    }
}
