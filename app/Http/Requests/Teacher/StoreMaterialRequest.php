<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
            'schedule_id' => 'required',
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,youtube,link',
            'file' => 'nullable|required_if:type,pdf|mimes:pdf,doc,docx,ppt,pptx|max:10240', // Max 10MB
            'url' => 'nullable|required_if:type,youtube,link|url',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_id.required' => 'Jadwal harus dipilih.',
            'title.required' => 'Judul materi harus diisi.',
            'title.string' => 'Judul materi harus berupa teks.',
            'title.max' => 'Judul materi tidak boleh lebih dari 255 karakter.',
            'type.required' => 'Tipe materi harus dipilih.',
            'type.in' => 'Tipe materi tidak valid.',
            'file.required_if' => 'File harus diunggah untuk tipe PDF/Dokumen.',
            'file.mimes' => 'Format file tidak didukung. Gunakan PDF, DOC, DOCX, PPT, atau PPTX.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
            'url.required_if' => 'URL harus diisi untuk tipe YouTube atau Link.',
            'url.url' => 'Format URL tidak valid.',
        ];
    }
}
