<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'products.*.serial_no' => ['required', 'string', 'distinct', 'max:255'],
            'products.*.name' => ['required', 'string', 'distinct', 'max:255'],
            'products.*.price' => ['required', 'numeric', 'decimal:2', 'gt:0'],
            'products.*.quantity' => ['required', 'integer', 'gt:0'],
        ];
    }
}
