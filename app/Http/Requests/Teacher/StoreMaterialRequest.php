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
}
