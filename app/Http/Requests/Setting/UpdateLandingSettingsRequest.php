<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_bg' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'struktur_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'history_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Field teks (data string/integer) akan diambil via validasi implicit atau $request->except()
        ];
    }

    public function messages(): array
    {
        return [
            'hero_bg.image' => 'Background hero harus berupa gambar.',
            'hero_bg.mimes' => 'Format background hero harus jpeg, png, atau jpg.',
            'hero_bg.max' => 'Ukuran background hero maksimal 2MB.',

            'struktur_img.image' => 'Gambar struktur harus berupa gambar.',
            'struktur_img.mimes' => 'Format gambar struktur harus jpeg, png, atau jpg.',
            'struktur_img.max' => 'Ukuran gambar struktur maksimal 2MB.',

            'school_logo.image' => 'Logo sekolah harus berupa gambar.',
            'school_logo.mimes' => 'Format logo sekolah harus jpeg, png, atau jpg.',
            'school_logo.max' => 'Ukuran logo sekolah maksimal 2MB.',

            'history_image.image' => 'Gambar sejarah harus berupa gambar.',
            'history_image.mimes' => 'Format gambar sejarah harus jpeg, png, atau jpg.',
            'history_image.max' => 'Ukuran gambar sejarah maksimal 2MB.',
        ];
    }
}
