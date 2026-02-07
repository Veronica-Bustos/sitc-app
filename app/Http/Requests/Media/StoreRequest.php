<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'file_path' => ['required', 'string', 'max:500'],
            'file_name' => ['required', 'string', 'max:255'],
            'original_name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:100'],
            'size' => ['required', 'integer', 'gt:0'],
            'disk' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_featured' => ['required'],
            'order' => ['required', 'integer'],
            'uploader_id' => ['nullable', 'integer', 'exists:users,id'],
            'attachable_id' => ['required', 'integer'],
            'attachable_type' => ['required', 'string', 'max:100'],
        ];
    }
}
