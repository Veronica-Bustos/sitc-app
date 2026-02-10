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
        return $this->user()->can('create', \App\Models\Attachment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => [
                'required',
                'file',
                'max:10240', // 10MB max
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,text/csv',
            ],
            'attachable_type' => ['required', 'string', 'in:item,movement,maintenance'],
            'attachable_id' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'files' => __('files'),
            'files.*' => __('file'),
            'attachable_type' => __('entity type'),
            'attachable_id' => __('entity'),
            'description' => __('description'),
            'is_featured' => __('featured'),
            'order' => __('display order'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'files.required' => __('Please select at least one file to upload.'),
            'files.max' => __('You can upload a maximum of 10 files at once.'),
            'files.*.max' => __('Each file must not exceed 10MB.'),
            'files.*.mimetypes' => __('Invalid file type. Allowed: Images, PDF, Word, Excel, Text.'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'order' => $this->input('order', 0),
        ]);
    }
}
