<?php

namespace App\Http\Requests\Logistics;

use Illuminate\Foundation\Http\FormRequest;

class MovementUpdateRequest extends FormRequest
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
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'from_location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'to_location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'movement_type' => ['required', 'string', 'max:20'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'quantity' => ['required', 'integer'],
            'notes' => ['nullable', 'string'],
            'reason' => ['nullable', 'string', 'max:100'],
            'reference_document' => ['nullable', 'string', 'max:50'],
            'performed_at' => ['required'],
        ];
    }
}
