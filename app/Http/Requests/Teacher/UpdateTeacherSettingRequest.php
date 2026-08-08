<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateTeacherSettingRequest extends FormRequest
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
        $teacher = Auth::user();

        return [
            'name' => 'required|string|max:100',
            'username' => ['required', Rule::unique('teachers', 'username')->ignore($teacher->id)],
            'photo_url' => 'nullable|image|max:2048',
            'current_password' => 'required_with:new_password,username',
            'new_password' => 'nullable|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa string.',
            'name.max' => 'Nama tidak boleh lebih dari 100 karakter.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'photo_url.image' => 'Foto harus berupa gambar.',
            'photo_url.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            'current_password.required_with' => 'Password saat ini harus diisi jika mengisi password baru atau mengubah username.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];
    }

    /**
     * Handle after validation routines.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('current_password')) {
                if (! Hash::check($this->current_password, Auth::user()->password)) {
                    $validator->errors()->add('current_password', 'Password lama salah.');
                }
            }
        });
    }
}
