<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'request_date' => ['required', 'date'],
            'intervention_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'type' => ['required', 'string', 'max:30'],
            'status' => ['required', 'string', 'max:20'],
            'priority' => ['required', 'string', 'max:10'],
            'description' => ['required', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'actions_taken' => ['nullable', 'string'],
            'parts_replaced' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'between:-99999999.99,99999999.99'],
            'technician_id' => ['nullable', 'integer', 'exists:users,id'],
            'requester_id' => ['nullable', 'integer', 'exists:users,id'],
            'next_maintenance_date' => ['nullable', 'date'],
        ];
    }
}
