<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            'zip_code' => ['required', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.serial_no' => ['required', 'string', 'max:255'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.price' => ['required', 'numeric', 'decimal:0,2', 'gt:0'],
            'products.*.quantity' => ['required', 'integer', 'gt:0'],
        ];
    }

    public function attributes()
    {
        return [
            'products.*.serial_no' => 'product serial no on row :position',
            'products.*.name' => 'product name on row :position',
            'products.*.price' => 'product price on row :position',
            'products.*.quantity' => 'product quantity on row :position',
        ];
    }
}
