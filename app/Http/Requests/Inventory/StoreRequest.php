<?php

namespace App\Http\Requests\Inventory;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Item::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:items,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'current_location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'status' => ['required', 'string', 'max:20'],
            'condition' => ['required', 'string', 'max:20'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'between:-9999999999.99,9999999999.99'],
            'current_value' => ['nullable', 'numeric', 'between:-9999999999.99,9999999999.99'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'supplier' => ['nullable', 'string', 'max:150'],
            'warranty_expiry' => ['nullable', 'date'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'qr_code' => ['nullable', 'string', 'max:100'],
            'minimum_stock' => ['required', 'integer'],
            'unit_of_measure' => ['nullable', 'string', 'max:20'],
            'weight_kg' => ['nullable', 'numeric', 'between:-999999.99,999999.99'],
            'dimensions' => ['nullable', 'string', 'max:50'],
        ];
    }
}
