<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'barcode' => [
                'required',
                'string',
                'max:64',
                Rule::unique('products', 'barcode')->ignore($this->route('product')),
            ],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'barcode.required' => 'Barcode wajib diisi.',
            'barcode.unique' => 'Barcode sudah terdaftar untuk produk lain.',
            'name.required' => 'Nama barang wajib diisi.',
        ];
    }
}
