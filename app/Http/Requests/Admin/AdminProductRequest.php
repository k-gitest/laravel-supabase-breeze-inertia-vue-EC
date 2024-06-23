<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminProductRequest extends FormRequest
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
        $productId = $this->route('id');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'price_excluding_tax' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];

        if($this->method() === 'POST'){
            $rules['name'][] = Rule::unique('products', 'name');
        }
        elseif($this->method() === 'PUT'){
            $rules['name'][] = Rule::unique('products', 'name')->ignore($productId);
        }

        return $rules;
        
    }
}
